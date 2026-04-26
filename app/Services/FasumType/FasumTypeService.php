<?php

namespace App\Services\FasumType;

use App\Helpers\JsonResponse;
use App\Models\Fasum;
use App\Models\FasumType;
use App\Services\ActivityLog\ActivityLogService;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FasumTypeService
{
    protected $logService;

    public function __construct(ActivityLogService $logService)
    {
        $this->logService = $logService;
    }
    
   public function getAllFasumTypes($limit = 10, $paginate = true)
    {
        try {
            $order  = request('order', 'ASC');
            $search = request('q', '');
            $ref    = request('ref', 'order');
            $start  = request('start', null);
            $end    = request('end', null);

            $query = FasumType::query();

            if (!empty($with)) {
                $query->with($with);
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

            return JsonResponse::success($data, '    found', 200);

        } catch (Exception $e) {

            $this->logService->log(
                'fasum_type.fetch_all',
                'Failed to fetch fasum types',
                500,
                [
                    'error' => $e->getMessage(),
                    'params' => request()->all(),
                ]
            );

            return JsonResponse::error(
                $e->getMessage(),
                'Fasum not found',
                500
            );
        }
    }

    public function createFasumType(array $data)
    {
        
        try {
            DB::beginTransaction();
            $fasumType = FasumType::create([
                'name' => $data['name'],
                'color_code' => $data['color_code'],
                'created_by' => auth()->id(),
            ]);
            
            DB::commit();

            $this->logService->log(
                'fasum_type.create',
                'Create fasum type',
                201,
                [
                    'fasum_type_id' => $fasumType->id,
                    'user_id'   => $fasumType->created_by,
                    'name'   => $fasumType->name,
                    'color_code' => $fasumType->color_code,
                    'created_by' => auth()->id(),
                ]
            );

            return JsonResponse::success(
                $fasumType,
                'Fasum type created',
                201
            );
        } catch (Exception $e) {
            DB::rollBack();

            $this->logService->log(
                'fasum_type.create',
                'Failed to create fasum type',
                500,
                [
                    'error' => $e->getMessage(),
                    'payload' => [
                        'name' => $data['name'],
                        'address' => $data['address'],
                    ],
                ]
            );

            return JsonResponse::error(
                $e->getMessage(),
                'Failed to create fasum type',
                500
            );
        }
    }

    public function updateFasumType(FasumType $fasumType, array $data)
    {
        
        try {
            DB::beginTransaction();
            $before = $fasumType->toArray();

            $updateData = [
                'name'       => $data['name'],
                'color_code' => $data['color_code'],
                'updated_by' => auth()->id(),
            ];  

            $fasumType->update($updateData);

            DB::commit();

            $this->logService->log(
                'fasum_type.update',
                'Update fasum type',
                200,
                [
                    'before' => $before,
                    'after'  => $fasumType->toArray(),
                ]
            );

            return JsonResponse::success(
                $fasumType,
                'Fasum type updated',
                201
            );

        } catch (Exception $e) {
            DB::rollBack();

            $this->logService->log(
                'fasum_type.update',
                'Failed to update fasum type',
                500,
                [
                    'error' => $e->getMessage(),
                    'payload' => $data,
                ]
            );

            throw $e;
        }
    }
    public function deleteFasumType(FasumType $fasumType)
    {
        try {
            DB::beginTransaction();
            $before = $fasumType->toArray();

            $fasumType->delete();

            DB::commit();

            $this->logService->log(
                'fasum_type.delete',
                'Delete fasum type',
                200,
                [
                    'before' => $before,
                ]
            );

            return JsonResponse::success(
                null,
                'Fasum type deleted',
                200
            );
        } catch (Exception $e) {
            DB::rollBack();

            $this->logService->log(
                'fasum_type.delete',
                'Failed to delete fasum type',
                500,
                [
                    'error' => $e->getMessage(),
                    'fasum_type_id' => $fasumType->id,
                ]
            );

            throw $e;
        }
    }
}
