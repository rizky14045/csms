<?php

namespace App\Services\MainSecurityProgram;

use App\Helpers\JsonResponse;
use App\Models\MainSecurityProgram;
use App\Models\SecurityProgram;
use App\Services\ActivityLog\ActivityLogService;
use Exception;
use Illuminate\Support\Facades\DB;

class MainSecurityProgramService
{
    protected $logService;

    public function __construct(ActivityLogService $logService)
    {
        $this->logService = $logService;
    }

    public function getAllMainSecurityProgram($limit = 10, $paginate = true, $with = [], $user_id = null, $program_id = null)
    {
        try {
            $order  = request('order', 'ASC');
            $search = request('q', '');
            $ref    = request('ref', 'order');
            $start  = request('start', null);
            $end    = request('end', null);
            $query = MainSecurityProgram::query();

            if (!empty($with)) {
                $query->with($with);
            }

            if ($user_id) {
                $query->where('user_id', $user_id);
            }

            if ($program_id) {
                $query->where('program_id', $program_id);
            }

            if ($start && $end) {
                $end = date('Y-m-d', strtotime($end . ' +1 day'));
                $query->whereBetween('created_at', [$start, $end]);
            } elseif ($start) {
                $query->whereDate('created_at', '>=', $start);
            } elseif ($end) {
                $query->whereDate('created_at', '<=', $end);
            }

            $allowedSort = ['id', 'program_name', 'created_at'];
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

            return JsonResponse::success($data, 'Main security program found', 200);

        } catch (Exception $e) {

            $this->logService->log(
                'main_security_program.fetch_all',
                'Failed to fetch main security programs',
                500,
                [
                    'error' => $e->getMessage(),
                    'params' => request()->all(),
                ]
            );

            return JsonResponse::error(
                $e->getMessage(),
                'Main security program not found',
                500
            );
        }
    }

    public function createMainSecurityProgram(array $data, $program_id)
    {
        DB::beginTransaction();

        try {
            $securityProgram = MainSecurityProgram::create([
                'user_id' => auth()->id(),
                'program_id' => $program_id,
                'program_name' => $data['program_name'],
                'start_month' => $data['start_month'],
                'start_week' => $data['start_week'],
                'end_month' => $data['end_month'],
                'end_week' => $data['end_week'],
                'created_by' => auth()->id(),
            ]);
            
            DB::commit();

            $this->logService->log(
                'main_security_program.create',
                'Create main security program',
                201,
                [
                    'main_security_program_id' => $securityProgram->id,
                    'data'   => $securityProgram->toArray(),
                ]
            );

            return JsonResponse::success(
                $securityProgram,
                'Main security program created',
                201
            );
        } catch (Exception $e) {
            DB::rollBack();

            $this->logService->log(
                'main_security_program.create',
                'Failed to create main security program',
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
                'Failed to create security program',
                500
            );
        }
    }

    public function updateMainSecurityProgram(MainSecurityProgram $program, array $data)
    {
        DB::beginTransaction();

        try {
            $before = $program->toArray();

            $updateData = [
                'program_name' => $data['program_name'],
                'start_month' => $data['start_month'],
                'start_week' => $data['start_week'],
                'end_month' => $data['end_month'],
                'end_week' => $data['end_week'],
                'updated_by' => auth()->id(),
            ];

            $program->update($updateData);

            DB::commit();

            $this->logService->log(
                'main_security_program.update',
                'Update main security program',
                200,
                [
                    'before' => $before,
                    'after'  => $program->toArray(),
                ]
            );

            return JsonResponse::success(
                $program,
                'Main security program updated',
                200
            );

        } catch (Exception $e) {
            DB::rollBack();

            $this->logService->log(
                'main_security_program.update',
                'Failed to update main security program',
                500,
                [
                    'error' => $e->getMessage(),
                    'payload' => $data,
                ]
            );

            throw $e;
        }
    }

    public function deleteMainSecurityProgram(MainSecurityProgram $program)
    {
        DB::beginTransaction();

        try {
            $program->update([
                'deleted_by' => auth()->id(),
            ]);

            $program->delete();

            DB::commit();

            $this->logService->log(
                'main_security_program.delete',
                'Delete main security program',
                200,
                [
                    'main_security_program_id' => $program->id,
                ]
            );

            return JsonResponse::success(
                null,
                'Main security program deleted successfully',
                200
            );

        } catch (Exception $e) {
            DB::rollBack();

            $this->logService->log(
                'main_security_program.delete',
                'Failed to delete main security program',
                500,
                [
                    'error' => $e->getMessage(),
                    'payload' => [
                        'main_security_program_id' => $program->id,
                    ],
                ]
            );

            return JsonResponse::error(
                $e->getMessage(),
                'Failed to delete main security program',
                500
            );
        }
    }
}
