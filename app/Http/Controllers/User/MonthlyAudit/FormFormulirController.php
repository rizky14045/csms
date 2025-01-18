<?php

namespace App\Http\Controllers\User\MonthlyAudit;

use Illuminate\Http\Request;
use App\Models\MonthlyReport;
use App\Models\ReportEmployee;
use App\Http\Helper\BlockMonthly;
use App\Models\OutsourceEmployee;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class FormFormulirController extends Controller
{
    public function index($monthlyId){

        $data['monthlyId'] = $monthlyId;
        $data['monthlyReport'] = MonthlyReport::where('id', $monthlyId)->select('report_date')->first();
        $data['employee'] = ReportEmployee::where('monthly_report_id', $monthlyId)->first(); 
        $data['outsources'] = OutsourceEmployee::where('monthly_report_id', $monthlyId)->latest()->get();
        return view('user.monthly-audit.form-formulir',$data);
    }

    public function saveFormulir(Request $request, $monthlyId){

        try {
            DB::beginTransaction();

            $userId = Auth::guard('web')->user()->id;

            $report = ReportEmployee::where('monthly_report_id', $monthlyId)->first();
            $report->employee_man = $request->employee_man;
            $report->employee_woman = $request->employee_woman;
            $report->student_man = $request->student_man;
            $report->student_woman = $request->student_woman;
            $report->save();

            OutsourceEmployee::where('monthly_report_id',$monthlyId)->delete();

            if($request->has('name')){
                $outsources = [];
    
                foreach ($request->name as $index => $unit) {
                    $outsources[] = [
                        'name' => $request->name[$index],
                        'total' => $request->total[$index],
                        'man' => $request->man[$index],
                        'woman' => $request->woman[$index],
                    ];
                }
                foreach ($outsources as $outsource) {
                    OutsourceEmployee::Create([
                        'monthly_report_id' => $monthlyId,
                        'user_id' => $userId,
                        'name' => $outsource['name'],
                        'total' => $outsource['total'],
                        'man' => $outsource['man'],
                        'woman' => $outsource['woman']
                    ]);
                };
            }
            DB::commit();
            Alert::success('Update berhasil', 'Data berhasil di update!');
            return redirect()->back();

        } catch (\Throwable $th) {

            DB::rollback();
            Alert::error('Update gagal', 'Data gagal di update!');
            return redirect()->back();
        }
    }


}
