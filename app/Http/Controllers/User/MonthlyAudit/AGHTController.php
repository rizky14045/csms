<?php

namespace App\Http\Controllers\User\MonthlyAudit;

use App\Models\AghtData;
use Illuminate\Http\Request;
use App\Models\MonthlyReport;
use App\Http\Helper\BlockMonthly;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class AGHTController extends Controller
{
    public function index($monthlyId){

        $data['monthlyId'] = $monthlyId;
        $data['aghts'] = AghtData::where('monthly_report_id', $monthlyId)->get();
        return view('user.monthly-audit.aght',$data);
    }

    public function create($monthlyId){

        $data['monthlyId'] = $monthlyId;
        return view('user.monthly-audit.aght.create',$data);
    }
    public function store(Request $request,$monthlyId){

        try {
            DB::beginTransaction();

            $userId = Auth::guard('web')->user()->id;

            $request->validate([
                'activity' => 'required',
                'incident_date' => 'required',
                'incident_time' => 'required',
                'incident_location' => 'required',
                'loss' => 'required',
                'after_incident' => 'required',
                'officer_contacted' => 'required',

            ],[
                'activity.required' => 'Uraian kegiatan harus diisi!',
                'incident_date.required' => 'Tanggal kejadian perkara harus diisi!',
                'incident_time.required' => 'Waktu kejadian perkara harus diisi!',
                'incident_location.required' => 'Tempat kejadian perkara harus diisi!',
                'loss.required' => 'Kerugian akibat yang ditimbulkan harus diisi!',
                'after_incident.required' => 'Tindakan pengamanan setelah kejadian harus diisi!',
                'officer_contacted.required' => 'Aparat yang dihubungi harus diisi!',

            ]);

            AghtData::create([
                'monthly_report_id' => $monthlyId,
                'user_id' => $userId,
                'activity' => $request->activity,
                'incident_date' => $request->incident_date,
                'incident_time' => $request->incident_time,
                'incident_location' => $request->incident_location,
                'loss' => $request->loss,
                'after_incident' => $request->after_incident,
                'officer_contacted' => $request->officer_contacted,
                'note' => $request->note
            ]);
            
            DB::commit();
            Alert::success('Tambah Berhasil', 'Data AGHT berhasil dibuat!');
            return redirect()->route('user.monthly-audit.aght.index',['monthlyId'=>$monthlyId]);
            
        } catch (\Throwable $th) {

            DB::rollback();
            throw $th;
        }
    }

    public function edit($monthlyId,$aghtId){

        $aght = AghtData::where('id',$aghtId)->where('monthly_report_id', $monthlyId)->first();
        if (!$aght) {
            Alert::warning('Warning', 'Data tidak ditemukan!');
            return redirect()->route('user.monthly-audit.aght.index',['monthlyId'=>$monthlyId]);
        }
        $data['monthlyId'] = $monthlyId;
        $data['aght'] = $aght;
        return view('user.monthly-audit.aght.edit',$data);
    }

    public function update(Request $request,$monthlyId,$aghtId){

        try {

            DB::beginTransaction();

            $request->validate([
                'activity' => 'required',
                'incident_date' => 'required',
                'incident_time' => 'required',
                'incident_location' => 'required',
                'loss' => 'required',
                'after_incident' => 'required',
                'officer_contacted' => 'required',

            ],[
                'activity.required' => 'Uraian kegiatan harus diisi!',
                'incident_date.required' => 'Tanggal kejadian perkara harus diisi!',
                'incident_time.required' => 'Waktu kejadian perkara harus diisi!',
                'incident_location.required' => 'Tempat kejadian perkara harus diisi!',
                'loss.required' => 'Kerugian akibat yang ditimbulkan harus diisi!',
                'after_incident.required' => 'Tindakan pengamanan setelah kejadian harus diisi!',
                'officer_contacted.required' => 'Aparat yang dihubungi harus diisi!',

            ]);

            $aght = AghtData::where('id',$aghtId)->where('monthly_report_id', $monthlyId)->first();
            if (!$aght) {
                Alert::warning('Warning', 'Data tidak ditemukan!');
                return redirect()->route('user.monthly-audit.aght.index',['monthlyId'=>$monthlyId]);
            }

            $aght->activity = $request->activity;
            $aght->incident_date = $request->incident_date;
            $aght->incident_time = $request->incident_time;
            $aght->incident_location = $request->incident_location;
            $aght->loss = $request->loss;
            $aght->after_incident = $request->after_incident;
            $aght->officer_contacted = $request->officer_contacted;
            $aght->note = $request->note;
            $aght->save();
         
            
            DB::commit();
            Alert::success('Update Berhasil', 'Data AGHT berhasil diubah!');
            return redirect()->route('user.monthly-audit.aght.index',['monthlyId'=>$monthlyId]);
            
        } catch (\Throwable $th) {

            DB::rollback();
            throw $th;
        }
    }

    public function destroy($monthlyId,$aghtId){

        try {
            DB::beginTransaction();

            $aght = AghtData::where('id',$aghtId)->where('monthly_report_id', $monthlyId)->first();
            if (!$aght) {
                Alert::warning('Warning', 'Data tidak ditemukan!');
                return redirect()->route('user.monthly-audit.aght.index',['monthlyId'=>$monthlyId]);
            }
            $aght->delete();
         
            
            DB::commit();
            Alert::success('Delete Berhasil', 'Data AGHT berhasil dihapus!');
            return redirect()->route('user.monthly-audit.aght.index',['monthlyId'=>$monthlyId]);
            
        } catch (\Throwable $th) {

            DB::rollback();
            throw $th;
        }
    }

}
