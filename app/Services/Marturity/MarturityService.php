<?php

namespace App\Services\Marturity;

use App\Helpers\JsonResponse;
use App\Models\Area;
use App\Models\Level;
use App\Models\Marturity;
use App\Models\MarturityArea;
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
    protected $logService;

    public function __construct(ActivityLogService $logService)
    {
        $this->logService = $logService;
    }
    
   public function getAlMarturity($limit = 10, $paginate = true, $with = [], $unit_id = null, $send_status = null)
    {
        try {
            $order  = request('order', 'ASC');
            $search = request('q', '');
            $ref    = request('ref', 'order');
            $start  = request('start', null);
            $end    = request('end', null);
            $date  = request('date', null);

            $query = Marturity::query();

            if (!empty($with)) {
                $query->with($with);
            }

            if ($unit_id) {
                $query->where('unit_id', $unit_id);
            }

            if ($send_status !== null) {
                $query->where('send_status', $send_status);
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
        DB::beginTransaction();

        try {
            $userId = auth()->id();

            $marturity = Marturity::create([
                'unit_id'   => $userId,
                'date'      => $data['date'],
                'triwulan'  => $data['triwulan'],
                'created_by'=> $userId,
            ]);

            $areas = Area::where('type', 'marturity')->get();

            foreach ($areas as $area) {

                $marturityArea = MarturityArea::create([
                    'unit_id'       => $userId,
                    'marturity_id'  => $marturity->id,
                    'name'          => $area->name,
                    'created_by'    => $userId,
                ]);

                $subAreas = SubArea::where('area_id', $area->id)->get();

                foreach ($subAreas as $subArea) {

                    $marturitySubArea = MarturitySubArea::create([
                        'unit_id'       => $userId,
                        'marturity_id'  => $marturity->id,
                        'area_id'       => $marturityArea->id,
                        'name'          => $subArea->name,
                        'description'   => $subArea->description,
                        'reference'     => $subArea->reference,
                        'created_by'    => $userId,
                    ]);

                    $levels = Level::where('sub_area_id', $subArea->id)->get();

                    foreach ($levels as $level) {

                        $marturityLevel = MarturityLevel::create([
                            'unit_id'       => $userId,
                            'marturity_id'  => $marturity->id,
                            'sub_area_id'   => $marturitySubArea->id,
                            'level'         => $level->level,
                            'description'   => $level->description,
                            'created_by'    => $userId,
                        ]);

                        $notes = Note::where('level_id', $level->id)->get();

                        foreach ($notes as $note) {

                            MarturityNote::create([
                                'unit_id'       => $userId,
                                'marturity_id'  => $marturity->id,
                                'level_id'      => $marturityLevel->id,
                                'note'          => $note->note,
                                'created_by'    => $userId,
                            ]);
                        }
                    }
                }
            }

            DB::commit();

            $this->logService->log(
                'marturity.create',
                'Create marturity with areas, subareas, levels and notes',
                200,
                [
                    'marturity_id' => $marturity->id,
                    'date' => $marturity->date,
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
                'date'       => $data['date'],
                'triwulan'   => $data['triwulan'],
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

            $note = MarturityNote::where('unit_id', $user->id)
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

            return JsonResponse::error(
                $e->getMessage(),
                'Failed to upload attachment file',
                500
            );
        }
    }

    public function sendMarturity(Marturity $marturity)
    {
        DB::beginTransaction();

        try {
            if($marturity->send_status == true){
                return JsonResponse::error(
                    'Marturity sudah dikirim',
                    'Failed to send marturity',
                    400
                );
            }
            
            $marturity->update([
                'send_status' => true,
                'send_date' => date('Y-m-d'),
                'updated_by' => auth()->id(),
            ]);

            DB::commit();

            $this->logService->log(
                'marturity.send',
                'Send marturity',
                200,
                [
                    'marturity_id' => $marturity->id,
                    'date' => $marturity->date,
                    'triwulan' => $marturity->triwulan,
                ]
            );

            return JsonResponse::success(
                $marturity,
                'Marturity sent successfully',
                200
            );

        } catch (\Exception $e) {
            DB::rollBack();

            $this->logService->log(
                'marturity.send',
                'Failed to send marturity',
                500,
                [
                    'marturity_id' => $marturity->id,
                    'error' => $e->getMessage(),
                ]
            );

            return JsonResponse::error(
                $e->getMessage(),
                'Failed to send marturity',
                500
            );
        }
    }
}
