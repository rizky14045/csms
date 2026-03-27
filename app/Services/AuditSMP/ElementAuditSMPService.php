<?php

namespace App\Services\AuditSMP;

use App\Helpers\JsonResponse;
use App\Models\AuditSMP;
use App\Services\ActivityLog\ActivityLogService;
use Exception;
use Illuminate\Support\Facades\DB;

class ElementAuditSMPService
{
    protected $logService;

    public function __construct(ActivityLogService $logService)
    {
        $this->logService = $logService;
    }

    public function getElementById($auditId,$elementId){
        return AuditSMP::where('id',$elementId)->where('parent_id',$auditId)->first();
    }

    public function createAuditElement(array $data, $auditId)
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
                'Failed to update element smp',
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

            $this->deleteChildren($element->id);

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
                    'error' => $e->getMessage(),
                ]
            );

            return JsonResponse::error(
                $e->getMessage(),
                'Failed to delete audit smp element',
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
