<?php

namespace App\Services\AgreementExternal;

use App\Helpers\JsonResponse;
use App\Models\AgreementExternal;
use App\Services\ActivityLog\ActivityLogService;
use Exception;
use Illuminate\Support\Facades\DB;

class AgreementExternalService
{
    protected $logService;

    public function __construct(ActivityLogService $logService)
    {
        $this->logService = $logService;
    }

    public function createAgreementExternal(array $data)
    {
        DB::beginTransaction();

        try {
            $agreementExternal = AgreementExternal::create([
                'user_id' => auth()->id(),
                'regional_unit' => $data['regional_unit'] ?? "",
                'instansi' => $data['instansi'] ?? "",
                'name' => $data['name'] ?? "",
                'pkt_number' => $data['pkt_number'] ?? "",
                'pkt_title' => $data['pkt_title'] ?? "",
                'expired_date' => $data['expired_date'] ?? "",
                'note' => $data['note'] ?? "",
                'created_by' => auth()->id(),
            ]);
            
            DB::commit();

            $this->logService->log(
                'agreement_external.create',
                'Create agreement external',
                201,
                [
                    'agreement_external_id' => $agreementExternal->id,
                    'data'   => $agreementExternal,
                ]
            );

            return JsonResponse::success(
                $agreementExternal,
                'Agreement external created',
                201
            );
        } catch (Exception $e) {
            DB::rollBack();

            $this->logService->log(
                'agreement_external.create',
                'Failed to create agreement external',
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
                'Failed to create agreement external',
                500
            );
        }
    }

    public function updateAgreementExternal(AgreementExternal $agreement, array $data)
    {
        DB::beginTransaction();

        try {
            $before = $agreement->toArray();

            $updateData = [
                'regional_unit' => $data['regional_unit'] ?? "",
                'instansi' => $data['instansi'] ?? "",
                'name' => $data['name'] ?? "",
                'pkt_number' => $data['pkt_number'] ?? "",
                'pkt_title' => $data['pkt_title'] ?? "",
                'expired_date' => $data['expired_date'] ?? "",
                'note' => $data['note'] ?? "",
                'updated_by' => auth()->id(),
            ];

            
            $agreement->update($updateData);

            DB::commit();

            $this->logService->log(
                'agreement_external.update',
                'Update agreement external',
                200,
                [
                    'before' => $before,
                    'after'  => $agreement->toArray(),
                ]
            );

            return JsonResponse::success(
                $agreement,
                'Agreement external updated',
                200
            );

        } catch (Exception $e) {
            DB::rollBack();

            $this->logService->log(
                'agreement_external.update',
                'Failed to update agreement external',
                500,
                [
                    'error' => $e->getMessage(),
                    'payload' => $data,
                ]
            );

            throw $e;
        }
    }

    public function deleteAgreementExternal(AgreementExternal $agreement)
    {
        DB::beginTransaction();

        try {
            $agreement->update([
                'deleted_by' => auth()->id(),
            ]);

            $agreement->delete();

            DB::commit();

            $this->logService->log(
                'agreement_external.delete',
                'Delete agreement external',
                200,
                [
                    'agreement_external_id' => $agreement->id,
                ]
            );

            return JsonResponse::success(
                null,
                'Agreement external deleted successfully',
                200
            );

        } catch (Exception $e) {
            DB::rollBack();

            $this->logService->log(
                'agreement_external.delete',
                'Failed to delete agreement external',
                500,
                [
                    'error' => $e->getMessage(),
                    'payload' => [
                        'agreement_external_id' => $agreement->id,
                    ],
                ]
            );

            return JsonResponse::error(
                $e->getMessage(),
                'Failed to delete agreement external',
                500
            );
        }
    }
}
