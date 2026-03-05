<?php

namespace App\Services\ResponsiblePerson;

use App\Helpers\JsonResponse;
use App\Models\Area;
use App\Models\ResponsiblePerson;
use App\Services\ActivityLog\ActivityLogService;
use Exception;
use Illuminate\Support\Facades\DB;

class ResponsiblePersonService
{
    protected $logService;

    public function __construct(ActivityLogService $logService)
    {
        $this->logService = $logService;
    }

    public function createResponsiblePerson(array $data)
    {
        DB::beginTransaction();

        try {
            $responsiblePerson = ResponsiblePerson::create([
                'user_id' => auth()->id(),
                'name' => $data['name'] ?? "",
                'position' => $data['position'] ?? "",
                'work_unit' => $data['work_unit'] ?? "",
                'main' => $data['main'] ?? "",
                'training_smp' => $data['training_smp'] ?? "",
                'auditor_smp' => $data['auditor_smp'] ?? "",
                'investigation' => $data['investigation'] ?? "",
                'mansrisk' => $data['mansrisk'] ?? "",
                'stackholder_management' => $data['stackholder_management'] ?? "",
                'last_education' => $data['last_education'] ?? "",
                'note' => $data['note'] ?? "",
                'created_by' => auth()->id(),
            ]);
            
            DB::commit();

            $this->logService->log(
                'responsible_person.create',
                'Create responsible person',
                201,
                [
                    'responsible_person_id' => $responsiblePerson->id,
                    'data'   => $responsiblePerson,
                ]
            );

            return JsonResponse::success(
                $responsiblePerson,
                'Responsible person created',
                201
            );
        } catch (Exception $e) {
            DB::rollBack();

            $this->logService->log(
                'responsible_person.create',
                'Failed to create responsible person',
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
                'Failed to create responsible person',
                500
            );
        }
    }

    public function updateResponsiblePerson(ResponsiblePerson $person, array $data)
    {
        DB::beginTransaction();

        try {
            $before = $person->toArray();

            $updateData = [
                'name' => $data['name'] ?? "",
                'position' => $data['position'] ?? "",
                'work_unit' => $data['work_unit'] ?? "",
                'main' => $data['main'] ?? "",
                'training_smp' => $data['training_smp'] ?? "",
                'auditor_smp' => $data['auditor_smp'] ?? "",
                'investigation' => $data['investigation'] ?? "",
                'mansrisk' => $data['mansrisk'] ?? "",
                'stackholder_management' => $data['stackholder_management'] ?? "",
                'last_education' => $data['last_education'] ?? "",
                'note' => $data['note'] ?? "",
                'updated_by' => auth()->id(),
            ];

            $person->update($updateData);

            DB::commit();

            $this->logService->log(
                'responsible_person.update',
                'Update responsible person',
                200,
                [
                    'before' => $before,
                    'after'  => $person->toArray(),
                ]
            );

            return JsonResponse::success(
                $person,
                'Responsible person updated',
                200
            );

        } catch (Exception $e) {
            DB::rollBack();

            $this->logService->log(
                'responsible_person.update',
                'Failed to update responsible person',
                500,
                [
                    'error' => $e->getMessage(),
                    'payload' => $data,
                ]
            );

            throw $e;
        }
    }

    public function deleteResponsiblePerson(ResponsiblePerson $person)
    {
        DB::beginTransaction();

        try {
            $person->update([
                'deleted_by' => auth()->id(),
            ]);

            $person->delete();

            DB::commit();

            $this->logService->log(
                'responsible_person.delete',
                'Delete responsible person',
                200,
                [
                    'responsible_person_id' => $person->id,
                ]
            );

            return JsonResponse::success(
                null,
                'Responsible person deleted successfully',
                200
            );

        } catch (Exception $e) {
            DB::rollBack();

            $this->logService->log(
                'responsible_person.delete',
                'Failed to delete responsible person',
                500,
                [
                    'error' => $e->getMessage(),
                    'payload' => [
                        'responsible_person_id' => $person->id,
                    ],
                ]
            );

            return JsonResponse::error(
                $e->getMessage(),
                'Failed to delete responsible person',
                500
            );
        }
    }
}
