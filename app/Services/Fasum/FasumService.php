<?php

namespace App\Services\Fasum;

use App\Helpers\JsonResponse;
use App\Models\Fasum;
use App\Services\ActivityLog\ActivityLogService;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FasumService
{
    protected $logService;

    public function __construct(ActivityLogService $logService)
    {
        $this->logService = $logService;
    }
    
   public function getAllFasum($limit = 10, $paginate = true)
    {
        try {
            $order  = request('order', 'ASC');
            $search = request('search', request('q', ''));
            $ref    = request('ref', 'order');
            $start  = request('start', null);
            $end    = request('end', null);

            $query = Fasum::query();

            $query->with('type');
            $query->where('unit_id', Auth::user()->unit_id);
            if (!empty($search)) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'ILIKE', "%{$search}%");
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

            return JsonResponse::success($data, 'Fasum found', 200);

        } catch (Exception $e) {

            $this->logService->log(
                'fasum.fetch_all',
                'Failed to fetch fasums',
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

    public function createFasum(array $data)
    {
        
        try {
            DB::beginTransaction();
            $fasum = Fasum::create([
                'unit_id' => Auth::user()->unit_id,
                'name' => $data['name'],
                'type_id' => $data['type_id'],
                'address' => $data['address'],
                'latitude' => $data['latitude'],
                'longitude' => $data['longitude'],
                'province_id' => $data['province_id'],
                'city_id' => $data['city_id'],
                'contact' => $data['contact'] ?? null,
                'created_by' => auth()->id(),
            ]);
            
            DB::commit();

            $this->logService->log(
                'fasum.create',
                'Create fasum',
                201,
                [
                    'fasum_id' => $fasum->id,
                    'user_id'   => $fasum->user_id,
                    'name'   => $fasum->name,
                    'address' => $fasum->address,
                    'type' => $fasum->type,
                    'latitude' => $fasum->latitude,
                    'longitude' => $fasum->longitude,
                    'province_id' => $fasum->province_id,
                    'city_id' => $fasum->city_id,
                    'created_by' => auth()->id(),
                ]
            );

            return JsonResponse::success(
                $fasum,
                'Fasum created',
                201
            );
        } catch (Exception $e) {
            DB::rollBack();

            $this->logService->log(
                'fasum.create',
                'Failed to create fasum',
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
                'Failed to create fasum',
                500
            );
        }
    }

    public function updateFasum(Fasum $fasum, array $data)
    {
        
        try {
            DB::beginTransaction();
            $before = $fasum->toArray();

            $updateData = [
                'name'       => $data['name'],
                'address'    => $data['address'],
                'type_id' => $data['type_id'],
                'latitude' => $data['latitude'],
                'longitude' => $data['longitude'],
                'province_id' => $data['province_id'],
                'city_id' => $data['city_id'],
                'contact' => $data['contact'] ?? null,
                'updated_by' => auth()->id(),
            ];

            $fasum->update($updateData);

            DB::commit();

            $this->logService->log(
                'fasum.update',
                'Update fasum',
                200,
                [
                    'before' => $before,
                    'after'  => $fasum->toArray(),
                ]
            );

            return JsonResponse::success(
                $fasum,
                'Fasum updated',
                201
            );

        } catch (Exception $e) {
            DB::rollBack();

            $this->logService->log(
                'fasum.update',
                'Failed to update fasum',
                500,
                [
                    'error' => $e->getMessage(),
                    'payload' => $data,
                ]
            );

            throw $e;
        }
    }
    public function deleteFasum(Fasum $fasum)
    {
        try {
            DB::beginTransaction();
            $before = $fasum->toArray();

            $fasum->delete();

            DB::commit();

            $this->logService->log(
                'fasum.delete',
                'Delete fasum',
                200,
                [
                    'before' => $before,
                ]
            );

            return JsonResponse::success(
                null,
                'Fasum deleted',
                200
            );
        } catch (Exception $e) {
            DB::rollBack();

            $this->logService->log(
                'fasum.delete',
                'Failed to delete fasum',
                500,
                [
                    'error' => $e->getMessage(),
                    'fasum_id' => $fasum->id,
                ]
            );

            throw $e;
        }
    }
}
