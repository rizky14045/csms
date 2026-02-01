<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class UnitController extends Controller
{
    public function index(){
        $data['units'] = User::with('userProfile')->where('type','user')->paginate(25);
        return view('admin.unit.index',$data);
    }

    public function create(){
        return view('admin.unit.create');
    }

    public function store(Request $request){
        try {
            DB::beginTransaction();

            $request->validate([
                'unit_code' => 'required',
                'name' => 'required',
                'email' => 'required|unique:users,email|email:rfc,dns',
                'address' => 'required',
                'latitude' => 'required',
                'longitude' => 'required',
            ],[
                'unit_code.required' => 'Kode Unit harus diisi!',
                'name.required' => 'Nama harus diisi!',
                'address.required' => 'Alamat harus diisi!',
                'email.required' => 'Email harus diisi!',
                'email.unique' => 'Email sudah dipakai sebelumnya!',
                'email.email' => 'Format Email harus benar',
                'latitude.required' => 'Latitude harus diisi!',
                'longitude.required' => 'Longitude harus diisi!',
       
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->email),
                'type' => 'user',
                'created_by' => Auth::user()->id
            ]);

            UserProfile::create([
                'user_id' => $user->id,
                'unit_code' => $request->unit_code,
                'address' => $request->address,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
            ]);
            
            DB::commit();
            Alert::success('Tambah Berhasil', 'Unit berhasil dibuat!');
            return redirect()->route('admin.unit.index');
            
        } catch (\Throwable $th) {

            DB::rollback();
            Alert::error('Tambah Gagal', 'Unit gagal dibuat!');
            return redirect()->route('admin.unit.index');
        }
    }

    public function edit($id){
        $unit = User::with('userProfile')->where('type','user')->where('id', $id)->first();
        if(!$unit){
            abort(404);
        }
        $data['unit'] = $unit;
        return view('admin.unit.edit',$data);
    }

    public function update(Request $request,$id){

        try {
            DB::beginTransaction();

            $unit = User::where('id',$id)->first();

            $request->validate([
                'unit_code' => 'required',
                'name' => 'required',
                'email' => 'required|email:rfc,dns|unique:users,email,'.$unit->id,
                'address' => 'required',
                'latitude' => 'required',
                'longitude' => 'required',
            ],[
                'unit_code.required' => 'Kode Unit harus diisi!',
                'name.required' => 'Nama harus diisi!',
                'address.required' => 'Alamat harus diisi!',
                'email.required' => 'Email harus diisi!',
                'email.unique' => 'Email sudah dipakai sebelumnya!',
                'email.email' => 'Format Email harus benar',
                'latitude.required' => 'Latitude harus diisi!',
                'longitude.required' => 'Longitude harus diisi!',
       
            ]);

            $unit->name = $request->name;
            $unit->email = $request->email;
            $unit->updated_by = Auth::user()->id;
            $unit->save();

            $profile = UserProfile::where('user_id',$id)->first();
            $profile->address = $request->address;
            $profile->latitude = $request->latitude;
            $profile->longitude = $request->longitude;
            $profile->unit_code = $request->unit_code;
            $profile->save();
            
            DB::commit();
            Alert::success('Update Berhasil', 'Unit berhasil diubah!');
            return redirect()->route('admin.unit.index');
            
        } catch (\Throwable $th) {

            DB::rollback();
            dd($th);
            Alert::error('Update Gagal', 'Unit gagal diubah!');
            return redirect()->route('admin.unit.index');
        }
    }

    public function destroy($id){
        try {
            DB::beginTransaction();

            $unit = User::where('id',$id)->first();
            $unit->deleted_by = Auth::user()->id;
            $unit->save();
            $unit->delete();
            
            DB::commit();
            Alert::success('Delete Berhasil', 'Unit berhasil dihapus!');
            return redirect()->route('admin.unit.index');
            
        } catch (\Throwable $th) {

            DB::rollback();
            Alert::error('Delete Gagal', 'Unit gagal dihapus!');
            return redirect()->route('admin.unit.index');
        }
    }
}
