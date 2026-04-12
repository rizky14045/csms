<?php

namespace App\Services\Unit;

use App\Helpers\JsonResponse;
use App\Models\Unit;
use App\Services\ActivityLog\ActivityLogService;
use Exception;
use Illuminate\Support\Facades\DB;

class UnitService
{
    protected $logService;

    public function __construct(ActivityLogService $logService)
    {
        $this->logService = $logService;
    }
    
   public function getAllUnit($limit = 10, $paginate = true)
    {
        try {
            $order  = request('order', 'ASC');
            $search = request('q', '');
            $ref    = request('ref', 'order');
            $start  = request('start', null);
            $end    = request('end', null);

            $query = Unit::query();

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

            return JsonResponse::success($data, 'Unit found', 200);

        } catch (Exception $e) {

            $this->logService->log(
                'unit.fetch_all',
                'Failed to fetch units',
                500,
                [
                    'error' => $e->getMessage(),
                    'params' => request()->all(),
                ]
            );

            return JsonResponse::error(
                $e->getMessage(),
                'Unit not found',
                500
            );
        }
    }

    public function createUnit(array $data)
    {
        
        try {
            DB::beginTransaction();
            $unit = Unit::create([
                'name' => $data['name'],
                'address' => $data['address'],
                'unit_code' => $data['unit_code'],
                'type' => $data['type'],
                'latitude' => $data['latitude'],
                'longitude' => $data['longitude'],
                'province_id' => $data['province_id'],
                'city_id' => $data['city_id'],
                'created_by' => auth()->id(),
            ]);
            
            DB::commit();

            $this->logService->log(
                'unit.create',
                'Create unit',
                201,
                [
                    'unit_id' => $unit->id,
                    'name'   => $unit->name,
                    'address' => $unit->address,
                    'unit_code' => $unit->unit_code,
                    'type' => $unit->type,
                    'latitude' => $unit->latitude,
                    'longitude' => $unit->longitude,
                    'province_id' => $unit->province_id,
                    'city_id' => $unit->city_id,
                ]
            );

            return JsonResponse::success(
                $unit,
                'Unit created',
                201
            );
        } catch (Exception $e) {
            dd($e);
            DB::rollBack();

            $this->logService->log(
                'unit.create',
                'Failed to create unit',
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
                'Failed to create unit',
                500
            );
        }
    }

    public function updateUnit(Unit $unit, array $data)
    {
        
        try {
            DB::beginTransaction();
            $before = $unit->toArray();

            $updateData = [
                'name'       => $data['name'],
                'address'    => $data['address'],
                'unit_code' => $data['unit_code'],
                'type' => $data['type'],
                'latitude' => $data['latitude'],
                'longitude' => $data['longitude'],
                'province_id' => $data['province_id'],
                'city_id' => $data['city_id'],
                'updated_by' => auth()->id(),
            ];

            $unit->update($updateData);

            DB::commit();

            $this->logService->log(
                'unit.update',
                'Update unit',
                200,
                [
                    'before' => $before,
                    'after'  => $unit->toArray(),
                ]
            );

            return JsonResponse::success(
                $unit,
                'Unit updated',
                201
            );

        } catch (Exception $e) {
            DB::rollBack();

            $this->logService->log(
                'unit.update',
                'Failed to update unit',
                500,
                [
                    'error' => $e->getMessage(),
                    'payload' => $data,
                ]
            );

            throw $e;
        }
    }
}
