<?php

namespace App\Services\User;

use App\Helpers\JsonResponse;
use App\Models\BujpProfile;
use App\Models\Role;
use App\Models\Unit;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\Vendor;
use App\Services\ActivityLog\ActivityLogService;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class UserService
{
    protected $logService;

    public function __construct(ActivityLogService $logService)
    {
        $this->logService = $logService;
    }
    
   public function getAllUser(
        $limit = 10,
        $paginate = true,
        $user_type = null,
        $only_my_vendor = false,
        $unit_id = null
    )
    {
        try {

            $order  = request('order', 'DESC');
            $search = request('search', '');
            $ref    = request('ref', 'id');
            $start  = request('start', null);
            $end    = request('end', null);

            /**
             * ⭐ Tentukan relasi yang akan di-load
             */
            $with = ['roles'];

            if ($only_my_vendor) {
                $with[] = 'bujpProfile';
                $with[] = 'vendor';
            }

            /**
             * ✅ Query awal
             */
            $query = User::with($with)
                ->where('id', '!=', auth()->id());

            /**
             * 🔥 Filter ONLY MY VENDOR
             */
            if ($only_my_vendor) {

                $vendorUserIds = \App\Models\Vendor::where(
                        'parent_user_id',
                        auth()->id()
                    )
                    ->pluck('user_id');

                $query->whereIn('id', $vendorUserIds);
            }

            if ($unit_id) {
                $query->where('unit_id', $unit_id);
            }

            // 🔍 Search
            if (!empty($search)) {
                $query->where('name', 'ILIKE', "%{$search}%");
            }

            // 🧑‍💼 Filter tipe user
            if ($user_type !== null) {

                // jika array
                if (is_array($user_type)) {

                    $query->whereIn('type', $user_type);

                } else {

                    $query->where('type', $user_type);

                }
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
                ['error' => $e->getMessage()]
            );

            return JsonResponse::error(
                $e->getMessage(),
                'User not found',
                500
            );
        }
    }

    public function createUser(array $data, $withVendor = false)
    {
        DB::beginTransaction();

        try {
            // Find user by email including soft deleted
            $user = User::withTrashed()
                ->where('email', $data['email'])
                ->first();
            $unit_id = null;

            if(isset($data['unit_id'])){
                $unit_id = $data['unit_id'];
            }else{
                $unit_id = auth()->user()->unit_id ?? null;
            }

            // Get role name
            $roleName = null;
            if (isset($data['role'])) {
                $role = Role::find($data['role']);
                if ($role) {
                    $roleName = $role->name;
                }
            }

            // Determine user type based on role
            if ($roleName === 'Admin') {
                $type = 'admin';
            } elseif ($roleName === 'BUJP') {
                $type = 'bujp';
            } elseif ($roleName === 'Pusat') {
                $type = 'pusat';
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
                    'unit_id'       => $unit_id,
                    'login_type' => !empty($data['is_ldap']) ? 1 : 0,
                    'updated_by' => auth()->id(),
                ]);

                if($withVendor){
                    $profile = BujpProfile::where('user_id', $user->id)->first();
                    if(!$profile){
                        BujpProfile::create([
                            'user_id' => $user->id,
                            'npwp' => $data['npwp'] ?? "",
                            'address' => $data['address'] ?? "",
                            'created_by' => auth()->id(),
                        ]);
                    }

                    $user = User::find($user->id);
                    if($user){
                        Vendor::create([
                            'parent_user_id' => auth()->user()->id,
                            'name' => $user->name,
                            'email'      => $user->email,
                            'start_date' => $data['start_date'],
                            'end_date' => $data['end_date'],
                            'contract_number'=> $data['contract_number'],
                            'user_id' => $user->id,
                            'unit_id' => $unit_id,
                            'created_by' => auth()->id(),
                        ]);
                    }

                    Vendor::create([
                        'parent_user_id' => auth()->user()->id,
                        'name' => $data['name'],
                        'email'      => $data['email'],
                        'password'   => bcrypt($data['password']),
                        'start_date' => $data['start_date'],
                        'end_date' => $data['end_date'],
                        'contract_number'=> $data['contract_number'],
                        'user_id' => $user->id,
                        'unit_id' => $unit_id,
                        'created_by' => auth()->id(),
                    ]);
                }

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
                    'unit_id'       => $unit_id,
                    'login_type' => !empty($data['is_ldap']) ? 1 : 0,
                    'created_by' => auth()->id(),
                ]);

                if($withVendor){
                    BujpProfile::create([
                        'user_id' => $user->id,
                        'npwp' => $data['npwp'] ?? "",
                        'address' => $data['address'] ?? "",
                        'created_by' => auth()->id(),
                    ]);

                    Vendor::create([
                        'parent_user_id' => auth()->user()->id,
                        'name' => $data['name'],
                        'email'      => $data['email'],
                        'password'   => bcrypt($data['password']),
                        'start_date' => $data['start_date'],
                        'end_date' => $data['end_date'],
                        'contract_number'=> $data['contract_number'],
                        'user_id' => $user->id,
                        'unit_id' => auth()->user()->unit_id ?? null,
                        'created_by' => auth()->id(),
                    ]);
                }

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
            } elseif( $roleName === 'Pusat') {
                $type = 'pusat';
            }else{
                $type = 'user';
            }

            $updateData = [
                'name'       => $data['name'],
                'email'      => $data['email'],
                'type'       => $type,
                'unit_id'       => $unit_id,
                'login_type' => !empty($data['is_ldap']) ? 1 : 0,
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

    public function updateBujpProfile(array $data, $user_id){
        DB::beginTransaction();
        try {
            $profile = BujpProfile::where('user_id', $user_id)->first();
            if(!$profile){
                BujpProfile::create([
                    'user_id' => $user_id,
                    'npwp' => $data['npwp'] ?? "",
                    'address' => $data['address'] ?? "",
                    'created_by' => auth()->id(),
                ]);
            }

            $user = User::find($user_id);
            if($user){
                Vendor::create([
                    'parent_user_id' => auth()->user()->id,
                    'name' => $user->name,
                    'email'      => $user->email,
                    'password'   => bcrypt($data['password']),
                    'start_date' => $data['start_date'],
                    'end_date' => $data['end_date'],
                    'contract_number'=> $data['contract_number'],
                    'user_id' => $user_id,
                    'created_by' => auth()->id(),
                ]);
            }

            DB::commit();

            $this->logService->log(
                'user.update_bujp',
                'Update bujp',
                200,
                [
                    'data' => $data
                ]
            );

            return null;

        } catch (Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
            $this->logService->log(
                'user.update_bujp',
                'Failed to update bujp',
                500,
                [
                    'error' => $e->getMessage(),
                    'payload' => $data,
                ]
            );

            throw $e;
        }
    }

    public function getAllUnitByVendorID(
        $limit = 10,
        $paginate = true,
        $user_id = null,
    )
    {
        try {

            $order  = request('order', 'DESC');
            $search = request('q', '');
            $ref    = request('ref', 'units.id');
            $start  = request('start', null);
            $end    = request('end', null);

            $getVendor = Vendor::where('user_id', $user_id)->get();

            $unit_ids = $getVendor
                            ->pluck('unit_id')
                            ->filter()
                            ->toArray();

            $query = Unit::query()
                ->join('vendors', 'vendors.unit_id', '=', 'units.id')
                ->whereIn('units.id', $unit_ids)
                ->where('vendors.user_id', $user_id)
                ->select(
                    'units.id',
                    'units.name', 
                    'vendors.id as vendor_id',
                    'vendors.contract_number',
                    'vendors.created_at as vendor_created_at'
                );

            if (!empty($search)) {
                $query->where(function ($q) use ($search) {
                    $q->where('units.name', 'like', "%{$search}%");
                });
            }

            if ($start && $end) {
                $end = date('Y-m-d', strtotime($end . ' +1 day'));
                $query->whereBetween('units.created_at', [$start, $end]);

            } elseif ($start) {
                $query->whereDate('units.created_at', '>=', $start);

            } elseif ($end) {
                $query->whereDate('units.created_at', '<=', $end);
            }

            $query->orderBy($ref, $order);

            if ($paginate) {
                $vendors = $query->paginate($limit)->withQueryString();

                $vendors->getCollection()->transform(function ($item) {
                    $item->vendor_id_encrypted = Crypt::encryptString($item->vendor_id);
                    return $item;
                });

            } else {
                $vendors = $limit > 0
                    ? $query->limit($limit)->get()
                    : $query->get();

                $vendors->transform(function ($item) {
                    $item->vendor_id_encrypted = Crypt::encryptString($item->vendor_id);
                    return $item;
                });
            }

            return JsonResponse::success($vendors, 'Users found', 200);

        } catch (\Exception $e) {

            $this->logService->log(
                'user.fetch_units_by_vendor',
                'Failed to fetch units by vendor',
                500,
                ['error' => $e->getMessage()]
            );

            return JsonResponse::error(
                $e->getMessage(),
                'Units not found',
                500
            );
        }
    }

    public function validateVendorAccess($encryptedVendorId)
    {
        try {
            $vendorId = Crypt::decryptString($encryptedVendorId);

        } catch (\Exception $e) {
            $this->logService->log(
                'user.validate_vendor_access',
                'Failed to validate vendor access due to decryption error',
                500,
                ['error' => $e->getMessage()]
            );

            return false;
        }

        $vendor = Vendor::find($vendorId);

        if (!$vendor) {
            return [
                'status'  => false,
                'message' => 'Vendor tidak ditemukan',
                'data'    => null
            ];
        }

        if ($vendor->user_id != auth()->user()->id) {
            $this->logService->log(
                'user.validate_vendor_access',
                'Failed to validate vendor access due to ownership error',
                500,
                [
                    'vendor_id' => $vendorId,
                    'user_id'   => auth()->user()->id,
                ]
            );
            return false;
        }

        return true;
    }
}
