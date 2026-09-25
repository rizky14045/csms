<?php

namespace App\Services\Marturity;

use App\Helpers\JsonResponse;
use App\Models\Area;
use App\Models\Level;
use App\Models\Marturity;
use App\Models\MarturityArea;
use App\Models\MarturityFileCheck;
use App\Models\User;
use App\Models\MarturityLevel;
use App\Models\MarturityNote;
use App\Models\MarturitySubArea;
use App\Models\Note;
use App\Models\SubArea;
use App\Services\ActivityLog\ActivityLogService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MarturityService
{
    use \App\Services\Concerns\AllocatesIds;

    protected $logService;

    public function __construct(ActivityLogService $logService)
    {
        $this->logService = $logService;
    }
    
   public function getAlMarturity($limit = 10, $paginate = true, $with = [], $unit_id = null, $send_status = null)
    {
        try {
            $order  = request('order', 'DESC');
            $search = request('q', '');
            $ref    = request('ref', 'id');
            $start  = request('start', null);
            $end    = request('end', null);
            $date  = request('date', null);

            $query = Marturity::query();

            if (!empty($with)) {
                $query->with($with);
            }

            $user = auth()->user();

            if ($user->roles[0]->name == 'Pusat') {

                $query->where(function ($q) use ($user, $send_status) {

                    // base logic Pusat
                    $q->where(function ($sub) use ($user) {
                        $sub->where('send_status', 1)
                            ->orWhere(function ($x) use ($user) {
                                $x->where('send_status', 0)
                                ->where('unit_id', $user->unit_id);
                            });
                    });

                });

                if ($unit_id) {
                    $query->where('unit_id', $unit_id);
                }

            } else {

                if ($unit_id) {
                    $query->where('unit_id', $unit_id);
                }

                if ($send_status !== null) {
                    $query->where('send_status', $send_status);
                }
            }

            if ($date) {
                $query->whereDate('date', $date);
            }

            if ($start && $end) {
                $end = date('Y-m-d', strtotime($end . ' +1 day'));
                $query->whereBetween('created_at', [$start, $end]);
            } elseif ($start) {
                $query->whereDate('created_at', '>=', $start);
            } elseif ($end) {
                $query->whereDate('created_at', '<=', $end);
            }

            $allowedSort = ['id', 'name', 'created_at'];
            if (!in_array($ref, $allowedSort)) {
                $ref = 'id';
            }

            $query->orderBy($ref, $order);

            if ($paginate) {
                $data = $query->paginate($limit)->withQueryString();
            } else {
                $data = $limit > 0
                    ? $query->limit($limit)->get()
                    : $query->get();
            }

            return JsonResponse::success($data, 'Marturity found', 200);

        } catch (Exception $e) {

            $this->logService->log(
                'marturity.fetch_all',
                'Failed to fetch marturities',
                500,
                [
                    'error' => $e->getMessage(),
                    'params' => request()->all(),
                ]
            );

            return JsonResponse::error(
                $e->getMessage(),
                'Marturity not found',
                500
            );
        }
    }

    public function createMarturity(array $data)
    {
        set_time_limit(120);

        DB::beginTransaction();

        try {
            $userId = auth()->id();
            $unit_id = auth()->user()->unit_id;

            $marturity = Marturity::create([
                'unit_id'   => $unit_id,
                'year'  => $data['year'],
                'triwulan'  => $data['triwulan'],
                'created_by'=> $userId,
            ]);

            $areas = Area::where('type', 'marturity')->orderBy('order')->orderBy('id')->get();

            if ($areas->isEmpty()) {
                DB::rollBack();

                return JsonResponse::error(
                    'Master data Maturity belum tersedia',
                    'Failed to create marturity',
                    422
                );
            }

            $subAreasByArea = SubArea::whereIn('area_id', $areas->pluck('id'))
                ->orderBy('order')->orderBy('id')->get()->groupBy('area_id');
            $levelsBySub = Level::whereIn('sub_area_id', $subAreasByArea->flatten()->pluck('id'))
                ->orderBy('order')->orderBy('id')->get()->groupBy('sub_area_id');
            $notesByLevel = Note::whereIn('level_id', $levelsBySub->flatten()->pluck('id'))
                ->orderBy('order')->orderBy('id')->get()->groupBy('level_id');

            $now = now();
            $base = ['unit_id' => $unit_id, 'marturity_id' => $marturity->id, 'created_by' => $userId, 'created_at' => $now, 'updated_at' => $now];

            $areaIds = $this->allocateIds('marturity_areas', $areas->count());
            $areaRows = [];
            $subRows = [];
            $levelRows = [];
            $noteRows = [];
            $subCount = $levelCount = $noteCount = 0;

            foreach ($areas as $ai => $area) {
                $newAreaId = $areaIds[$ai];
                $areaRows[] = $base + ['id' => $newAreaId, 'name' => $area->name, 'order' => $ai + 1];
                $subCount += ($subAreasByArea[$area->id] ?? collect())->count();
            }

            $subIds = $this->allocateIds('marturity_sub_areas', $subCount);
            $si = 0;
            $pendingLevels = [];
            foreach ($areas as $ai => $area) {
                foreach (($subAreasByArea[$area->id] ?? collect())->values() as $sj => $subArea) {
                    $newSubId = $subIds[$si++];
                    $subRows[] = $base + [
                        'id' => $newSubId, 'area_id' => $areaIds[$ai], 'name' => $subArea->name,
                        'description' => $subArea->description, 'reference' => $subArea->reference, 'order' => $sj + 1,
                    ];
                    foreach (($levelsBySub[$subArea->id] ?? collect())->values() as $lk => $level) {
                        $pendingLevels[] = [$newSubId, $level, $lk + 1];
                    }
                }
            }

            $levelIds = $this->allocateIds('marturity_levels', count($pendingLevels));
            $pendingNotes = [];
            foreach ($pendingLevels as $li => [$newSubId, $level, $order]) {
                $newLevelId = $levelIds[$li];
                $levelRows[] = $base + [
                    'id' => $newLevelId, 'sub_area_id' => $newSubId, 'level' => $level->level,
                    'description' => $level->description, 'total_evidence' => $level->total_evidence, 'order' => $order,
                ];
                foreach (($notesByLevel[$level->id] ?? collect())->values() as $nk => $note) {
                    $noteRows[] = $base + ['level_id' => $newLevelId, 'note' => $note->note, 'order' => $nk + 1];
                }
            }

            $totalLevelsCreated = count($levelRows);

            if ($totalLevelsCreated === 0) {
                DB::rollBack();

                return JsonResponse::error(
                    'Master data Maturity belum lengkap (tidak ada level yang tersedia)',
                    'Failed to create marturity',
                    422
                );
            }

            MarturityArea::insert($areaRows);
            if ($subRows) { MarturitySubArea::insert($subRows); }
            MarturityLevel::insert($levelRows);
            if ($noteRows) { MarturityNote::insert($noteRows); }

            DB::commit();

            $this->logService->log(
                'marturity.create',
                'Create marturity with areas, subareas, levels and notes',
                200,
                [
                    'marturity_id' => $marturity->id,
                    'year' => $marturity->year,
                    'triwulan' => $marturity->triwulan,
                ]
            );

            return JsonResponse::success(
                $marturity,
                'Marturity created successfully',
                201
            );

        } catch (\Exception $e) {

            DB::rollBack();

            $this->logService->log(
                'marturity.create',
                'Failed to create marturity',
                500,
                [
                    'error' => $e->getMessage(),
                    'payload' => $data,
                ]
            );

            return JsonResponse::error(
                $e->getMessage(),
                'Failed to create marturity',
                500
            );
        }
    }

    public function updateMarturity(Marturity $marturity, array $data)
    {
        DB::beginTransaction();

        try {
            $before = $marturity->toArray();

            $updateData = [
                'year'  => $data['year'],
                'triwulan'  => $data['triwulan'],
                'updated_by' => auth()->id(),
            ];

            $marturity->update($updateData);

            DB::commit();

            $this->logService->log(
                'marturity.update',
                'Update marturity',
                200,
                [
                    'before' => $before,
                    'after'  => $marturity->toArray(),
                ]
            );

            return JsonResponse::success(
                $marturity,
                'Marturity updated',
                201
            );

        } catch (Exception $e) {
            DB::rollBack();

            $this->logService->log(
                'marturity.update',
                'Failed to update marturity',
                500,
                [
                    'error' => $e->getMessage(),
                    'payload' => $data,
                ]
            );

            throw $e;
        }
    }

    public function deleteMarturity(Marturity $marturity)
    {
        DB::beginTransaction();

        try {
            MarturityArea::where('marturity_id',$marturity->id)->update(['deleted_by' => auth()->id()]);
            MarturityArea::where('marturity_id',$marturity->id)->delete();
            MarturitySubArea::where('marturity_id',$marturity->id)->update(['deleted_by' => auth()->id()]);
            MarturitySubArea::where('marturity_id',$marturity->id)->delete();
            MarturityLevel::where('marturity_id',$marturity->id)->update(['deleted_by' => auth()->id()]);
            MarturityLevel::where('marturity_id',$marturity->id)->delete();
            MarturityNote::where('marturity_id',$marturity->id)->update(['deleted_by' => auth()->id()]);
            MarturityNote::where('marturity_id',$marturity->id)->delete();

            $marturity->deleted_by = auth()->id();
            $marturity->save();
            $marturity->delete();

            DB::commit();

            $this->logService->log(
                'marturity.delete',
                'Delete marturity with areas, subareas, levels and notes',
                200,
                [
                    'marturity_id' => $marturity->id,
                    'date' => $marturity->date,
                    'triwulan' => $marturity->triwulan,
                ]
            );

            return JsonResponse::success(
                null,
                'Marturity deleted successfully',
                200
            );

        } catch (Exception $e) {

            DB::rollBack();

            $this->logService->log(
                'marturity.delete',
                'Failed to delete marturity',
                500,
                [
                    'marturity_id' => $marturity->id ?? null,
                    'date' => $marturity->date ?? null,
                    'triwulan' => $marturity->triwulan ?? null,
                    'error'   => $e->getMessage(),
                ]
            );

            return JsonResponse::error(
                $e->getMessage(),
                'Failed to delete marturity',
                500
            );
        }
    }

    public function getAlMarturityArea($with = [], $marturity_id = null)
    {
        try {
            $order  = request('order', 'ASC');
            $search = request('q', '');
            $ref    = request('ref', 'order');
            $start  = request('start', null);
            $end    = request('end', null);
            $date  = request('date', null);

            $query = MarturityArea::query();

            if (!empty($with)) {
                $query->with($with);
            }

            if ($marturity_id) {
                $query->where('marturity_id', $marturity_id);
            }

            if ($date) {
                $query->whereDate('date', $date);
            }

            if ($start && $end) {
                $end = date('Y-m-d', strtotime($end . ' +1 day'));
                $query->whereBetween('created_at', [$start, $end]);
            } elseif ($start) {
                $query->whereDate('created_at', '>=', $start);
            } elseif ($end) {
                $query->whereDate('created_at', '<=', $end);
            }

            $allowedSort = ['id', 'name', 'created_at'];
            if (!in_array($ref, $allowedSort)) {
                $ref = 'id';
            }

            $query->orderBy($ref, $order);

            $data =  $query->get();

            return JsonResponse::success($data, 'Marturity Area found', 200);

        } catch (Exception $e) {

            $this->logService->log(
                'marturity area.fetch_all',
                'Failed to fetch marturity areas',
                500,
                [
                    'error' => $e->getMessage(),
                    'params' => request()->all(),
                ]
            );

            return JsonResponse::error(
                $e->getMessage(),
                'Marturity Area not found',
                500
            );
        }
    }

    public function uploadNote(Request $request, Marturity $marturity, $areaid, MarturityNote $note)
    {
        DB::beginTransaction();
        try {

            $user = auth()->user();

            $note = MarturityNote::where('unit_id', $user->unit_id)
                ->where('marturity_id', $marturity->id)
                ->where('id', $note->id)
                ->first();

            if (!$note) {
                return JsonResponse::error(
                    'Note not found',
                    'Marturity Note not found',
                    404
                );
            }

            if ($request->hasFile('attachment_file_'.$note->id)) {      
                $file = $request->file('attachment_file_'.$note->id);
                $file_name = 'marturity-file-' . time() .'.'. $file->getClientOriginalExtension();
                
                if ($note->attachment_file) {
                    unlink(public_path('uploads/attachment_file_marturity_file/'.$note->attachment_file));
                }

                $file->move(public_path('uploads/attachment_file_marturity_file/'), $file_name);

                $note->update([
                    'attachment_file' => $file_name,
                    'updated_by' => $user->id,
                ]);
            }
            $note->attachment_file = $attachmentFile ?? $note->attachment_file;
            $note->save();

            DB::commit();

            return JsonResponse::success(
                $note,
                'Attachment file uploaded successfully',
                200
            );

        } catch (\Exception $e) {
            DB::rollBack();

            dd($e->getMessage());

            return JsonResponse::error(
                $e->getMessage(),
                'Failed to upload attachment file',
                500
            );
        }
    }

    public function isLevelUnlocked(MarturityLevel $level)
    {
        $siblings = MarturityLevel::where('sub_area_id', $level->sub_area_id)
            ->orderBy('order')
            ->orderBy('id')
            ->get();

        foreach ($siblings as $sibling) {
            if ($sibling->id == $level->id) {
                return true;
            }

            $files = json_decode($sibling->attachment_files ?? '[]', true) ?: [];
            if (count($files) === 0) {
                return false;
            }
        }

        return true;
    }

    public function uploadLevelFiles(Request $request, MarturityLevel $level)
    {
        DB::beginTransaction();
        try {
            $existing = json_decode($level->attachment_files ?? '[]', true) ?: [];
            $uploaded = $request->file('files') ?? [];
            if (!is_array($uploaded)) {
                $uploaded = [$uploaded];
            }

            $uploadPath = public_path('uploads/attachment_file_marturity_file/');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0775, true);
            }

            $newFiles = [];
            foreach ($uploaded as $file) {
                $name = 'marturity-file-' . time() . '-' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move($uploadPath, $name);
                $newFiles[] = $name;
            }

            $allFiles = array_values(array_merge($existing, $newFiles));

            $level->update([
                'attachment_files' => json_encode($allFiles),
                'updated_by'       => auth()->id(),
            ]);

            DB::commit();

            return JsonResponse::success(['files' => $allFiles], 'Upload berhasil', 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return JsonResponse::error($e->getMessage(), 'Upload gagal', 500);
        }
    }

    public function deleteLevelFile(MarturityLevel $level, string $filename)
    {
        DB::beginTransaction();
        try {
            $existing = json_decode($level->attachment_files ?? '[]', true) ?: [];
            $existing = array_values(array_filter($existing, fn($f) => $f !== $filename));

            $filePath = public_path('uploads/attachment_file_marturity_file/' . $filename);
            if (file_exists($filePath)) {
                unlink($filePath);
            }

            $level->update([
                'attachment_files' => json_encode($existing),
                'updated_by'       => auth()->id(),
            ]);

            DB::commit();

            return JsonResponse::success(['files' => $existing], 'File dihapus', 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return JsonResponse::error($e->getMessage(), 'Hapus gagal', 500);
        }
    }

    public function sendMarturity(Marturity $marturity)
    {
        DB::beginTransaction();

        try {
            if($marturity->send_status == true || (int) $marturity->status === 1){
                DB::rollBack();
                return JsonResponse::error(
                    'Marturity sudah dikirim',
                    'Failed to send marturity',
                    400
                );
            }

            $user = auth()->user();
            // Selalu dikirim ke MMRK dulu; MMRK yang meneruskan ke Pusat.
            $marturity->update([
                'status' => 1,
                'mmrk_send_date' => date('Y-m-d'),
                'updated_by' => $user->id,
            ] + ((int) $marturity->rebuttal_state === 1 ? ['rebuttal_state' => 2] : []));

            DB::commit();

            $this->logService->log('marturity.send', 'Send marturity', 200, [
                'marturity_id' => $marturity->id,
                'status' => $marturity->status,
            ]);

            return JsonResponse::success($marturity, 'Marturity sent successfully', 200);

        } catch (\Exception $e) {
            DB::rollBack();

            $this->logService->log('marturity.send', 'Failed to send marturity', 500, [
                'marturity_id' => $marturity->id,
                'error' => $e->getMessage(),
            ]);

            return JsonResponse::error($e->getMessage(), 'Failed to send marturity', 500);
        }
    }

    public function sendToPusat(Marturity $marturity)
    {
        if ((int) $marturity->status !== 1) {
            return false;
        }

        $marturity->update([
            'status' => 2,
            'send_status' => true,
            'send_date' => date('Y-m-d'),
            'updated_by' => auth()->id(),
        ] + ((int) $marturity->rebuttal_state === 2 ? ['rebuttal_state' => 3] : []));

        return true;
    }

    public function finishValidation(Marturity $marturity)
    {
        if ((int) $marturity->status !== 2) {
            return false;
        }

        // Selesai validasi sanggahan: cukup tandai selesai sanggah (sanggahan hanya sekali).
        if ((int) $marturity->rebuttal_state === 3) {
            $marturity->update(['status' => 3, 'rebuttal_state' => 4, 'rebuttal_finished_at' => now(), 'updated_by' => auth()->id()]);

            return true;
        }

        // Validasi pertama selesai: mulai masa sanggah 7 hari dan beri tahu Unit/Pusat lewat email.
        $marturity->update(['status' => 3, 'validated_at' => now(), 'updated_by' => auth()->id()]);
        $marturity->notifyValidationFinished('Maturity');

        return true;
    }

    public function getCheckedMap(Marturity $marturity)
    {
        $map = [];
        foreach (MarturityFileCheck::where('marturity_id', $marturity->id)->get() as $c) {
            $map[$c->level_id . '|' . $c->filename] = true;
        }

        return $map;
    }

    public function toggleCheck(Marturity $marturity, MarturityLevel $level, $filename, $checked)
    {
        if ((int) $marturity->status !== 2) {
            return [false, 'Data tidak dalam tahap validasi Pusat!'];
        }

        if ($level->marturity_id != $marturity->id) {
            return [false, 'Level tidak valid!'];
        }

        $files = json_decode($level->attachment_files ?? '[]', true) ?: [];
        if (!in_array($filename, $files, true)) {
            return [false, 'File tidak ditemukan!'];
        }

        $siblings = MarturityLevel::where('sub_area_id', $level->sub_area_id)
            ->orderBy('order')->orderBy('id')->get();

        if ($checked) {
            foreach ($siblings as $sibling) {
                if ($sibling->id == $level->id) {
                    break;
                }

                $siblingFiles = json_decode($sibling->attachment_files ?? '[]', true) ?: [];
                $done = MarturityFileCheck::where('level_id', $sibling->id)
                    ->whereIn('filename', $siblingFiles)->count();

                if (count($siblingFiles) === 0 || $done < count($siblingFiles)) {
                    return [false, 'Centang semua file level sebelumnya terlebih dahulu!'];
                }
            }

            MarturityFileCheck::firstOrCreate(
                ['level_id' => $level->id, 'filename' => $filename],
                ['marturity_id' => $marturity->id, 'checked_by' => auth()->id()]
            );

            return [true, 'ok'];
        }

        MarturityFileCheck::where('level_id', $level->id)->where('filename', $filename)->delete();

        $after = false;
        foreach ($siblings as $sibling) {
            if ($after) {
                MarturityFileCheck::where('level_id', $sibling->id)->delete();
            }
            if ($sibling->id == $level->id) {
                $after = true;
            }
        }

        return [true, 'ok'];
    }
}
