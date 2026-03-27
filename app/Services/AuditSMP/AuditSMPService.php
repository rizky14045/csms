<?php

namespace App\Services\AuditSMP;

use App\Helpers\JsonResponse;
use App\Models\AuditSMP;
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
    

    public function getAllAudit($limit = 10, $paginate = true, $type = null, $with = [])
    {
        try {

            $order  = request('order', 'ASC');
            $search = request('q', '');
            $ref    = request('ref', 'id');
            $start  = request('start');
            $end    = request('end');

            $allowedSort = ['id', 'name', 'created_at'];

            if (!in_array($ref, $allowedSort)) {
                $ref = 'id';
            }

            $query = AuditSMP::query();

            if (empty($with)) {
                $with = [
                    'pernyataan.kriteria.evidence',
                    'kriteria.evidence'
                ];
            }

            $query->with($with);

            if ($type !== null) {
                $query->where('type', $type);
            }

            if (!empty($search)) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
            }

            if ($start && $end) {

                $end = date('Y-m-d', strtotime($end . ' +1 day'));

                $query->whereBetween('created_at', [$start, $end]);

            } elseif ($start) {

                $query->whereDate('created_at', '>=', $start);

            } elseif ($end) {

                $query->whereDate('created_at', '<=', $end);
            }

            $query->orderBy($ref, $order);

            if ($paginate) {

                $data = $query
                    ->paginate($limit)
                    ->withQueryString();

            } else {

                $data = $limit > 0
                    ? $query->limit($limit)->get()
                    : $query->get();
            }

            return JsonResponse::success($data, 'Audit SMP found', 200);

        } catch (Exception $e) {

            $this->logService->log(
                'auditSMP.fetch_all',
                'Failed to fetch audit SMP',
                500,
                [
                    'error' => $e->getMessage(),
                    'params' => request()->all(),
                ]
            );

            return JsonResponse::error(
                $e->getMessage(),
                'Audit SMP not found',
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
                'type' => $data['type'],
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

            $logData = [
                'audit_id'   => $audit->id,
                'audit_name' => $audit->name,
            ];

            $this->deleteChildren($audit->id);

            $audit->update([
                'deleted_by' => auth()->id(),
            ]);

            $audit->delete();

            DB::commit();

            $this->logService->log(
                'audit-smp.delete-audit',
                'Delete audit smp audit',
                200,
                $logData
            );

            return JsonResponse::success(
                null,
                'Audit SMP audit deleted successfully',
                200
            );

        } catch (Exception $e) {

            DB::rollBack();

            $this->logService->log(
                'audit-smp.delete-audit',
                'Failed to delete audit smp audit',
                500,
                [
                    'audit_id' => $audit->id ?? null,
                    'error' => $e->getMessage(),
                ]
            );

            return JsonResponse::error(
                $e->getMessage(),
                'Failed to delete audit smp audit',
                500
            );
        }
    }

    private function deleteChildren($parentId)
    {
        $children = AuditSMP::where('parent_id', $parentId)->get();

        foreach ($children as $child) {

            $this->deleteChildren($child->id);

            $child->update([
                'deleted_by' => auth()->id(),
            ]);

            $child->delete();
        }
    }
}
