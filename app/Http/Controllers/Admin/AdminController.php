<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\AdminProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class AdminController extends Controller
{
    public function index(){
        $data['admins'] = User::with('adminProfile')->where('type','admin')->paginate(25);
        return view('admin.admin.index',$data);
    }

    public function create(){
        return view('admin.admin.create');
    }

    public function store(Request $request){
        try {
            DB::beginTransaction();

            $request->validate([
                'name' => 'required',
                'email' => 'required|unique:users,email|email:rfc,dns',
            ],[
                'name.required' => 'Nama harus diisi!',
                'email.required' => 'Email harus diisi!',
                'email.unique' => 'Email sudah dipakai sebelumnya!',
                'email.email' => 'Format Email harus benar',
       
            ]);

            $admin = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->email),
                'type' => 'Admin',
                'created_by' => Auth::user()->id
            ]);

            AdminProfile::create([
                'user_id' => $admin->id,
                'name' => $admin->name
            ]);
            
            DB::commit();
            Alert::success('Tambah Berhasil', 'Admin berhasil dibuat!');
            return redirect()->route('admin.admin.index');
            
        } catch (\Throwable $th) {

            DB::rollback();
            Alert::error('Tambah Gagal', 'Admin gagal dibuat!');
            return redirect()->route('admin.admin.index');
        }
    }

    public function edit($id){
        $admin = User::where('type','admin')->where('id', $id)->first();
        if(!$admin){
            abort(404);
        }
        $data['admin'] = $admin;
        return view('admin.admin.edit',$data);
    }

    public function update(Request $request,$id){

        try {
            DB::beginTransaction();

            $admin = User::where('type','admin')->where('id',$id)->first();

            $request->validate([
                'name' => 'required',
                'email' => 'required|email:rfc,dns|unique:users,email,'.$admin->id,
            ],[
                'name.required' => 'Nama harus diisi!',
                'email.required' => 'Email harus diisi!',
                'email.unique' => 'Email sudah dipakai sebelumnya!',
                'email.email' => 'Format Email harus benar',
            ]);
            
            $admin->name = $request->name;
            $admin->email = $request->email;
            $admin->updated_by = Auth::user()->id;
            $admin->save();

            $profile = AdminProfile::where('user_id',$id)->first();
            $profile->name = $request->name;
            $profile->save();
            
            DB::commit();
            Alert::success('Update Berhasil', 'Admin berhasil diubah!');
            return redirect()->route('admin.admin.index');
            
        } catch (\Throwable $th) {

            DB::rollback();
            Alert::error('Update Gagal', 'Admin gagal diubah!');
            return redirect()->route('admin.admin.index');
        }
    }

    public function destroy($id){
        try {
            DB::beginTransaction();

            $admin = User::where('type','admin')->where('id',$id)->first();
            $admin->deleted_by = Auth::user()->id;
            $admin->save();
            $admin->delete();
            
            DB::commit();
            Alert::success('Delete Berhasil', 'Admin berhasil dihapus!');
            return redirect()->route('admin.admin.index');
            
        } catch (\Throwable $th) {

            DB::rollback();
            Alert::error('Delete Gagal', 'Admin gagal dihapus!');
            return redirect()->route('admin.admin.index');
        }
    }
}
