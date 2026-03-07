<?php

namespace App\Services\AuditSMP;

use App\Helpers\JsonResponse;
use App\Models\Area;
use App\Models\AuditSMP;
use App\Models\CategoryAssesment;
use App\Services\ActivityLog\ActivityLogService;
use Exception;
use Illuminate\Support\Facades\DB;

class AuditSMPService
{
    protected $logService;

    public function __construct(ActivityLogService $logService)
    {
        $this->logService = $logService;
    }
    
    public function getAuditById($id){
        return AuditSMP::where('id',$id)->first();
    }
    public function getElementById($auditId,$elementId){
        return AuditSMP::where('id',$elementId)->where('parent_id',$auditId)->first();
    }
    public function getAllAudit($limit = 10, $paginate = true, $type = null, $with = [])
    {
        try {
            $order  = request('order', 'ASC');
            $search = request('q', '');
            $ref    = request('ref', 'order');
            $start  = request('start', null);
            $end    = request('end', null);

            $query = AuditSMP::query()->with('kriteria','evident')->where('type','header');

            //

            // if ($type) {
            //     $query->where('type', $type);
            // }

            // if (!empty($search)) {
            //     $query->where(function ($q) use ($search) {
            //         $q->where('name', 'like', "%{$search}%");
            //     });
            // }

            // if ($start && $end) {
            //     $end = date('Y-m-d', strtotime($end . ' +1 day'));
            //     $query->whereBetween('created_at', [$start, $end]);
            // } elseif ($start) {
            //     $query->whereDate('created_at', '>=', $start);
            // } elseif ($end) {
            //     $query->whereDate('created_at', '<=', $end);
            // }

            // $allowedSort = ['id', 'name', 'created_at'];
            // if (!in_array($ref, $allowedSort)) {
            //     $ref = 'id';
            // }

            // $query->orderBy($ref, $order);

            // if ($paginate) {
            //     $data = $query->paginate($limit)->withQueryString();
            // } else {
                $data = $limit > 0
                    ? $query->limit($limit)->get()
                    : $query->get();
            // }

            return JsonResponse::success($data, 'Audit SMP found', 200);

        } catch (Exception $e) {

            $this->logService->log(
                'auditSMP.fetch_all',
                'Failed to fetch auditSMP',
                500,
                [
                    'error' => $e->getMessage(),
                    'params' => request()->all(),
                ]
            );

            return JsonResponse::error(
                $e->getMessage(),
                'auditSMP not found',
                500
            );
        }
    }

    public function createAudit(array $data)
    {
        DB::beginTransaction();

        try {

            $audit = AuditSMP::create([
                'name' => $data['name'],
                'bobot' => $data['bobot'],
                'type' => 'header',
                'created_by' => auth()->id(),
            ]);
            
            DB::commit();

            $this->logService->log(
                'audit-smp.create',
                'Create audit smp',
                201,
                [
                    'audit_id' => $audit->id,
                    'name'   => $audit->name,
                    'bobot'    => $audit->bobot,
                ]
            );

            return JsonResponse::success(
                $audit,
                'Audit SMP created',
                201
            );
        } catch (Exception $e) {
            DB::rollBack();

            $this->logService->log(
                'audit-smp.create',
                'Failed to create audit smp',
                500,
                [
                    'error' => $e->getMessage(),
                    'payload' => [
                        'name' => $data['name'],
                        'bobot' => $data['bobot'],
                    ],
                ]
            );

            return JsonResponse::error(
                $e->getMessage(),
                'Failed to create audit smp',
                500
            );
        }
    }

    public function updateAudit(AuditSMP $audit, array $data)
    {
        DB::beginTransaction();

        try {
            $before = $audit->toArray();

            $updateData = [
                'name'       => $data['name'],
                'bobot'       => $data['bobot'],
                'updated_by' => auth()->id(),
            ];

            $audit->update($updateData);

            DB::commit();

            $this->logService->log(
                'audit-smp.update',
                'Update audit smp',
                200,
                [
                    'before' => $before,
                    'after'  => $audit->fresh()->toArray(),
                ]
            );

            return JsonResponse::success(
                $audit,
                'Audit SMP updated',
                201
            );

        } catch (Exception $e) {
            DB::rollBack();

            $this->logService->log(
                'audit-smp.update',
                'Failed to update area',
                500,
                [
                    'error' => $e->getMessage(),
                    'payload' => $data,
                ]
            );

            throw $e;
        }
    }

