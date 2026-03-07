<?php

namespace App\Services\SecurityProgram;

use App\Helpers\JsonResponse;
use App\Models\SecurityProgram;
use App\Services\ActivityLog\ActivityLogService;
use Exception;
use Illuminate\Support\Facades\DB;

class SecurityProgramService
{
    protected $logService;

    public function __construct(ActivityLogService $logService)
    {
        $this->logService = $logService;
    }

    public function getAllSecurityProgram($limit = 10, $paginate = true, $with = [], $user_id = null, $send_status = null)
    {
        try {
            $order  = request('order', 'ASC');
            $search = request('q', '');
            $ref    = request('ref', 'order');
            $start  = request('start', null);
            $end    = request('end', null);
            $query = SecurityProgram::query();

            if (!empty($with)) {
                $query->with($with);
            }

            if ($user_id) {
                $query->where('user_id', $user_id);
            }

            if ($send_status !== null) {
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

            return JsonResponse::success($data, 'Security program found', 200);

        } catch (Exception $e) {

            $this->logService->log(
                'security_program.fetch_all',
                'Failed to fetch security programs',
                500,
                [
                    'error' => $e->getMessage(),
                    'params' => request()->all(),
                ]
            );

            return JsonResponse::error(
                $e->getMessage(),
                'Security program not found',
                500
            );
        }
    }

    public function createSecurityProgram(array $data)
    {
        DB::beginTransaction();

        try {
            $securityProgram = SecurityProgram::create([
                'user_id' => auth()->id(),
                'program_name' => $data['program_name'],
                'description' => $data['description'],
                'year' => $data['year'],
                'created_by' => auth()->id(),
            ]);
            
            DB::commit();

            $this->logService->log(
                'security_program.create',
                'Create security program',
                201,
                [
                    'security_program_id' => $securityProgram->id,
                    'data'   => $securityProgram->toArray(),
                ]
            );

            return JsonResponse::success(
                $securityProgram,
                'Security program created',
                201
            );
        } catch (Exception $e) {
            DB::rollBack();

            $this->logService->log(
                'security_program.create',
                'Failed to create security program',
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

    public function updateSecurityProgram(SecurityProgram $program, array $data)
    {
        DB::beginTransaction();

        try {
            $before = $program->toArray();

            $updateData = [
                'program_name' => $data['program_name'] ?? "",
                'description' => $data['description'] ?? "",
                'year' => $data['year'] ?? "",
                'updated_by' => auth()->id(),
            ];

            $program->update($updateData);

            DB::commit();

            $this->logService->log(
                'security_program.update',
                'Update security program',
                200,
                [
                    'before' => $before,
                    'after'  => $program->toArray(),
                ]
            );

            return JsonResponse::success(
                $program,
                'Security program updated',
                200
            );

        } catch (Exception $e) {
            DB::rollBack();

            $this->logService->log(
                'security_program.update',
                'Failed to update security program',
                500,
                [
                    'error' => $e->getMessage(),
                    'payload' => $data,
                ]
            );

            throw $e;
        }
    }

    public function deleteSecurityProgram(SecurityProgram $program)
    {
        DB::beginTransaction();

        try {
            $program->update([
                'deleted_by' => auth()->id(),
            ]);

            $program->delete();

            DB::commit();

            $this->logService->log(
                'security_program.delete',
                'Delete security program',
                200,
                [
                    'security_program_id' => $program->id,
                ]
            );

            return JsonResponse::success(
                null,
                'Security program deleted successfully',
                200
            );

        } catch (Exception $e) {
            DB::rollBack();

            $this->logService->log(
                'security_program.delete',
                'Failed to delete security program',
                500,
                [
                    'error' => $e->getMessage(),
                    'payload' => [
                        'security_program_id' => $program->id,
                    ],
                ]
            );

            return JsonResponse::error(
                $e->getMessage(),
                'Failed to delete security program',
                500
            );
        }
    }
}
