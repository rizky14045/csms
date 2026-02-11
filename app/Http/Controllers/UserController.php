<?php

namespace App\Http\Controllers;

use App\Http\Validation\UserValidation;
use App\Models\User;
use App\Services\Role\RoleService;
use App\Services\User\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class UserController extends Controller
{
    protected $userService, $roleService;

    public function __construct(UserService $userService, RoleService $roleService)
    {
        $this->userService = $userService;
        $this->roleService = $roleService;

        $this->middleware('can:view.user')->only(['index']);
        $this->middleware('can:create.user')->only(['create', 'store']);
        $this->middleware('can:edit.user')->only(['edit', 'update']);
        $this->middleware('can:delete.user')->only(['destroy']);
    }

    protected function validator(array $data, $validation, array $messages = [])
    {
        return Validator::make($data, $validation, $messages);
    }

    public function index(Request $request)
    {
        $users = $this->userService->getAllUser(10, true);
        $status = getStatus($users);
        if (!$status) {
            $message = getMsgError($users);
            Alert::error('User Gagal Ditampilkan');
            return redirect()->back();
        }
        $data['users'] = getPaginate($users);
        $data['request'] = $request;

        return view('users.index', $data);
    }

    public function create()
    {
        $roles = $this->roleService->getAllRole(0, false);
        $data['roles'] = getData($roles);

        return view('users.create', $data);
    }

    public function store(Request $request)
    {
        // Validation rules
        $validator = $this->validator($request->all(), UserValidation::rulesForCreate(), UserValidation::messages());
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $this->userService->createUser($request->all());

        Alert::success('User Berhasil Dibuat', 'User berhasil ditambahkan!');
        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan!');
    }

    public function edit(User $user)
    {
        $roles = $this->roleService->getAllRole(0, false);
        $data['roles'] = getData($roles);
        $data['user'] = $user;

        return view('users.edit', $data);
    }

    public function update(Request $request, User $user)
    {
        $validator = $this->validator(
            $request->all(),
            UserValidation::rulesForUpdate($user->id),
            UserValidation::messages()
        );

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $this->userService->updateUser(
            $user,
            $request->all()
        );

        Alert::success('Berhasil', 'User berhasil diperbarui');
        return redirect()->route('users.index');
    }

    public function destroy(User $user)
    {
        $status = $this->userService->deleteUser($user);
        if ($status) {
            Alert::success('Berhasil', 'User berhasil dihapus');
            return redirect()->route('users.index');
        } else {
            Alert::error('Gagal', 'User gagal dihapus');
            return redirect()->route('users.index');
        }
    }
}
