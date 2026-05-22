<?php

namespace App\Http\Controllers\User\MonthlyAudit;

use App\Http\Controllers\Controller;
use App\Http\Helper\BlockMonthly;
use App\Models\ForeignWorker;
use App\Models\MonthlyGangguan;
use App\Models\MonthlyReport;
use App\Models\MonthlySecurityExternal;
use App\Models\OutsourceEmployee;
use App\Models\ReportEmployee;
use App\Models\SecurityForm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class FormFormulirController extends Controller
{
    public function index($monthlyId){

        $data['monthlyId'] = $monthlyId;

        $data['monthlyReport'] = MonthlyReport::where('id', $monthlyId)
            ->select('report_date')
            ->first();

        $data['employee'] = ReportEmployee::where('monthly_report_id', $monthlyId)->first();

        $data['outsources'] = OutsourceEmployee::where('monthly_report_id', $monthlyId)
            ->latest()
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Security Internal
        |--------------------------------------------------------------------------
        */
        $securities = SecurityForm::join(
                'securities',
                'securities.id',
                '=',
                'security_forms.security_id'
            )
            ->where('security_forms.monthly_report_id', $monthlyId)
            ->select('securities.*')
            ->get();

        $data['securities'] = $securities;

        /*
        |--------------------------------------------------------------------------
        | Security External
        |--------------------------------------------------------------------------
        */
        $securityExternal = MonthlySecurityExternal::join(
                'security_externals',
                'security_externals.id',
                '=',
                'monthly_security_externals.security_external_id'
            )
            ->where('monthly_security_externals.monthly_report_id', $monthlyId)
            ->select('security_externals.*');

        $data['securityPolri'] = (clone $securityExternal)
            ->where('note', 'Polri')
            ->count();

        $data['securityTNI'] = (clone $securityExternal)
            ->where('note', 'TNI')
            ->count();

        $data['securityExternal'] = (clone $securityExternal)->count();

        /*
        |--------------------------------------------------------------------------
        | Foreign Worker
        |--------------------------------------------------------------------------
        */
        $foreign = ForeignWorker::where('monthly_report_id', $monthlyId);
        $gangguan = MonthlyGangguan::where('monthly_report_id', $monthlyId)->first();
        $data['gangguan'] = $gangguan;
        $data['foreignAhli'] = (clone $foreign)
            ->where('position', 'Tenaga Ahli')
            ->count();

        $data['foreignStaff'] = (clone $foreign)
            ->where('position', 'staff')
            ->count();

        $data['foreign'] = (clone $foreign)->count();

        /*
        |--------------------------------------------------------------------------
        | Total All
        |--------------------------------------------------------------------------
        */
        $total = 
            ($data['employee']->employee_man ?? 0) +
            ($data['employee']->employee_woman ?? 0) +
            ($data['employee']->student_man ?? 0) +
            ($data['employee']->student_woman ?? 0) +
            $data['outsources']->sum('total') +
            $data['securityExternal'] +
            $data['foreign'];

        /*
        |--------------------------------------------------------------------------
        | Total Man
        |--------------------------------------------------------------------------
        */
        $totalMan =
            ($data['employee']->employee_man ?? 0) +
            ($data['employee']->student_man ?? 0) +
            $data['outsources']->sum('man') +

            (clone $securityExternal)
                ->where('note', 'TNI')
                ->where('gender', 'Pria')
                ->count() +

            (clone $securityExternal)
                ->where('note', 'Polri')
                ->where('gender', 'Pria')
                ->count() +

            $securities->where('gender', 'Pria')->count();

        /*
        |--------------------------------------------------------------------------
        | Total Woman
        |--------------------------------------------------------------------------
        */
        $totalWoman =
            ($data['employee']->employee_woman ?? 0) +
            ($data['employee']->student_woman ?? 0) +
            $data['outsources']->sum('woman') +

            (clone $securityExternal)
                ->where('note', 'TNI')
                ->where('gender', 'Wanita')
                ->count() +

            (clone $securityExternal)
                ->where('note', 'Polri')
                ->where('gender', 'Wanita')
                ->count() +

            $securities->where('gender', 'Wanita')->count();

        $data['totalAll'] = $total;
        $data['totalAllMan'] = $totalMan;
        $data['totalAllWoman'] = $totalWoman;

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
                    $man   = (int) ($request->man[$index] ?? 0);
                    $woman = (int) ($request->woman[$index] ?? 0);
                    $outsources[] = [
                        'name'  => $request->name[$index],
                        'man'   => $man,
                        'woman' => $woman,
                        'total' => $man + $woman,
                    ];
                }
                foreach ($outsources as $outsource) {
                    OutsourceEmployee::Create([
                        'monthly_report_id' => $monthlyId,
                        'user_id'           => $userId,
                        'name'              => $outsource['name'],
                        'man'               => $outsource['man'],
                        'woman'             => $outsource['woman'],
                        'total'             => $outsource['total'],
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

    public function updateGangguan(Request $request,$monthlyId){

        try {

            DB::beginTransaction();
            $userId = Auth::guard('web')->user()->id;
            $gangguan = MonthlyGangguan::where('monthly_report_id', $monthlyId)->first();
            $gangguan->kriminal = $request->kriminal;
            $gangguan->politis = $request->politis;
            $gangguan->kebakaran = $request->kebakaran;
            $gangguan->bencana_alam = $request->bencana_alam;
            $gangguan->other = $request->other;
            $gangguan->save();

            DB::commit();
            Alert::success('Update berhasil', 'Data berhasil di update!');
            return redirect()->back();

        } catch (\Throwable $th) {
            //throw $th;
            DB::rollback();
            dd($th);
            Alert::error('Update gagal', 'Data gagal di update!');
            return redirect()->back();
        }
    }


}
