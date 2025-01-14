<?php

namespace App\Http\Controllers\User\MonthlyAudit;

use Illuminate\Http\Request;
use App\Http\Helper\BlockMonthly;
use App\Models\AgreementExternal;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class AgreementExternalController extends Controller
{
    public function create($monthlyId){

        $data['monthlyId'] = $monthlyId;
        return view('user.monthly-audit.agreement-external.create',$data);

    }
    public function store(Request $request,$monthlyId){

        try {
            DB::beginTransaction();

            $userId = Auth::guard('web')->user()->id;

            $request->validate([
                'regional_unit' => 'required',
                'instansi' => 'required',
                'name' => 'required',
                'pkt_number' => 'required',
                'pkt_title' => 'required',
                'expired_date' => 'required',

            ],[
                'regional_unit.required' => 'Satuan wilayah harus diisi!',
                'instansi.required' => 'Instansi harus diisi!',
                'name.required' => 'Nama harus diisi!',
                'pkt_number.required' => 'Nomor PKT harus diisi!',
                'pkt_title.required' => 'Judul PKT harus diisi!',
                'expired_date.required' => 'Tanggal masa berlaku harus diisi!',
            ]);

            AgreementExternal::create([
                'monthly_report_id' => $monthlyId,
                'user_id' => $userId,
                'regional_unit' => $request->regional_unit,
                'instansi' => $request->instansi,
                'name' => $request->name,
                'pkt_number' => $request->pkt_number,
                'pkt_title' => $request->pkt_title,
                'expired_date' => $request->expired_date,
                'note' => $request->note,
            ]);
            
            DB::commit();
            Alert::success('Tambah Berhasil', 'Data kerja sama external berhasil dibuat!');
            return redirect()->route('user.monthly-audit.worker-sum.index',['monthlyId'=>$monthlyId]);
            
        } catch (\Throwable $th) {

            DB::rollback();
            throw $th;
        }
    }
    public function edit($monthlyId,$agreementId){

        $agreement = AgreementExternal::where('id',$agreementId)->where('monthly_report_id', $monthlyId)->first();
        if (!$agreement) {
            Alert::warning('Warning', 'Data tidak ditemukan!');
            return redirect()->route('user.monthly-audit.worker-sum.index',['monthlyId'=>$monthlyId]);
        }
        $data['monthlyId'] = $monthlyId;
        $data['agreement'] = $agreement;
        return view('user.monthly-audit.agreement-external.edit',$data);
    }

    public function update(Request $request, $monthlyId,$agreementId){

        try {
            DB::beginTransaction();

            $userId = Auth::guard('web')->user()->id;

            $request->validate([
                'regional_unit' => 'required',
                'instansi' => 'required',
                'name' => 'required',
                'pkt_number' => 'required',
                'pkt_title' => 'required',
                'expired_date' => 'required',

            ],[
                'regional_unit.required' => 'Satuan wilayah harus diisi!',
                'instansi.required' => 'Instansi harus diisi!',
                'name.required' => 'Nama harus diisi!',
                'pkt_number.required' => 'Nomor PKT harus diisi!',
                'pkt_title.required' => 'Judul PKT harus diisi!',
                'expired_date.required' => 'Tanggal masa berlaku harus diisi!',
            ]);

            AgreementExternal::where('monthly_report_id',$monthlyId)->where('id',$agreementId)->update([
                'regional_unit' => $request->regional_unit,
                'instansi' => $request->instansi,
                'name' => $request->name,
                'pkt_number' => $request->pkt_number,
                'pkt_title' => $request->pkt_title,
                'expired_date' => $request->expired_date,
                'note' => $request->note,
            ]);
            
            DB::commit();
            Alert::success('Update Berhasil', 'Data kerja sama external berhasil diubah!');
            return redirect()->route('user.monthly-audit.worker-sum.index',['monthlyId'=>$monthlyId]);
            
        } catch (\Throwable $th) {

            DB::rollback();
            throw $th;
        }

    }
    public function destroy($monthlyId,$agreementId){

        try {
            
            DB::beginTransaction();

            AgreementExternal::where('monthly_report_id',$monthlyId)->where('id',$agreementId)->delete();
            
            DB::commit();
            Alert::success('Delete Berhasil', 'Data kerja sama external berhasil dihapus!');
            return redirect()->route('user.monthly-audit.worker-sum.index',['monthlyId'=>$monthlyId]);
            
        } catch (\Throwable $th) {

            DB::rollback();
            throw $th;
        }

    }

}
