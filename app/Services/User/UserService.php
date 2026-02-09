<?php

namespace App\Services\User;

use App\Helpers\JsonResponse;
use App\Models\Role;
use App\Models\User;
use App\Services\ActivityLog\ActivityLogService;
use Exception;
use Illuminate\Support\Facades\DB;

class UserService
{
    protected $logService;

    public function __construct(ActivityLogService $logService)
    {
        $this->logService = $logService;
    }
    
   public function getAllUser($limit = 10, $paginate = true)
    {
        try {
            $order  = request('order', 'DESC');
            $search = request('q', '');
            $ref    = request('ref', 'id');
            $start  = request('start', null);
            $end    = request('end', null);

            /**
             * ✅ Eloquent + eager load roles
             * ✅ Exclude current logged-in user
             */
            $query = User::with('roles')
                ->where('id', '!=', auth()->id());

            // 🔍 Search
            if (!empty($search)) {
                $query->where('name', 'like', "%{$search}%");
            }

            // 📅 Date filter
            if ($start && $end) {
                $end = date('Y-m-d', strtotime($end . ' +1 day'));
                $query->whereBetween('users.created_at', [$start, $end]);
            } elseif ($start) {
                $query->whereDate('users.created_at', '>=', $start);
            } elseif ($end) {
                $query->whereDate('users.created_at', '<=', $end);
            }

            // ↕️ Order
            $query->orderBy($ref, $order);

            // 📄 Pagination / non-pagination
            if ($paginate) {
                $users = $query->paginate($limit)->withQueryString();
            } else {
                $users = $limit > 0
                    ? $query->limit($limit)->get()
                    : $query->get();
            }

            return JsonResponse::success($users, 'User found', 200);

        } catch (\Exception $e) {
            $this->logService->log(
                'user.fetch_all',
                'Failed to fetch users',
                500,
                [
                    'error' => $e->getMessage(),
                    'params' => request()->all(),
                ]
            );

            return JsonResponse::error(
                $e->getMessage(),
                'User not found',
                500
            );
        }
    }


    public function createUser(array $data)
    {
        DB::beginTransaction();

        try {
            // Find user by email including soft deleted
            $user = User::withTrashed()
                ->where('email', $data['email'])
                ->first();

            // Get role name
            $roleName = null;
            if (isset($data['role'])) {
                $role = Role::find($data['role']);
                if ($role) {
                    $roleName = $role->name;
                }
            }

            // Determine user type based on role
            if ($roleName === 'Super-Admin') {
                $type = 'admin';
            } elseif ($roleName === 'BUJP') {
                $type = 'bujp';
            } else {
                $type = 'user';
            }

            /**
             * ============================
             * 🔁 CASE 1: USER SOFT DELETED
             * ============================
             */
            if ($user && $user->trashed()) {

                $user->restore();

                $user->update([
                    'password'   => bcrypt($data['password']),
                    'type'       => $type,
                    'updated_by' => auth()->id(),
                ]);

                if ($roleName) {
                    $user->syncRoles([$roleName]);
                }

                DB::commit();

                // ✅ LOG RESTORE
                $this->logService->log(
                    'user.restore',
                    'Restore user and update password',
                    201,
                    [
                        'user_id' => $user->id,
                        'email'   => $user->email,
                        'role'    => $roleName,
                    ]
                );

                return JsonResponse::success(
                    $user,
                    'User restored and password updated',
                    201
                );
            }

            /**
             * ============================
             * 🆕 CASE 2: NEW USER
             * ============================
             */
            if (!$user) {
                $user = User::create([
                    'name'       => $data['name'],
                    'email'      => $data['email'],
                    'password'   => bcrypt($data['password']),
                    'type'       => $type,
                    'created_by' => auth()->id(),
                ]);

                if ($roleName) {
                    $user->assignRole($roleName);
                }

                DB::commit();

                // ✅ LOG CREATE
                $this->logService->log(
                    'user.create',
                    'Create user',
                    201,
                    [
                        'user_id' => $user->id,
                        'email'   => $user->email,
                        'role'    => $roleName,
                    ]
                );

                return JsonResponse::success(
                    $user,
                    'User created',
                    201
                );
            }

            /**
             * ============================
             * ❌ CASE 3: EMAIL EXISTS
             * ============================
             */
            DB::rollBack();

            $this->logService->log(
                'user.create',
                'Failed to create user because email already exists',
                409,
                [
                    'email' => $data['email'],
                ]
            );

            return JsonResponse::error(
                'Email already exists',
                'Failed to create user',
                409
            );

        } catch (Exception $e) {
            DB::rollBack();

            $this->logService->log(
                'user.create',
                'Failed to create user',
                500,
                [
                    'error' => $e->getMessage(),
                    'payload' => [
                        'email' => $data['email'] ?? null,
                    ],
                ]
            );

            return JsonResponse::error(
                $e->getMessage(),
                'Failed to create user',
                500
            );
        }
    }

    public function updateUser(User $user, array $data)
    {
        DB::beginTransaction();

        try {
            $before = $user->toArray();
            // Ambil role
            $roleName = null;
            if (isset($data['role'])) {
                $role = Role::find($data['role']);
                if ($role) {
                    $roleName = $role->name;
                }
            }

            if ($roleName === 'Super-Admin') {
                $type = 'admin';
            } elseif ($roleName === 'BUJP') {
                $type = 'bujp';
            } else {
                $type = 'user';
            }

            $updateData = [
                'name'       => $data['name'],
                'email'      => $data['email'],
                'type'       => $type,
                'updated_by' => auth()->id(),
            ];

            if (!empty($data['password'])) {
                $updateData['password'] = bcrypt($data['password']);
            }

            $user->update($updateData);

            if ($roleName) {
                $user->syncRoles([$roleName]);
            }

            DB::commit();

            $this->logService->log(
                'user.update',
                'Update user',
                200,
                [
                    'before' => $before,
                    'after'  => $user->toArray(),
                    'role'   => $roleName,
                ]
            );

            return $user;

        } catch (Exception $e) {
            DB::rollBack();

            $this->logService->log(
                'user.update',
                'Failed to update user',
                500,
                [
                    'error' => $e->getMessage(),
                    'payload' => $data,
                ]
            );

            throw $e;
        }
    }

    public function deleteUser(User $user)
    {
        DB::beginTransaction();

        try {
            if ($user->id === auth()->id()) {
                $this->logService->log(
                    'user.delete',
                    'User failed to delete because trying to delete self',
                    403,
                    ['user_id' => $user->id]
                );

                throw new Exception('Anda tidak dapat menghapus akun sendiri');
            }

            $user->update([
                'deleted_by' => auth()->id(),
            ]);

            $user->delete();

            DB::commit();

            $this->logService->log(
                'user.delete',
                'Delete user',
                200,
                [
                    'user_id' => $user->id,
                    'email'   => $user->email,
                ]
            );

            return JsonResponse::success(
                null,
                'User deleted',
                200
            );

        } catch (Exception $e) {
            DB::rollBack();

            $this->logService->log(
                'user.delete',
                'Failed to delete user',
                500,
                [
                    'user_id' => $user->id ?? null,
                    'error'   => $e->getMessage(),
                ]
            );

            return JsonResponse::error(
                $e->getMessage(),
                'Failed to delete user',
                500
            );
        }
    }
}
