<?php

namespace App\Services\Security;

use App\Helpers\JsonResponse;
use App\Models\Security;
use App\Services\ActivityLog\ActivityLogService;
use Exception;
use Illuminate\Support\Facades\DB;

class SecurityService
{
    protected $logService;

    public function __construct(ActivityLogService $logService)
    {
        $this->logService = $logService;
    }
    
   public function getAllSecurity($limit = 10, $paginate = true, $user_id = null)
    {
        try {
            $order  = request('order', 'DESC');
            $search = request('q', '');
            $ref    = request('ref', 'id');
            $start  = request('start', null);
            $end    = request('end', null);

            $query = Security::query();

            if (!empty($search)) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
            }

            if($user_id){
                $query->where('user_id', $user_id);
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

            return JsonResponse::success($data, 'Security found', 200);

        } catch (Exception $e) {

            $this->logService->log(
                'security.fetch_all',
                'Failed to fetch securities',
                500,
                [
                    'error' => $e->getMessage(),
                    'params' => request()->all(),
                ]
            );

            return JsonResponse::error(
                $e->getMessage(),
                'securities not found',
                500
            );
        }
    }

    public function getSecurityById(int $id, $user_id = null)
    {
        try {

            $query = Security::where('id', $id);

            if (!empty($user_id)) {
                $query->where('user_id', $user_id);
            }

            $security = $query->first();

            if (!$security) {

                $this->logService->log(
                    'security.fetch_one',
                    'Security not found',
                    404,
                    [
                        'id' => $id,
                        'user_id' => $user_id,
                    ]
                );

                return JsonResponse::error(
                    'Security not found',
                    'Security not found',
                    404
                );
            }

            return JsonResponse::success(
                $security,
                'Security found',
                200
            );

        } catch (Exception $e) {

            $this->logService->log(
                'security.fetch_one',
                'Failed to fetch security',
                500,
                [
                    'id' => $id,
                    'user_id' => $user_id,
                    'error' => $e->getMessage(),
                ]
            );

            return JsonResponse::error(
                $e->getMessage(),
                'Failed to fetch security',
                500
            );
        }
    }

    public function createSecurity(array $data)
    {
        DB::beginTransaction();

        try {
            $user_id = null;
            if(isset($data['user_id'])){
                $user_id = $data['user_id'];
            }

            $security = Security::create([
                'user_id' => $user_id,
                'name' => $data['name'] ?? null,
                'gender' => $data['gender'] ?? null,
                'unit_work' => $data['unit_work'] ?? null,
                'nid' => $data['nid'] ?? null,
                'registration_number' => $data['registration_number'] ?? null,
                'expired_card_date' => $data['expired_card_date'] ?? null,
                'position' => $data['position'] ?? null,
                'birth_place' => $data['birth_place'] ?? null,
                'birth_date' => $data['birth_date'] ?? null,
                'qualification' => $data['qualification'] ?? null,
                'last_education' => $data['last_education'] ?? null,
                'note' => $data['note'] ?? null,
                'created_by' => auth()->user()->id,
            ]);

            DB::commit();

            $this->logService->log(
                'security.create',
                'Create security',
                201,
                [
                    'security_id' => $security->id,
                    'name' => $security->name,
                    'gender' => $security->gender,
                    'unit_work' => $security->unit_work,
                    'nid' => $security->nid,
                    'registration_number' => $security->registration_number,
                    'expired_card_date' => $security->expired_card_date,
                    'position' => $security->position,
                    'birth_place' => $security->birth_place,
                    'birth_date' => $security->birth_date,
                    'qualification' => $security->qualification,
                    'last_education' => $security->last_education,
                    'note' => $security->note,
                ]
            );

            return JsonResponse::success(
                $security,
                'Security created',
                201
            );
        } catch (Exception $e) {
            DB::rollBack();

            $this->logService->log(
                'security.create',
                'Failed to create security',
                500,
                [
                    'error' => $e->getMessage(),
                    'payload' => [
                        'name' => $data['name'] ?? null,
                        'gender' => $data['gender'] ?? null,
                        'unit_work' => $data['unit_work'] ?? null,
                        'nid' => $data['nid'] ?? null,
                        'registration_number' => $data['registration_number'] ?? null,
                        'expired_card_date' => $data['expired_card_date'] ?? null,
                        'position' => $data['position'] ?? null,
                        'birth_place' => $data['birth_place'] ?? null,
                        'birth_date' => $data['birth_date'] ?? null,
                        'qualification' => $data['qualification'] ?? null,
                        'last_education' => $data['last_education'] ?? null,
                        'note' => $data['note'] ?? null,
                    ],
                ]
            );

            return JsonResponse::error(
                $e->getMessage(),
                'Failed to create security',
                500
            );
        }
    }

    public function updateSecurity(Security $security, array $data)
    {
        DB::beginTransaction();

        try {
            $before = $security->toArray();

            $updateData = [
                'name' => $data['name'] ?? null,
                'gender' => $data['gender'] ?? null,
                'unit_work' => $data['unit_work'] ?? null,
                'nid' => $data['nid'] ?? null,
                'registration_number' => $data['registration_number'] ?? null,
                'expired_card_date' => $data['expired_card_date'] ?? null,
                'position' => $data['position'] ?? null,
                'birth_place' => $data['birth_place'] ?? null,
                'birth_date' => $data['birth_date'] ?? null,
                'qualification' => $data['qualification'] ?? null,
                'last_education' => $data['last_education'] ?? null,
                'note' => $data['note'] ?? null,
                'updated_by' => auth()->id(),
            ];

            $security->update($updateData);

            DB::commit();

            $this->logService->log(
                'security.update',
                'Update security',
                200,
                [
                    'before' => $before,
                    'after'  => $security->toArray(),
                ]
            );

            return JsonResponse::success(
                $security,
                'Security updated',
                201
            );

        } catch (Exception $e) {
            DB::rollBack();

            $this->logService->log(
                'security.update',
                'Failed to update security',
                500,
                [
                    'error' => $e->getMessage(),
                    'payload' => $data,
                ]
            );

            throw $e;
        }
    }

    public function deleteSecurity(Security $security)
    {
        DB::beginTransaction();

        try {
            $security->update([
                'deleted_by' => auth()->id(),
            ]);

            $security->delete();

            DB::commit();

            $this->logService->log(
                'security.delete',
                'Delete security',
                201,
                [
                    'security_id' => $security->id,
                    'name' => $security->name,
                    'gender' => $security->gender,
                    'unit_work' => $security->unit_work,
                    'nid' => $security->nid,
                    'registration_number' => $security->registration_number,
                    'expired_card_date' => $security->expired_card_date,
                    'position' => $security->position,
                    'birth_place' => $security->birth_place,
                    'birth_date' => $security->birth_date,
                    'qualification' => $security->qualification,
                    'last_education' => $security->last_education,
                    'note' => $security->note,
                ]
            );

            return JsonResponse::success(
                null,
                'Security deleted',
                201
            );

        } catch (Exception $e) {
            DB::rollBack();

            $this->logService->log(
                'security.delete',
                'Failed to delete security',
                500,
                [
                    'security_id' => $security->id ?? null,
                    'error'   => $e->getMessage(),
                ]
            );

            return JsonResponse::error(
                $e->getMessage(),
                'Failed to delete security',
                500
            );
        }
    }
}
