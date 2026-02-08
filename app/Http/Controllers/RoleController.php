<?php

namespace App\Http\Controllers;

use App\Http\Validation\RoleValidation;
use App\Models\Role;
use App\Models\User;
use App\Services\Permission\PermissionService;
use App\Services\Role\RoleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class RoleController extends Controller
{
    protected $roleService, $permissionService;

    public function __construct(RoleService $roleService, PermissionService $permissionService)
    {
        $this->roleService = $roleService;
        $this->permissionService = $permissionService;

        // $this->middleware('can:view.role')->only(['index']);
        // $this->middleware('can:create.role')->only(['create', 'store']);
        // $this->middleware('can:edit.role')->only(['edit', 'update']);
    }

    protected function validator(array $data, $validation, array $messages = [])
    {
        return Validator::make($data, $validation, $messages);
    }

    public function index(Request $request)
    {
        $roles = $this->roleService->getAllRole(10, true);
        $data['roles'] = getPaginate($roles);
        $data['request'] = $request;

        return view('role.index', $data);
    }

    public function create()
    {
        $permissions = $this->permissionService->getAllPermission(0, false);
        $data['permissions'] = getData($permissions);
        $data['groupedPermissions'] = groupPermissionsByModule($data['permissions']);
        return view('role.create', $data);
    }

    public function store(Request $request)
    {
        // Validation rules
        $validator = $this->validator($request->all(), RoleValidation::rulesForCreate(), RoleValidation::messages());
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $this->roleService->createRole($request->only(['name', 'permissions']));

        Alert::success('Role Berhasil Dibuat', 'Role berhasil ditambahkan!');
        return redirect()->route('roles.index')->with('success', 'Role berhasil ditambahkan!');
    }

    public function edit(Role $role)
    {
        $permissions = $this->permissionService->getAllPermission(0, false);
        $data['permissions'] = getData($permissions);
        $data['groupedPermissions'] = groupPermissionsByModule($data['permissions']);
        $data['role'] = $role;
        $data['rolePermissions'] = $role->permissions->pluck('id')->toArray();

        return view('role.edit', $data);
    }

    public function update(Request $request, Role $role)
    {
        $validator = $this->validator(
            $request->all(),
            RoleValidation::rulesForUpdate($role->id),
            RoleValidation::messages()
        );

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $this->roleService->updateRole(
            $role,
            $request->only(['name', 'permissions'])
        );

        Alert::success('Role Berhasil Diupdate', 'Role berhasil diperbarui!');
        return redirect()->route('roles.index');
    }

    public function destroy(Role $role)
    {
        $role = $this->roleService->deleteRole($role);
        $status = getStatus($role);
        if (!$status) {
            $message = getMsgError($role);
            Alert::error('Role Gagal Dihapus', $message);
            return redirect()->route('roles.index');
        }
        Alert::success('Role Berhasil Dihapus', 'Role berhasil dihapus!');
        return redirect()->route('roles.index');
    }
}
