<?php

namespace App\Services\Attribute;

use App\Helpers\JsonResponse;
use App\Models\Attribute;
use App\Services\ActivityLog\ActivityLogService;
use Exception;
use Illuminate\Support\Facades\DB;

class AttributeService
{
    protected $logService;

    public function __construct(ActivityLogService $logService)
    {
        $this->logService = $logService;
    }
    
   public function getAllAttribute($limit = 10, $paginate = true, $type_attribute = null, $user_id = null, $unit_id = null)
    {
        try {
            $order  = request('order', 'DESC');
            $search = request('search', request('q', ''));
            $ref    = request('ref', 'id');
            $start  = request('start', null);
            $end    = request('end', null);

            $query = Attribute::query();

            if (!empty($search)) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
            }

            if (!empty($type_attribute)) {
                $query->where('type_attribute', $type_attribute);
            }

            if ($unit_id) {
                $query->where('unit_id', $unit_id);
            } elseif ($user_id) {
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

            return JsonResponse::success($data, 'Attributes found', 200);

        } catch (Exception $e) {

            $this->logService->log(
                'attribute.fetch_all',
                'Failed to fetch attributes',
                500,
                [
                    'error' => $e->getMessage(),
                    'params' => request()->all(),
                ]
            );

            return JsonResponse::error(
                $e->getMessage(),
                'Attributes not found',
                500
            );
        }
    }

    public function getAttributeById(int $id, $user_id = null)
    {
        try {

            $query = Attribute::where('id', $id);

            if (!empty($user_id)) {
                $query->where('user_id', $user_id);
            }

            $attribute = $query->first();

            if (!$attribute) {

                $this->logService->log(
                    'attribute.fetch_one',
                    'Attribute not found',
                    404,
                    [
                        'id' => $id,
                        'user_id' => $user_id,
                    ]
                );

                return JsonResponse::error(
                    'Attribute not found',
                    'Attribute not found',
                    404
                );
            }

            return JsonResponse::success(
                $attribute,
                'Attribute found',
                200
            );

        } catch (Exception $e) {

            $this->logService->log(
                'attribute.fetch_one',
                'Failed to fetch attribute',
                500,
                [
                    'id' => $id,
                    'user_id' => $user_id,
                    'error' => $e->getMessage(),
                ]
            );

            return JsonResponse::error(
                $e->getMessage(),
                'Failed to fetch attribute',
                500
            );
        }
    }

    public function createAttribute(array $data)
    {
        DB::beginTransaction();

        try {
            $user_id = null;
            if(isset($data['user_id'])){
                $user_id = $data['user_id'];
            }

            $attribute = Attribute::create([
                'user_id' => $user_id,
                'unit_id' => $data['unit_id'] ?? null,
                'name' => $data['name'],
                'status_ownership' => $data['status_ownership'],
                'unit' => $data['unit'],
                'standard_contract' => $data['standard_contract'],
                'type_attribute' => $data['type_attribute'],
                'created_by' => auth()->id(),
            ]);
            
            DB::commit();

            // ✅ LOG CREATE
            $this->logService->log(
                'attribute.create',
                'Create attribute',
                201,
                [
                    'attribute_id' => $attribute->id,
                    'name'   => $attribute->name,
                    'status_ownership'    => $attribute->status_ownership,
                ]
            );

            return JsonResponse::success(
                $attribute,
                'Attribute created',
                201
            );
        } catch (Exception $e) {
            DB::rollBack();

            $this->logService->log(
                'attribute.create',
                'Failed to create attribute',
                500,
                [
                    'error' => $e->getMessage(),
                    'payload' => [
                        'name' => $data['name'] ?? null,
                        'status_ownership' => $data['status_ownership'] ?? null,
                        'unit' => $data['unit'] ?? null,
                        'standard_contract' => $data['standard_contract'] ?? null,
                        'type_attribute' => $data['type_attribute'] ?? null,
                    ],
                ]
            );

            return JsonResponse::error(
                $e->getMessage(),
                'Failed to create attribute',
                500
            );
        }
    }

    public function updateAttribute(Attribute $attribute, array $data)
    {
        DB::beginTransaction();

        try {
            $before = $attribute->toArray();

            $updateData = [
                'name'       => $data['name'],
                'status_ownership'      => $data['status_ownership'],
                'unit' => $data['unit'],
                'standard_contract' => $data['standard_contract'],
                'type_attribute' => $data['type_attribute'],
                'updated_by' => auth()->id(),
            ];

            $attribute->update($updateData);

            DB::commit();

            $this->logService->log(
                'attribute.update',
                'Update attribute',
                200,
                [
                    'before' => $before,
                    'after'  => $attribute->toArray(),
                ]
            );

            return JsonResponse::success(
                $attribute,
                'Attribute updated',
                201
            );

        } catch (Exception $e) {
            DB::rollBack();

            $this->logService->log(
                'attribute.update',
                'Failed to update attribute',
                500,
                [
                    'error' => $e->getMessage(),
                    'payload' => $data,
                ]
            );

            throw $e;
        }
    }

    public function deleteAttribute(Attribute $attribute)
    {
        DB::beginTransaction();

        try {
            $attribute->update([
                'deleted_by' => auth()->id(),
            ]);

            $attribute->delete();

            DB::commit();

            $this->logService->log(
                'attribute.delete',
                'Delete attribute',
                201,
                [
                    'attribute_id' => $attribute->id,
                    'name'   => $attribute->name,
                ]
            );

            return JsonResponse::success(
                null,
                'Attribute deleted',
                201
            );

        } catch (Exception $e) {
            DB::rollBack();

            $this->logService->log(
                'attribute.delete',
                'Failed to delete attribute',
                500,
                [
                    'attribute_id' => $attribute->id ?? null,
                    'error'   => $e->getMessage(),
                ]
            );

            return JsonResponse::error(
                $e->getMessage(),
                'Failed to delete attribute',
                500
            );
        }
    }
}
