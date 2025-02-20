<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Models\SecurityExternal;
use App\Http\Helper\BlockMonthly;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class SecurityExternalController extends Controller
{
    public function create(){

        return view('user.security-external.create');

    }
    public function store(Request $request){

        try {
            DB::beginTransaction();

            $userId = Auth::guard('web')->user()->id;

            $request->validate([
                'name' => 'required',
                'instansi' => 'required',
                'regional_unit' => 'required',
                'warrant_number' => 'required',
                'note' => 'required',

            ],[
                'name.required' => 'Nama harus diisi!',
                'instansi.required' => 'Instansi harus diisi!',
                'regional_unit.required' => 'Satuan wilayah harus diisi!',
                'warrant_number.required' => 'Nomor surat perintah harus diisi!',
                'note.required' => 'Keterangan harus diisi!',
            ]);

            SecurityExternal::create([
                'user_id' => $userId,
                'name' => $request->name,
                'instansi' => $request->instansi,
                'regional_unit' => $request->regional_unit,
                'warrant_number' => $request->warrant_number,
                'note' => $request->note,
            ]);
            
            DB::commit();
            Alert::success('Tambah Berhasil', 'Data Keamanan external berhasil dibuat!');
            return redirect()->route('user.worker-sum.index');
            
        } catch (\Throwable $th) {

            DB::rollback();
            Alert::error('Tambah gagal', 'Data Keamanan external gagal dibuat!');
            return redirect()->route('user.worker-sum.index');

        }
    }
    public function edit($securityId){

        $security = SecurityExternal::where('id',$securityId)->first();
        if (!$security) {
            Alert::warning('Warning', 'Data tidak ditemukan!');
            return redirect()->route('user.worker-sum.index');
        }
        $data['security'] = $security;
        return view('user.security-external.edit',$data);
    }

    public function update(Request $request,$securityId){

        try {
            DB::beginTransaction();

            $userId = Auth::guard('web')->user()->id;

            $request->validate([
                'name' => 'required',
                'instansi' => 'required',
                'regional_unit' => 'required',
                'warrant_number' => 'required',
                'note' => 'required',

            ],[
                'name.required' => 'Nama harus diisi!',
                'instansi.required' => 'Instansi harus diisi!',
                'regional_unit.required' => 'Satuan wilayah harus diisi!',
                'warrant_number.required' => 'Nomor surat perintah harus diisi!',
                'note.required' => 'Keterangan harus diisi!',
            ]);

            SecurityExternal::where('id',$securityId)->update([
                'name' => $request->name,
                'instansi' => $request->instansi,
                'regional_unit' => $request->regional_unit,
                'warrant_number' => $request->warrant_number,
                'note' => $request->note,
            ]);
            
            DB::commit();
            Alert::success('Update Berhasil', 'Data Keamanan external berhasil diubah!');
            return redirect()->route('user.worker-sum.index');
            
        } catch (\Throwable $th) {

            DB::rollback();
            Alert::error('Update gagal', 'Data Keamanan external gagal diubah!');
            return redirect()->route('user.worker-sum.index');
        }

    }
    public function destroy($securityId){

        try {
            
            DB::beginTransaction();

            SecurityExternal::where('id',$securityId)->delete();
            
            DB::commit();
            Alert::success('Delete Berhasil', 'Data Keamanan external berhasil dihapus!');
            return redirect()->route('user.worker-sum.index');
            
        } catch (\Throwable $th) {

            DB::rollback();
            Alert::error('Delete gagal', 'Data Keamanan external gagal dihapus!');
            return redirect()->route('user.worker-sum.index');
        }

    }
}