    public function deleteAudit(AuditSMP $audit)
    {
        DB::beginTransaction();

        try {
            // $area->load('subAreas.levels.notes');

            $logData = [
                'area_id'   => $audit->id,
                'audit_name' => $audit->name,
                // 'sub_areas' => [],
                // 'total_sub_areas' => 0,
                // 'total_levels'    => 0,
                // 'total_notes'     => 0,
            ];

            $audit->update([
                'deleted_by' => auth()->id(),
            ]);

            // foreach ($area->subAreas as $subArea) {

            //     $subArea->update([
            //         'deleted_by' => auth()->id(),
            //     ]);

            //     $levelData = [];

            //     foreach ($subArea->levels as $level) {

            //         $level->update([
            //             'deleted_by' => auth()->id(),
            //         ]);

            //         $noteData = [];

            //         foreach ($level->notes as $note) {

            //             $note->update([
            //                 'deleted_by' => auth()->id(),
            //             ]);

            //             $noteData[] = [
            //                 'note_id'   => $note->id,
            //                 'note_text' => $note->note ?? null,
            //             ];

            //             $note->delete();
            //             $logData['total_notes']++;
            //         }

            //         $levelData[] = [
            //             'level_id' => $level->id,
            //             'level_name' => $level->name ?? null,
            //             'notes' => $noteData
            //         ];

            //         $level->delete();
            //         $logData['total_levels']++;
            //     }

            //     $logData['sub_areas'][] = [
            //         'sub_area_id' => $subArea->id,
            //         'sub_area_name' => $subArea->name ?? null,
            //         'levels' => $levelData
            //     ];

            //     $subArea->delete();
            //     $logData['total_sub_areas']++;
            // }

            // $remainingAreas = Area::where('type', $area->type)
            //     ->orderBy('order', 'asc')
            //     ->get();

            $audit->delete();

            // $newOrder = 1;

            // foreach ($remainingAreas as $item) {
            //     $item->update([
            //         'order' => $newOrder++
            //     ]);
            // }

            DB::commit();

            $this->logService->log(
                'audit-smp.delete',
                'Delete audit smp',
                200,
                $logData
            );

            return JsonResponse::success(
                null,
                'Audit SMP deleted successfully',
                200
            );

        } catch (Exception $e) {

            DB::rollBack();

            $this->logService->log(
                'audit-smp.delete',
                'Failed to delete audit smp',
                500,
                [
                    'audit_id' => $audit->id ?? null,
                    'error'   => $e->getMessage(),
                ]
            );

            return JsonResponse::error(
                $e->getMessage(),
                'Failed to delete audit smp',
                500
            );
        }
    }
    public function createAuditElement(array $data,$auditId)
    {
        DB::beginTransaction();

        try {

            $audit = AuditSMP::create([
                'name' => $data['name'],
                'type' => $data['type'],
                'parent_id' => $auditId,
                'created_by' => auth()->id(),
            ]);
            
            DB::commit();

            $this->logService->log(
                'audit-smp.createElement',
                'Create audit smp element',
                201,
                [
                    'audit_id' => $audit->id,
                    'name'   => $audit->name,
                    'bobot'    => $audit->bobot,
                    'parent_id'    => $audit->parent_id,
                ]
            );

            return JsonResponse::success(
                $audit,
                'Audit SMP created element',
                201
            );
        } catch (Exception $e) {
            DB::rollBack();

            $this->logService->log(
                'audit-smp.createElement',
                'Failed to create audit smp element',
                500,
                [
                    'error' => $e->getMessage(),
                    'payload' => [
                        'name' => $data['name'],
                        'bobot' => $data['bobot'],
                    ],
                ]
            );

            return JsonResponse::error(
                $e->getMessage(),
                'Failed to create audit smp',
                500
            );
        }
    }
     public function updateAuditElement($element, array $data)
    {
        DB::beginTransaction();

        try {
            $before = $element->toArray();

            $updateData = [
                'name'       => $data['name'],
                'type'       => $data['type'],
                'updated_by' => auth()->id(),
            ];

            $element->update($updateData);

            DB::commit();

            $this->logService->log(
                'element-smp.update',
                'Update element smp',
                200,
                [
                    'before' => $before,
                    'after'  => $element->fresh()->toArray(),
                ]
            );

            return JsonResponse::success(
                $element,
                'Audit SMP updated',
                201
            );

        } catch (Exception $e) {
            DB::rollBack();

            $this->logService->log(
                'element-smp.update',
                'Failed to update area',
                500,
                [
                    'error' => $e->getMessage(),
                    'payload' => $data,
                ]
            );

            throw $e;
        }
    }
    public function deleteElement(AuditSMP $element)
    {
        DB::beginTransaction();

        try {

            $logData = [
                'element_id'   => $element->id,
                'element_name' => $element->name,
            ];

            $element->update([
                'deleted_by' => auth()->id(),
            ]);

            $element->delete();

            DB::commit();

            $this->logService->log(
                'audit-smp.delete-element',
                'Delete audit smp element',
                200,
                $logData
            );

            return JsonResponse::success(
                null,
                'Audit SMP element deleted successfully',
                200
            );

        } catch (Exception $e) {

            DB::rollBack();

            $this->logService->log(
                'audit-smp.delete-element',
                'Failed to delete audit smp element',
                500,
                [
                    'audit_id' => $element->id ?? null,
                    'error'   => $e->getMessage(),
                ]
            );

            return JsonResponse::error(
                $e->getMessage(),
                'Failed to delete audit smp element',
                500
            );
        }
    }
}
