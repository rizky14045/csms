<?php

namespace App\Http\Controllers\User\MonthlyAudit;

use Illuminate\Http\Request;
use App\Models\SecurityProgram;
use App\Models\SecurityExternal;
use App\Http\Helper\BlockMonthly;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\MonthlySecurityProgram;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\MonthlyMainSecurityProgram;

class RealizationProgramController extends Controller
{
    public function index($monthlyId,$programId){

        $data['monthlyId'] = $monthlyId;
        $data['programId'] = $programId;
        $data['mains'] = MonthlyMainSecurityProgram::with('mainProgram')->where('monthly_report_id',$monthlyId)->where('monthly_program_id',$programId)->get();
        return view('user.monthly-audit.realization-program.index',$data);

    }

    public function edit($monthlyId,$programId,$mainId){

        $data['monthlyId'] = $monthlyId;
        $data['programId'] = $programId;
        $data['main'] = MonthlyMainSecurityProgram::where('monthly_program_id',$programId)->where('id',$mainId)->first();
        return view('user.monthly-audit.realization-program.edit',$data);

    }

    public function update(Request $request,$monthlyId,$programId,$mainId){

        try {

            DB::beginTransaction();

            $userId = Auth::user()->id;
            $request->validate([
                'start_month' => 'required',
                'start_week' => 'required',
                'end_month' => 'required',
                'end_week' => 'required',
         
            ],[
                'start_month.required' => 'Bulan Planning kerja harus diisi!',
                'start_week.required' => 'Minggu Planning harus diisi!',
                'end_month.required' => 'Bulan Planning kerja harus diisi!',
                'end_week.required' => 'Minggu Planning harus diisi!',
            ]);


            $program = MonthlyMainSecurityProgram::where('monthly_program_id',$programId)->where('id',$mainId)->first();;
            $program->start_month = $request->start_month;
            $program->start_week = $request->start_week;
            $program->end_month = $request->end_month;
            $program->end_week = $request->end_week;
            $program->note = $request->note;
            $program->save();
            
            DB::commit();
            Alert::success('Update Berhasil', 'Program Keamanan berhasil diubah!');
            return redirect()->route('user.monthly-audit.realization-program.index',['monthlyId' => $monthlyId,'programId'=>$programId]);
            
        } catch (\Throwable $th) {

            DB::rollback();
            Alert::error('Update Gagal', 'Program Keamanan gagal diubah!');
            return redirect()->route('user.monthly-audit.realization-program.index',['monthlyId' => $monthlyId,'programId'=>$programId]);
        }
    }

    public function visual($monthlyId,$programId){

        $data['monthlyId'] = $monthlyId;
        $data['programId'] = $programId;
        $data['securityProgram'] = SecurityProgram::where('id',$programId)->first();
        $data['programs'] = MonthlyMainSecurityProgram::with('mainProgram')->where('monthly_program_id',$programId)->get();
        return view('user.monthly-audit.realization-program.visual',$data);

    }

   
}
