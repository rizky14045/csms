<?php

namespace App\Http\Controllers\User\MonthlyAudit;

use Illuminate\Http\Request;
use App\Http\Helper\BlockMonthly;
use App\Models\ResponsiblePerson;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class ResponsiblePersonController extends Controller
{
    public function create($monthlyId){

        $data['monthlyId'] = $monthlyId;
        return view('user.monthly-audit.responsible-person.create',$data);

    }
    public function store(Request $request,$monthlyId){

        try {
            DB::beginTransaction();

            $userId = Auth::guard('web')->user()->id;

            $request->validate([
                'name' => 'required',
                'position' => 'required',
                'work_unit' => 'required',


            ],[
                'name.required' => 'Nama harus diisi!',
                'position.required' => 'Jabatan harus diisi!',
                'work_unit.required' => 'Unit kerja harus diisi!',

            ]);

            ResponsiblePerson::create([
                'monthly_report_id' => $monthlyId,
                'user_id' => $userId,
                'name' => $request->name,
                'position' => $request->position,
                'work_unit' => $request->work_unit,
                'main' => $request->main,
                'training_smp' => $request->training_smp,
                'auditor_smp' => $request->auditor_smp,
                'investigation' => $request->investigation,
                'mansrisk' => $request->mansrisk,
                'stackholder_management' => $request->stackholder_management,
                'last_education' => $request->last_education,
                'note' => $request->note,
            ]);
            
            DB::commit();
            Alert::success('Tambah Berhasil', 'Data penanggung jawab keamanan berhasil dibuat!');
            return redirect()->route('user.monthly-audit.worker-sum.index',['monthlyId'=>$monthlyId]);
            
        } catch (\Throwable $th) {

            DB::rollback();
            throw $th;
        }
    }
    public function edit($monthlyId,$personId){

        $person = ResponsiblePerson::where('id',$personId)->where('monthly_report_id', $monthlyId)->first();
        if (!$person) {
            Alert::warning('Warning', 'Data tidak ditemukan!');
            return redirect()->route('user.monthly-audit.worker-sum.index',['monthlyId'=>$monthlyId]);
        }
        $data['monthlyId'] = $monthlyId;
        $data['person'] = $person;
        return view('user.monthly-audit.responsible-person.edit',$data);
    }

    public function update(Request $request, $monthlyId,$personId){

        try {
            DB::beginTransaction();

            $userId = Auth::guard('web')->user()->id;

            $request->validate([
                'name' => 'required',
                'position' => 'required',
                'work_unit' => 'required',


            ],[
                'name.required' => 'Nama harus diisi!',
                'position.required' => 'Posisi harus diisi!',
                'work_unit.required' => 'Unit kerja harus diisi!',

            ]);

            ResponsiblePerson::where('monthly_report_id',$monthlyId)->where('id',$personId)->update([
                'name' => $request->name,
                'position' => $request->position,
                'work_unit' => $request->work_unit,
                'main' => $request->main,
                'training_smp' => $request->training_smp,
                'auditor_smp' => $request->auditor_smp,
                'investigation' => $request->investigation,
                'mansrisk' => $request->mansrisk,
                'stackholder_management' => $request->stackholder_management,
                'last_education' => $request->last_education,
                'note' => $request->note,
            ]);
            
            DB::commit();
            Alert::success('Update Berhasil', 'Data penanggung jawab keamanan berhasil diubah!');
            return redirect()->route('user.monthly-audit.worker-sum.index',['monthlyId'=>$monthlyId]);
            
        } catch (\Throwable $th) {

            DB::rollback();
            throw $th;
        }

    }
    public function destroy($monthlyId,$personId){

        try {
            
            DB::beginTransaction();

            ResponsiblePerson::where('monthly_report_id',$monthlyId)->where('id',$personId)->delete();
            
            DB::commit();
            Alert::success('Delete Berhasil', 'Data penanggung jawab keamanan berhasil dihapus!');
            return redirect()->route('user.monthly-audit.worker-sum.index',['monthlyId'=>$monthlyId]);
            
        } catch (\Throwable $th) {

            DB::rollback();
            throw $th;
        }

    }

}
