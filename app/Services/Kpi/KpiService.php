<?php

namespace App\Services\Kpi;

use App\Helpers\JsonResponse;
use App\Models\Area;
use App\Models\CategoryAssesment;
use App\Models\Kpi;
use App\Models\KpiArea;
use App\Models\KpiLevel;
use App\Models\KpiLevelCheck;
use App\Models\User;
use App\Models\KpiNote;
use App\Models\KpiSubArea;
use App\Models\Level;
use App\Models\Note;
use App\Models\SubArea;
use App\Services\ActivityLog\ActivityLogService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KpiService
{
    use \App\Services\Concerns\AllocatesIds;

    protected $logService;

    public function __construct(ActivityLogService $logService)
    {
        $this->logService = $logService;
    }
    
   public function getAllKpi($limit = 10, $paginate = true, $unit_id = null, $with = [], $send_status = null)
    {
        try {
            $order  = request('order', 'DESC');
            $search = request('q', '');
            $ref    = request('ref', 'id');
            $start  = request('start', null);
            $end    = request('end', null);
            $date  = request('date', null);

            $query = Kpi::query();

            if (!empty($with)) {
                $query->with($with);
            }

            $user = Auth::guard('web')->user();

            if(auth()->user()->roles[0]->name == 'Pusat') {
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

                if($send_status !== null){
                    $query->where('send_status', $send_status);
                }
            }

            if (!empty($search)) {
                $query->where(function ($q) use ($search) {
                    $q->where('send_status', 'like', "%{$search}%");
                });
            }

            if($date){
                $query->where('date', $date);
            }

            if ($start && $end) {
                $end = date('Y-m-d', strtotime($end . ' +1 day'));
                $query->whereBetween('created_at', [$start, $end]);
            } elseif ($start) {
                $query->whereDate('created_at', '>=', $start);
            } elseif ($end) {
                $query->whereDate('created_at', '<=', $end);
            }

            $allowedSort = ['id', 'send_status', 'created_at'];
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

            return JsonResponse::success($data, 'Kpi found', 200);

        } catch (Exception $e) {

            $this->logService->log(
                'kpi.fetch_all',
                'Failed to fetch kpis',
                500,
                [
                    'error' => $e->getMessage(),
                    'params' => request()->all(),
                ]
            );

            return JsonResponse::error(
                $e->getMessage(),
                'Kpi not found',
                500
            );
        }
    }

    public function createKpi(array $data)
    {
        set_time_limit(120);

        DB::beginTransaction();

        try {
            $user = Auth::guard('web')->user();
            
            $kpi = Kpi::create([
                'unit_id' => $user->unit_id,
                'year' => $data['year'],
                'triwulan' => $data['triwulan'],
                'created_by' => $user->id,
            ]);
            
            $areas = Area::where('type','kpi')->orderBy('order')->orderBy('id')->get();

            if ($areas->isEmpty()) {
                DB::rollBack();

                return JsonResponse::error(
                    'Master data KPI belum tersedia',
                    'Failed to create kpi',
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
            $base = ['unit_id' => $user->unit_id, 'kpi_id' => $kpi->id, 'created_by' => $user->id, 'created_at' => $now, 'updated_at' => $now];

            $areaIds = $this->allocateIds('kpi_areas', $areas->count());
            $areaRows = [];
            $subRows = [];
            $levelRows = [];
            $noteRows = [];
            $subCount = 0;

            foreach ($areas as $ai => $area) {
                $areaRows[] = $base + ['id' => $areaIds[$ai], 'name' => $area->name, 'order' => $ai + 1];
                $subCount += ($subAreasByArea[$area->id] ?? collect())->count();
            }

            $subIds = $this->allocateIds('kpi_sub_areas', $subCount);
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

            $levelIds = $this->allocateIds('kpi_levels', count($pendingLevels));
            foreach ($pendingLevels as $li => [$newSubId, $level, $order]) {
                $newLevelId = $levelIds[$li];
                $levelRows[] = $base + [
                    'id' => $newLevelId, 'sub_area_id' => $newSubId, 'level' => $level->level,
                    'description' => $level->description, 'order' => $order,
                ];
                foreach (($notesByLevel[$level->id] ?? collect())->values() as $nk => $note) {
                    $noteRows[] = $base + ['level_id' => $newLevelId, 'note' => $note->note, 'order' => $nk + 1];
                }
            }

            $totalLevelsCreated = count($levelRows);

            if ($totalLevelsCreated === 0) {
                DB::rollBack();

                return JsonResponse::error(
                    'Master data KPI belum lengkap (tidak ada level yang tersedia)',
                    'Failed to create kpi',
                    422
                );
            }

            KpiArea::insert($areaRows);
            if ($subRows) { KpiSubArea::insert($subRows); }
            KpiLevel::insert($levelRows);
            if ($noteRows) { KpiNote::insert($noteRows); }

            DB::commit();

            $this->logService->log(
                'kpi.create',
                'Create kpi with areas, subareas, levels and notes',
                201,
                [
                    'data' => $kpi->toArray(),
                ]
            );

            return JsonResponse::success(
                $kpi,
                'Kpi created',
                201
            );
        } catch (Exception $e) {
            DB::rollBack();

            $this->logService->log(
                'kpi.create',
                'Failed to create kpi',
                500,
                [
                    'error' => $e->getMessage(),
                    'payload' => [
                        'year' => $data['year'],
                        'triwulan' => $data['triwulan'],
                    ],
                ]
            );

            return JsonResponse::error(
                $e->getMessage(),
                'Failed to create kpi',
                500
            );
        }
    }

    public function updateKpi(Kpi $kpi, array $data)
    {
        DB::beginTransaction();

        try {
            $before = $kpi->toArray();

            $updateData = [
                'year' => $data['year'],
                'triwulan' => $data['triwulan'],
                'updated_by' => auth()->id(),
            ];

            $kpi->update($updateData);

            DB::commit();

            $this->logService->log(
                'kpi.update',
                'Update kpi',
                200,
                [
                    'before' => $before,
                    'after'  => $kpi->toArray(),
                ]
            );

            return JsonResponse::success(
                $kpi,
                'Kpi updated',
                201
            );

        } catch (Exception $e) {
            DB::rollBack();

            $this->logService->log(
                'kpi.update',
                'Failed to update kpi',
                500,
                [
                    'error' => $e->getMessage(),
                    'payload' => $data,
                ]
            );

            throw $e;
        }
    }

    public function deleteKpi(Kpi $kpi)
    {
        DB::beginTransaction();

        try {

            $user = Auth::guard('web')->user();

            KpiArea::where('kpi_id', $kpi->id)->update(['deleted_by' => $user->id]);
            KpiArea::where('kpi_id',$kpi->id)->delete();
            KpiSubArea::where('kpi_id', $kpi->id)->update(['deleted_by' => $user->id]);
            KpiSubArea::where('kpi_id',$kpi->id)->delete();
            KpiLevel::where('kpi_id', $kpi->id)->update(['deleted_by' => $user->id]);
            KpiLevel::where('kpi_id',$kpi->id)->delete();
            KpiNote::where('kpi_id', $kpi->id)->update(['deleted_by' => $user->id]);
            KpiNote::where('kpi_id',$kpi->id)->delete();

            $kpi->deleted_by = $user->id;
            $kpi->save();

            $kpi->delete();
            
            DB::commit();

            $this->logService->log(
                'kpi.delete',
                'Delete kpi with areas, subareas, levels and notes',
                200,
                $kpi->toArray()
            );

            return JsonResponse::success(
                null,
                'Kpi deleted successfully',
                200
            );

        } catch (Exception $e) {

            DB::rollBack();

            $this->logService->log(
                'kpi.delete',
                'Failed to delete kpi',
                500,
                [
                    'kpi_id' => $kpi->id ?? null,
                    'error'   => $e->getMessage(),
                ]
            );

            return JsonResponse::error(
                $e->getMessage(),
                'Failed to delete kpi',
                500
            );
        }
    }

    public function getAllKpiArea($limit = 10, $paginate = true, $kpi_id = null, $with = [])
    {
        try {
            $order  = request('order', 'ASC');
            $search = request('q', '');
            $ref    = request('ref', 'order');
            $start  = request('start', null);
            $end    = request('end', null);
            $date  = request('date', null);

            $query = KpiArea::query();

            if (!empty($with)) {
                $query->with($with);
            }

            if ($kpi_id) {
                $query->where('kpi_id', $kpi_id);
            }

            if($date){
                $query->where('date', $date);
            }

            if ($start && $end) {
                $end = date('Y-m-d', strtotime($end . ' +1 day'));
                $query->whereBetween('created_at', [$start, $end]);
            } elseif ($start) {
                $query->whereDate('created_at', '>=', $start);
            } elseif ($end) {
                $query->whereDate('created_at', '<=', $end);
            }

            $allowedSort = ['id', 'created_at'];
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

            return JsonResponse::success($data, 'Kpi areas found', 200);

        } catch (Exception $e) {

            $this->logService->log(
                'kpi_area.fetch_all',
                'Failed to fetch kpi areas',
                500,
                [
                    'error' => $e->getMessage(),
                    'params' => request()->all(),
                ]
            );

            return JsonResponse::error(
                $e->getMessage(),
                'Kpi areas not found',
                500
            );
        }
    }

    public function isLevelUnlocked(KpiLevel $level)
    {
        $siblings = KpiLevel::where('sub_area_id', $level->sub_area_id)
            ->orderBy('order')
            ->orderBy('id')
            ->get();

        foreach ($siblings as $sibling) {
            if ($sibling->id == $level->id) {
                return true;
            }

            if (empty($sibling->attachment_file)) {
                return false;
            }
        }

        return true;
    }

    public function uploadNote(Request $request, Kpi $kpi, $areaId, KpiNote $note)
    {
        DB::beginTransaction();

        try {

            $user = auth()->user();

            $attachmentFile = null;
            
            if($request->hasFile('attachment_file_'.$note->id))
            {      
                $file= $request->file('attachment_file_'.$note->id);
                $file_name = 'kpi-file-' . time() .'.'. $file->getClientOriginalExtension();
                if ($note->attachment_file) {
                    unlink(public_path('uploads/attachment_file_kpi_file/'.$note->attachment_file));
                }
                $file->move(public_path('uploads/attachment_file_kpi_file/'),$file_name);   
                $attachmentFile = $file_name;
            }
            
            $note->attachment_file = $attachmentFile ?? $note->attachment_file;
            $note->updated_by = $user->id;
            $note->save();

            DB::commit();

            $this->logService->log(
                'kpi_note.upload_attachment',
                'Upload attachment file for kpi note',
                200,
                [
                    'kpi_id' => $kpi->id,
                    'area_id' => $areaId,
                    'note_id' => $note->id,
                ]
            );

            return JsonResponse::success(
                $note,
                'Attachment file uploaded successfully',
                200
            );

        } catch (\Exception $e) {
            DB::rollBack();

            $this->logService->log(
                'kpi_note.upload_attachment',
                'Failed to upload attachment file for kpi note',
                500,
                [
                    'kpi_id' => $kpi->id,
                    'area_id' => $areaId,
                    'note_id' => $note->id,
                    'error' => $e->getMessage(),
                ]
            );

            return JsonResponse::error(
                $e->getMessage(),
                'Failed to upload attachment file',
                500
            );
        }
    }

    public function sendKpi(Kpi $kpi)
    {
        DB::beginTransaction();

        try {
            if($kpi->send_status == true || (int) $kpi->status === 1){
                DB::rollBack();
                return JsonResponse::error(
                    'KPI sudah dikirim',
                    'Failed to send kpi',
                    400
                );
            }

            $user = auth()->user();
            // Selalu dikirim ke MMRK dulu; MMRK yang meneruskan ke Pusat.
            $kpi->update([
                'status' => 1,
                'mmrk_send_date' => date('Y-m-d'),
                'updated_by' => $user->id,
            ] + ((int) $kpi->rebuttal_state === 1 ? ['rebuttal_state' => 2] : []));

            DB::commit();

            $this->logService->log('kpi.send', 'Send kpi', 200, [
                'kpi_id' => $kpi->id,
                'status' => $kpi->status,
            ]);

            return JsonResponse::success($kpi, 'KPI sent successfully', 200);

        } catch (\Exception $e) {
            DB::rollBack();

            $this->logService->log('kpi.send', 'Failed to send kpi', 500, [
                'kpi_id' => $kpi->id,
                'error' => $e->getMessage(),
            ]);

            return JsonResponse::error($e->getMessage(), 'Failed to send kpi', 500);
        }
    }

    public function sendToPusat(Kpi $kpi)
    {
        if ((int) $kpi->status !== 1) {
            return false;
        }

        $kpi->update([
            'status' => 2,
            'send_status' => true,
            'send_date' => date('Y-m-d'),
            'updated_by' => auth()->id(),
        ] + ((int) $kpi->rebuttal_state === 2 ? ['rebuttal_state' => 3] : []));

        return true;
    }

    public function finishValidation(Kpi $kpi)
    {
        if ((int) $kpi->status !== 2) {
            return false;
        }

        // Selesai validasi sanggahan: cukup tandai selesai sanggah (sanggahan hanya sekali).
        if ((int) $kpi->rebuttal_state === 3) {
            $kpi->update(['status' => 3, 'rebuttal_state' => 4, 'rebuttal_finished_at' => now(), 'updated_by' => auth()->id()]);

            return true;
        }

        // Validasi pertama selesai: mulai masa sanggah 7 hari dan beri tahu Unit/Pusat lewat email.
        $kpi->update(['status' => 3, 'validated_at' => now(), 'updated_by' => auth()->id()]);
        $kpi->notifyValidationFinished('KPI');

        return true;
    }

    public function getCheckedMap(Kpi $kpi)
    {
        return KpiLevelCheck::where('kpi_id', $kpi->id)->pluck('level_id')
            ->mapWithKeys(fn($id) => [$id => true])->all();
    }

    public function toggleCheck(Kpi $kpi, KpiLevel $level, $checked)
    {
        if ((int) $kpi->status !== 2) {
            return [false, 'Data tidak dalam tahap validasi Pusat!'];
        }

        if ($level->kpi_id != $kpi->id) {
            return [false, 'Level tidak valid!'];
        }

        $siblings = KpiLevel::where('sub_area_id', $level->sub_area_id)
            ->orderBy('order')->orderBy('id')->get();

        if ($checked) {
            if (empty($level->attachment_file)) {
                return [false, 'Level belum memiliki file!'];
            }

            foreach ($siblings as $sibling) {
                if ($sibling->id == $level->id) {
                    break;
                }

                if (!KpiLevelCheck::where('level_id', $sibling->id)->exists()) {
                    return [false, 'Centang level sebelumnya terlebih dahulu!'];
                }
            }

            KpiLevelCheck::firstOrCreate(
                ['level_id' => $level->id],
                ['kpi_id' => $kpi->id, 'checked_by' => auth()->id()]
            );

            return [true, 'ok'];
        }

        $after = false;
        foreach ($siblings as $sibling) {
            if ($after || $sibling->id == $level->id) {
                KpiLevelCheck::where('level_id', $sibling->id)->delete();
            }
            if ($sibling->id == $level->id) {
                $after = true;
            }
        }

        return [true, 'ok'];
    }
}
