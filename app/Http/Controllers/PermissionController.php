<?php

namespace App\Http\Controllers;

use App\Http\Validation\PermissionValidation;
use App\Models\Permission;
use App\Models\User;
use App\Services\Permission\PermissionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class PermissionController extends Controller
{
    protected $permissionService;

    public function __construct(PermissionService $permissionService)
    {
        $this->permissionService = $permissionService;

        $this->middleware('can:view.permission')->only(['index']);
        $this->middleware('can:create.permission')->only(['create', 'store']);
        $this->middleware('can:edit.permission')->only(['edit', 'update']);
        $this->middleware('can:delete.permission')->only(['destroy']);
    }

    protected function validator(array $data, $validation, array $messages = [])
    {
        return Validator::make($data, $validation, $messages);
    }

    public function index(Request $request)
    {
        $permissions = $this->permissionService->getAllPermission(10, true);
        $data['permissions'] = getPaginate($permissions);
        $data['request'] = $request;

        return view('permission.index', $data);
    }

    public function create()
    {
        return view('permission.create');
    }

    public function store(Request $request)
    {
        // Validation rules
        $validator = $this->validator($request->all(), PermissionValidation::rulesForCreate(), PermissionValidation::messages());
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $tes = $this->permissionService->createPermission($request->only(['name']));

        Alert::success('Permission Berhasil Dibuat', 'Permission berhasil ditambahkan!');
        return redirect()->route('permissions.index')->with('success', 'Permission berhasil ditambahkan!');
    }

    public function edit(Permission $permission)
    {
        $permissions = $this->permissionService->getAllPermission(0, false);
        $data['permissions'] = getData($permissions);

        $data['permission'] = $permission;
        $data['rolePermissions'] = $permission->permissions->pluck('id')->toArray();

        return view('permission.edit', $data);
    }

    public function update(Request $request, Permission $permission)
    {
        $validator = $this->validator($request->all(), PermissionValidation::rulesForUpdate($permission->id), PermissionValidation::messages());

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $this->permissionService->updatePermission(
            $permission,
            $request->only(['name'])
        );

        Alert::success('Permission Berhasil Diupdate', 'Permission berhasil diperbarui!');
        return redirect()->route('permissions.index');
    }

    public function destroy(Permission $permission)
    {
        $permission = $this->permissionService->deletePermission($permission);
        $status = getStatus($permission);
        if (!$status) {
            $message = getMsgError($permission);
            Alert::error('Permission Gagal Dihapus', $message);
            return redirect()->route('permissions.index');
        }
        Alert::success('Permission Berhasil Dihapus', 'Permission berhasil dihapus!');
        return redirect()->route('permissions.index');
    }
}
