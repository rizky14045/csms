<?php

namespace App\Http\Controllers\User\MonthlyAudit;

use Illuminate\Http\Request;
use App\Models\SecurityExternal;
use App\Http\Helper\BlockMonthly;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class SecurityExternalController extends Controller
{
    public function create($monthlyId){

        $data['monthlyId'] = $monthlyId;
        return view('user.monthly-audit.security-external.create',$data);

    }
    public function store(Request $request,$monthlyId){

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
                'monthly_report_id' => $monthlyId,
                'user_id' => $userId,
                'name' => $request->name,
                'instansi' => $request->instansi,
                'regional_unit' => $request->regional_unit,
                'warrant_number' => $request->warrant_number,
                'note' => $request->note,
            ]);
            
            DB::commit();
            Alert::success('Tambah Berhasil', 'Data Keamanan external berhasil dibuat!');
            return redirect()->route('user.monthly-audit.worker-sum.index',['monthlyId'=>$monthlyId]);
            
        } catch (\Throwable $th) {

            DB::rollback();
            Alert::error('Tambah gagal', 'Data Keamanan external gagal dibuat!');
            return redirect()->route('user.monthly-audit.worker-sum.index',['monthlyId'=>$monthlyId]);

        }
    }
    public function edit($monthlyId,$securityId){

        $security = SecurityExternal::where('id',$securityId)->where('monthly_report_id', $monthlyId)->first();
        if (!$security) {
            Alert::warning('Warning', 'Data tidak ditemukan!');
            return redirect()->route('user.monthly-audit.worker-sum.index',['monthlyId'=>$monthlyId]);
        }
        $data['monthlyId'] = $monthlyId;
        $data['security'] = $security;
        return view('user.monthly-audit.security-external.edit',$data);
    }

    public function update(Request $request, $monthlyId,$securityId){

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

            SecurityExternal::where('monthly_report_id',$monthlyId)->where('id',$securityId)->update([
                'name' => $request->name,
                'instansi' => $request->instansi,
                'regional_unit' => $request->regional_unit,
                'warrant_number' => $request->warrant_number,
                'note' => $request->note,
            ]);
            
            DB::commit();
            Alert::success('Update Berhasil', 'Data Keamanan external berhasil diubah!');
            return redirect()->route('user.monthly-audit.worker-sum.index',['monthlyId'=>$monthlyId]);
            
        } catch (\Throwable $th) {

            DB::rollback();
            Alert::error('Update gagal', 'Data Keamanan external gagal diubah!');
            return redirect()->route('user.monthly-audit.worker-sum.index',['monthlyId'=>$monthlyId]);
        }

    }
    public function destroy($monthlyId,$securityId){

        try {
            
            DB::beginTransaction();

            SecurityExternal::where('monthly_report_id',$monthlyId)->where('id',$securityId)->delete();
            
            DB::commit();
            Alert::success('Delete Berhasil', 'Data Keamanan external berhasil dihapus!');
            return redirect()->route('user.monthly-audit.worker-sum.index',['monthlyId'=>$monthlyId]);
            
        } catch (\Throwable $th) {

            DB::rollback();
            Alert::error('Delete gagal', 'Data Keamanan external gagal dihapus!');
            return redirect()->route('user.monthly-audit.worker-sum.index',['monthlyId'=>$monthlyId]);
        }

    }
}
