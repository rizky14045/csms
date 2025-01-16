<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use App\Models\Vendor;
use App\Models\Attribute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class VendorController extends Controller
{
    public function index(){

        $userId = Auth::user()->id;
        $data['vendors'] = Vendor::where('parent_user_id',$userId)->paginate(25);
        return view('user.vendor.index',$data);

    }

    public function create(){
        return view('user.vendor.create');
    }

    public function store(Request $request){

        try {
            DB::beginTransaction();

            $userId = Auth::user()->id;
            $request->validate([

                'name' => 'required',
                'npwp' => 'required',
                'email' => 'required|unique:vendors,email|email:rfc,dns',
                'address' => 'required',
            ],[
                'name.required' => 'Nama harus diisi!',
                'npwp.required' => 'Npwp harus diisi!',
                'email.required' => 'Email harus diisi!',
                'email.unique' => 'Email sudah digunakan!',
                'email.email' => 'Format email tidak sesuai!',
                'adress.required' => 'Alamat harus diisi!',
            ]);

            Vendor::create([
                'parent_user_id' => $userId,
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->npwp),
                'npwp' => $request->npwp,
                'address' => $request->address,
            ]);
            
            DB::commit();
            Alert::success('Tambah Berhasil', 'BUJP / Vendor berhasil dibuat!');
            return redirect()->route('user.vendor.index');
            
        } catch (\Throwable $th) {

            DB::rollback();
            throw $th;
        }
    }

    public function edit($id){

        $userId = Auth::user()->id;
        $vendor = Vendor::where('id', $id)->where('parent_user_id',$userId)->first();
        if(!$vendor){
            abort(404);
        }
        $data['vendor'] = $vendor;
        return view('user.vendor.edit',$data);
    }

    public function update(Request $request,$id){

        try {

            DB::beginTransaction();

            $userId = Auth::user()->id;

            $vendor = Vendor::where('id',$id)->where('parent_user_id',$userId)->first();

            if(!$vendor){
                abort(404);
            }
            $request->validate([

                'name' => 'required',
                'npwp' => 'required',
                'email' => 'required|email:rfc,dns|unique:vendors,email,'.$vendor->id,
                'address' => 'required',
            ],[
                'name.required' => 'Nama harus diisi!',
                'npwp.required' => 'Npwp harus diisi!',
                'email.required' => 'Email harus diisi!',
                'email.unique' => 'Email sudah digunakan!',
                'email.email' => 'Format email tidak sesuai!',
                'adress.required' => 'Alamat harus diisi!',
            ]);



            $vendor->name = $request->name;
            $vendor->email = $request->email;
            $vendor->npwp = $request->npwp;
            $vendor->address = $request->address;
            $vendor->save();
            
            DB::commit();
            Alert::success('Update Berhasil', 'BUJP / Vendor berhasil diubah!');
            return redirect()->route('user.vendor.index');
            
        } catch (\Throwable $th) {

            DB::rollback();
            throw $th;
        }
    }

    public function destroy($id){
        
        try {
            DB::beginTransaction();
            
            $userId = Auth::user()->id;
            $vendor = Vendor::where('id',$id)->where('parent_user_id',$userId)->first();
            $vendor->delete();
            
            DB::commit();
            Alert::success('Delete Berhasil', 'BUJP / Vendor berhasil dihapus!');
            return redirect()->route('user.vendor.index');
            
        } catch (\Throwable $th) {

            DB::rollback();
            throw $th;
        }
    }
}
