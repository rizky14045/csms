<?php

namespace App\Services\SecurityExternal;

use App\Helpers\JsonResponse;
use App\Models\SecurityExternal;
use App\Services\ActivityLog\ActivityLogService;
use Exception;
use Illuminate\Support\Facades\DB;

class SecurityExternalService
{
    protected $logService;

    public function __construct(ActivityLogService $logService)
    {
        $this->logService = $logService;
    }

    public function createSecurityExternal(array $data)
    {
        DB::beginTransaction();

        try {
            $securityExternal = SecurityExternal::create([
                'user_id' => auth()->id(),
                'name' => $data['name'] ?? "",
                'instansi' => $data['instansi'] ?? "",
                'regional_unit' => $data['regional_unit'] ?? "",
                'warrant_number' => $data['warrant_number'] ?? "",
                'note' => $data['note'] ?? "",
                'created_by' => auth()->id(),
            ]);
            
            DB::commit();

            $this->logService->log(
                'security_external.create',
                'Create security external',
                201,
                [
                    'security_external_id' => $securityExternal->id,
                    'data'   => $securityExternal,
                ]
            );

            return JsonResponse::success(
                $securityExternal,
                'Security external created',
                201
            );
        } catch (Exception $e) {
            DB::rollBack();

            $this->logService->log(
                'security_external.create',
                'Failed to create security external',
                500,
                [
                    'error' => $e->getMessage(),
                    'payload' => [
                        'data' => $data
                    ],
                ]
            );

            return JsonResponse::error(
                $e->getMessage(),
                'Failed to create security external',
                500
            );
        }
    }

    public function updateSecurityExternal(SecurityExternal $security, array $data)
    {
        DB::beginTransaction();

        try {
            $before = $security->toArray();

            $updateData = [
                'name' => $data['name'] ?? "",
                'instansi' => $data['instansi'] ?? "",
                'regional_unit' => $data['regional_unit'] ?? "",
                'warrant_number' => $data['warrant_number'] ?? "",
                'note' => $data['note'] ?? "",
                'updated_by' => auth()->id(),
            ];

            
            $security->update($updateData);

            DB::commit();

            $this->logService->log(
                'security_external.update',
                'Update security external',
                200,
                [
                    'before' => $before,
                    'after'  => $security->toArray(),
                ]
            );

            return JsonResponse::success(
                $security,
                'Security external updated',
                200
            );

        } catch (Exception $e) {
            DB::rollBack();

            $this->logService->log(
                'security_external.update',
                'Failed to update security external',
                500,
                [
                    'error' => $e->getMessage(),
                    'payload' => $data,
                ]
            );

            throw $e;
        }
    }

    public function deleteSecurityExternal(SecurityExternal $security)
    {
        DB::beginTransaction();

        try {
            $security->update([
                'deleted_by' => auth()->id(),
            ]);

            $security->delete();

            DB::commit();

            $this->logService->log(
                'security_external.delete',
                'Delete security external',
                200,
                [
                    'security_external_id' => $security->id,
                ]
            );

            return JsonResponse::success(
                null,
                'Security external deleted successfully',
                200
            );

        } catch (Exception $e) {
            DB::rollBack();

            $this->logService->log(
                'security_external.delete',
                'Failed to delete security external',
                500,
                [
                    'error' => $e->getMessage(),
                    'payload' => [
                        'security_external_id' => $security->id,
                    ],
                ]
            );

            return JsonResponse::error(
                $e->getMessage(),
                'Failed to delete security external',
                500
            );
        }
    }
}
