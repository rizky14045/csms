<?php

namespace App\Http\Controllers;

use App\Exports\FormFormulirExport;
use App\Exports\WorkerSumExport;
use App\Models\ForeignWorker;
use App\Models\MonthlyAgreementExternal;
use App\Models\MonthlyGangguan;
use App\Models\MonthlyReport;
use App\Models\MonthlyResponsiblePerson;
use App\Models\MonthlySecurityExternal;
use App\Models\OutsourceEmployee;
use App\Models\ReportEmployee;
use App\Models\SecurityForm;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class MonthlyAuditExportController extends Controller
{
    public function exportFormFormulir($monthlyId)
    {
        // ambil data (copy dari index kamu)
        $data['monthlyId'] = $monthlyId;

        $data['monthlyReport'] = MonthlyReport::where('id', $monthlyId)
            ->select('report_date')
            ->first();

        $data['employee'] = ReportEmployee::where('monthly_report_id', $monthlyId)->first();

        $data['outsources'] = OutsourceEmployee::where('monthly_report_id', $monthlyId)->get();

        $securities = SecurityForm::join('securities','securities.id','=','security_forms.security_id')
            ->where('security_forms.monthly_report_id', $monthlyId)
            ->select('securities.*')
            ->get();

        $data['securities'] = $securities;

        $securityExternal = MonthlySecurityExternal::join(
            'security_externals',
            'security_externals.id',
            '=',
            'monthly_security_externals.security_external_id'
        )->where('monthly_security_externals.monthly_report_id', $monthlyId);

        $data['securityPolri'] = (clone $securityExternal)->where('note','Polri')->count();
        $data['securityTNI'] = (clone $securityExternal)->where('note','TNI')->count();
        $data['securityExternal'] = (clone $securityExternal)->count();

        $foreign = ForeignWorker::where('monthly_report_id', $monthlyId);

        $data['foreignAhli'] = (clone $foreign)->where('position','Tenaga Ahli')->count();
        $data['foreignStaff'] = (clone $foreign)->where('position','staff')->count();
        $data['foreign'] = (clone $foreign)->count();

        $data['gangguan'] = MonthlyGangguan::where('monthly_report_id', $monthlyId)->first();

        $data['totalAll'] =
            ($data['employee']->employee_man ?? 0) +
            ($data['employee']->employee_woman ?? 0) +
            ($data['employee']->student_man ?? 0) +
            ($data['employee']->student_woman ?? 0) +
            $data['outsources']->sum('total') +
            $data['securityExternal'] +
            $data['foreign'];

        $data['totalAllMan'] =
            ($data['employee']->employee_man ?? 0) +
            ($data['employee']->student_man ?? 0) +
            $data['outsources']->sum('man');

        $data['totalAllWoman'] =
            ($data['employee']->employee_woman ?? 0) +
            ($data['employee']->student_woman ?? 0) +
            $data['outsources']->sum('woman');

        return Excel::download(new FormFormulirExport($data), 'Form-Formulir.xlsx');
    }
    public function exportWorkerSum($monthlyId)
    {
        $monthly = MonthlyReport::findOrFail($monthlyId);
        
        // Pastikan relasi 'person', 'security', dan 'agreement' ada di database
        $data['persons'] = MonthlyResponsiblePerson::with('person')
            ->whereHas('person') 
            ->where('monthly_report_id', $monthlyId)
            ->get();
            
        $data['securities'] = MonthlySecurityExternal::with('security')
            ->whereHas('security')
            ->where('monthly_report_id', $monthlyId)
            ->get();
            
        $data['agreements'] = MonthlyAgreementExternal::with('agreement')
            ->whereHas('agreement')
            ->where('monthly_report_id', $monthlyId)
            ->get();

        $html = view('exports.monthly-audit.worker-sum-excel', $data)->render();
        return response($html)->header('Content-Type', 'text/plain');
    }
}
