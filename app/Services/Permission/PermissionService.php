<?php

namespace App\Services\Permission;

use App\Helpers\JsonResponse;
use App\Models\Permission;
use App\Services\ActivityLog\ActivityLogService;
use Exception;
use Illuminate\Support\Facades\DB;

class PermissionService
{
    protected $logService;

    public function __construct(ActivityLogService $logService)
    {
        $this->logService = $logService;
    }
    
    public function getAllPermission($limit = 10, $paginate = true)
    {
        try {
            // Get permission by ID
            $order = request('order', 'DESC');
            $search = request('q', '');
            $ref = request('ref', 'id');
            $start = request('start', null); // Default to earliest date
            $end = request('end', null); // Default to today

            $search = request('search', request('q', ''));

            $query = Permission::query();
            if (!empty($search)) $query = $query->whereRaw("name like ?", ["%{$search}%"]);

            if ($start && $end) {
                $end = date('Y-m-d', strtotime($end . ' +1 day'));
                $query = $query->whereBetween('permissions.created_at', [$start, $end]);
            } elseif ($start) {
                $query = $query->where('permissions.created_at', '>=', $start);
            } elseif ($end) {
                $query = $query->where('permissions.created_at', '<=', $end);
            }

            $permission = $query->orderBy($ref, $order);
            if($paginate){
                $permission = $permission->paginate($limit)->withQueryString();
            } else {
                if($limit != 0) {
                    $permission = $permission->limit($limit);
                }else{
                    $permission = $permission->get();
                }
            }
            
            
            return JsonResponse::success($permission , 'Permission found', 200);
        } catch (Exception $e) {
            $this->logService->log(
                'permission.fetch',
                'Failed to fetch permissions',
                500,
                [
                    'error' => $e->getMessage(),
                    'params' => request()->all(),
                ]
            );
            
            return JsonResponse::error($e->getMessage() , 'Permission not found', 404);
        }
    }

    public function createPermission(array $data)
    {
        DB::beginTransaction();

        try {
            $permission = Permission::create([
                'name'       => $data['name'],
                'guard_name' => 'web',
                'created_by' => auth()->id(),
            ]);

            if (!empty($data['permissions']) && is_array($data['permissions'])) {
                $permission->syncPermissions($data['permissions']);
            }

            DB::commit();

            $this->logService->log(
                'permission.create',
                'Create permission',
                201,
                ['permission' => $permission->toArray()]
            );

            return JsonResponse::success(
                $permission,
                'Permission created',
                201
            );

        } catch (Exception $e) {
            DB::rollBack();

            $this->logService->log(
                'permission.create',
                'Failed to create permission',
                500,
                ['error' => $e->getMessage()]
            );

            return JsonResponse::error(
                $e->getMessage(),
                'Failed to create permission',
                500
            );
        }
    }

    public function updatePermission(Permission $permission, array $data)
    {
        DB::beginTransaction();

        try {
            $before = $permission->toArray();

            $permission->update([
                'name'       => $data['name'],
                'updated_by' => auth()->id(),
            ]);

            if (array_key_exists('permissions', $data)) {
                $permission->syncPermissions($data['permissions'] ?? []);
            }

            DB::commit();

            $this->logService->log(
                'permission.update',
                'Update permission',
                201,
                [
                    'before' => $before,
                    'after'  => $permission->toArray(),
                ]
            );

            return JsonResponse::success(
                $permission,
                'Permission updated',
                200
            );

        } catch (Exception $e) {
            DB::rollBack();

            $this->logService->log(
                'permission.update',
                'Failed to update permission',
                500,
                ['error' => $e->getMessage()]
            );

            return JsonResponse::error(
                $e->getMessage(),
                'Failed to update permission',
                500
            );
        }
    }

    public function deletePermission(Permission $permission)
    {
        DB::beginTransaction();

        try {
            if ($permission->roles()->exists()) {
                $this->logService->log(
                    'permission.delete',
                    'Permission failed to delete because it is still used by role',
                    409, // Conflict
                    ['permission_id' => $permission->id]
                );

                throw new Exception(
                    'Permission masih digunakan oleh role'
                );
            }

            $permission->delete();

            $permission->update([
                'deleted_by' => auth()->id(),
            ]);

            DB::commit();

            $this->logService->log(
                'permission.delete',
                'Delete permission',
                201,
                ['permission' => $permission->toArray()]
            );

            return JsonResponse::success(
                null,
                'Permission deleted',
                200
            );

        } catch (Exception $e) {
            DB::rollBack();

            $this->logService->log(
                    'permission.delete',
                    'Failed to delete permission',
                    500,
                    ['error' => $e->getMessage()]
                );

            return JsonResponse::error(
                $e->getMessage(),
                'Failed to delete permission',
                500
            );
        }
    }
}
