<?php

namespace App\Services\Kpi;

use App\Helpers\JsonResponse;
use App\Models\Area;
use App\Models\CategoryAssesment;
use App\Models\Kpi;
use App\Models\KpiArea;
use App\Models\KpiLevel;
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
    protected $logService;

    public function __construct(ActivityLogService $logService)
    {
        $this->logService = $logService;
    }
    
   public function getAllKpi($limit = 10, $paginate = true, $unit_id = null, $with = [], $send_status = null)
    {
        try {
            $order  = request('order', 'ASC');
            $search = request('q', '');
            $ref    = request('ref', 'order');
            $start  = request('start', null);
            $end    = request('end', null);
            $date  = request('date', null);

            $query = Kpi::query();

            if (!empty($with)) {
                $query->with($with);
            }

            if ($unit_id) {
                $query->where('unit_id', $unit_id);
            }

            if (!empty($search)) {
                $query->where(function ($q) use ($search) {
                    $q->where('send_status', 'like', "%{$search}%");
                });
            }

            if($date){
                $query->where('date', $date);
            }

            if($send_status !== null){
                $query->where('send_status', $send_status);
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
        DB::beginTransaction();

        try {
            $user = Auth::guard('web')->user();
            
            $kpi = Kpi::create([
                'unit_id' => $user->id,
                'date' => $data['date'],
                'triwulan' => $data['triwulan'],
                'created_by' => $user->id,
            ]);
            
            $areas = Area::where('type','kpi')->get();

            foreach ($areas as $area) {

                $kpiArea = KpiArea::create([
                    'unit_id' => $user->id,
                    'kpi_id' => $kpi->id,
                    'name' => $area->name,
                    'created_by' => $user->id,
                ]);
                $subAreas = SubArea::where('area_id', $area->id)->get();
                foreach ($subAreas as $subArea) {

                    $kpiSubArea = KpiSubArea::create([
                        'unit_id' => $user->id,
                        'kpi_id' => $kpi->id,
                        'area_id' => $kpiArea->id,
                        'name' => $subArea->name,
                        'description' => $subArea->description,
                        'reference' => $subArea->reference,   
                        'created_by' => $user->id, 
                    ]);

                    $levels = Level::where('sub_area_id', $subArea->id)->get();
                    foreach ($levels as $level){

                        $kpiLevel = KpiLevel::create([
                            'unit_id' => $user->id,
                            'kpi_id' => $kpi->id,
                            'sub_area_id' => $kpiSubArea->id,
                            'level' => $level->level,
                            'description' => $level->description,  
                            'created_by' => $user->id,     
                        ]);

                        $notes = Note::where('level_id', $level->id)->get();

                        foreach ($notes as $note){
                            $kpiNote = KpiNote::create([
                                'unit_id' => $user->id,
                                'kpi_id' => $kpi->id,
                                'level_id' => $kpiLevel->id,
                                'note' => $note->note,
                                'created_by' => $user->id,
                            ]);
                        }
                    }
                }
            }
            
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
                        'date' => $data['date'],
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
                'date' => $data['date'],
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

    public function sendKpi(Kpi $kpi)
    {
        DB::beginTransaction();

        try {
            if($kpi->send_status == true){
                return JsonResponse::error(
                    'KPI sudah dikirim',
                    'Failed to send kpi',
                    400
                );
            }
            
            $kpi->update([
                'send_status' => true,
                'send_date' => date('Y-m-d'),
                'updated_by' => auth()->id(),
            ]);

            DB::commit();

            $this->logService->log(
                'kpi.send',
                'Send kpi',
                200,
                [
                    'kpi_id' => $kpi->id,
                    'date' => $kpi->date,
                    'triwulan' => $kpi->triwulan,
                ]
            );

            return JsonResponse::success(
                $kpi,
                'KPI sent successfully',
                200
            );

        } catch (\Exception $e) {
            DB::rollBack();

            $this->logService->log(
                'kpi.send',
                'Failed to send kpi',
                500,
                [
                    'kpi_id' => $kpi->id,
                    'error' => $e->getMessage(),
                ]
            );

            return JsonResponse::error(
                $e->getMessage(),
                'Failed to send kpi',
                500
            );
        }
    }
}
