<?php

namespace App\Services\Role;

use App\Helpers\JsonResponse;
use App\Models\Role;
use App\Services\ActivityLog\ActivityLogService;
use Exception;
use Illuminate\Support\Facades\DB;

class RoleService
{
    protected $logService;

    public function __construct(ActivityLogService $logService)
    {
        $this->logService = $logService;
    }
    
    public function getAllRole($limit = 10, $paginate = true)
    {
        try {
            // Get role by ID
            $order = request('order', 'DESC');
            $search = request('q', '');
            $ref = request('ref', 'id');
            $start = request('start', null); // Default to earliest date
            $end = request('end', null); // Default to today

            $role = new Role;
            if(isset($_GET['q'])) $role = $role->whereRaw("name like ?", ["%".$_GET['q']."%"]);

            if ($start && $end) {
                $end = date('Y-m-d', strtotime($end . ' +1 day'));
                $role->whereBetween('roles.created_at', [$start, $end]);
            } elseif ($start) {
                $role->where('roles.created_at', '>=', $start);
            } elseif ($end) {
                $role->where('roles.created_at', '<=', $end);
            }

            $role = $role->orderBy($ref, $order);
            if($paginate){
                $role = $role->paginate($limit)->withQueryString();
            } else {
                if($limit != 0) {
                    $role = $role->limit($limit);
                }else{
                    $role = $role->get();
                }
            }
            return JsonResponse::success($role , 'Role found', 200);
        } catch (Exception $e) {
            return JsonResponse::error($e->getMessage() , 'Role not found', 404);
        }
    }

    public function createRole(array $data)
    {
        DB::beginTransaction();

        try {
            $role = Role::create([
                'name'       => $data['name'],
                'guard_name' => 'web',
                'created_by' => auth()->id(),
            ]);

            if (!empty($data['permissions']) && is_array($data['permissions'])) {
                $role->syncPermissions($data['permissions']);
            }

            DB::commit();

             $this->logService->log(
                'role.create',
                'Create role',
                201,
                [
                    'role' => $role->toArray(),
                    'permissions' => $data['permissions'] ?? [],
                ]
            );

            return JsonResponse::success(
                $role,
                'Role created',
                200
            );

        } catch (Exception $e) {
            DB::rollBack();

            $this->logService->log(
                'role.create',
                'Failed to create role',
                500,
                [
                    'error' => $e->getMessage(),
                    'payload' => $data,
                ]
            );

            return JsonResponse::error(
                $e->getMessage(),
                'Failed to create role',
                500
            );
        }
    }

    /**
     * Update Role + sync permissions
     */
    public function updateRole(Role $role, array $data)
    {
        DB::beginTransaction();

        try {
           $before = [
                'name' => $role->name,
                'permissions' => $role->permissions->pluck('id')->toArray(),
            ];

            $role->update([
                'name'       => $data['name'],
                'updated_by' => auth()->id(),
            ]);

            if (array_key_exists('permissions', $data)) {
                $role->syncPermissions($data['permissions'] ?? []);
            }

            DB::commit();
            
            $this->logService->log(
                'role.update',
                'Update role',
                200,
                [
                    'before' => $before,
                    'after' => [
                        'name' => $role->name,
                        'permissions' => $role->permissions->pluck('id')->toArray(),
                    ],
                ]
            );

            return JsonResponse::success(
                $role,
                'Role updated',
                201
            );  

        } catch (Exception $e) {
            DB::rollBack();

            $this->logService->log(
                'role.update',
                'Failed to update role',
                500,
                [
                    'error' => $e->getMessage(),
                    'payload' => $data,
                ]
            );

            return JsonResponse::error(
                $e->getMessage(),
                'Failed to update role',
                500
            );
        }
    }

    public function deleteRole(Role $role)
    {
        DB::beginTransaction();

        try {
            if ($role->users()->exists()) {
                $this->logService->log(
                    'role.delete',
                    'Role failed to delete because it is still assigned to users',
                    409, // Conflict
                    ['role_id' => $role->id]
                );

                throw new Exception(
                    'Role masih digunakan oleh user'
                );
            }

            $role->update([
                'deleted_by' => auth()->id(),
            ]);

            $role->delete(); // SOFT DELETE

            DB::commit();

            $this->logService->log(
                'role.delete',
                'Delete role',
                200,
                ['role' => $role->toArray()]
            );

            return JsonResponse::success(
                null,
                'Role deleted',
                200
            );

        } catch (Exception $e) {
            DB::rollBack();

            $this->logService->log(
                'role.delete',
                'Failed to delete role',
                500,
                [
                    'role_id' => $role->id ?? null,
                    'error' => $e->getMessage()
                ]
            );

            return JsonResponse::error(
                $e->getMessage(),
                'Failed to delete role',
                500
            );
        }
    }
}
