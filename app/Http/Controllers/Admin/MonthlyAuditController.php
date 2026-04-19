<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AghtData;
use App\Models\AgreementExternal;
use App\Models\ExternalVulnerability;
use App\Models\ForeignWorker;
use App\Models\FormAttribute;
use App\Models\InternalVulnerability;
use App\Models\LaporanBulananBiaya;
use App\Models\MonthlyAgreementExternal;
use App\Models\MonthlyReport;
use App\Models\MonthlyResponsiblePerson;
use App\Models\MonthlySecurityExternal;
use App\Models\MonthlySecurityProgram;
use App\Models\OutsourceEmployee;
use App\Models\ReportEmployee;
use App\Models\ResponsiblePerson;
use App\Models\SecurityExternal;
use App\Models\SecurityForm;
use Illuminate\Http\Request;

class MonthlyAuditController extends Controller
{
    public function index(){
        $data['forms'] = MonthlyReport::where('send_status',true)->latest()->paginate(25);
        return view('admin.monthly-audit.index',$data);
    }
    
    public function show($monthlyId){

        $data['monthlyId'] = $monthlyId;
        $data['monthlyReport'] = MonthlyReport::where('id', $monthlyId)->select('report_date')->first();
        $data['employee'] = ReportEmployee::where('monthly_report_id', $monthlyId)->first(); 
        $data['outsources'] = OutsourceEmployee::where('monthly_report_id', $monthlyId)->latest()->get();
        $security = SecurityForm::join('securities', 'security_forms.security_id','securities.id')->where('monthly_report_id', $monthlyId);
        $data['securityKomandan'] = (clone $security)->where('securities.position', 'Komandan')->get()->count();
        $data['securityAnggota'] = (clone $security)->where('securities.position', 'Anggota')->get()->count();
        $data['securityChief'] = (clone $security)->where('securities.position', 'Chief')->get()->count();
        $data['security'] = (clone $security)->get()->count();
        $securityExternal = MonthlySecurityExternal::join('security_externals','security_externals.id','monthly_security_externals.security_external_id')->where('monthly_report_id', $monthlyId);
        $data['securityPolri'] = (clone $securityExternal)->where('note', 'Polri')->get()->count();
        $data['securityTNI'] = (clone $securityExternal)->where('note', 'TNI')->get()->count();
        $data['securityExternal'] = (clone $securityExternal)->get()->count();

        $foreign = ForeignWorker::where('monthly_report_id', $monthlyId);
        $data['foreignAhli'] = (clone $foreign)->where('position', 'Tenaga Ahli')->get()->count();
        $data['foreignStaff'] = (clone $foreign)->where('position', 'staff')->get()->count();
        $data['foreign'] = (clone $foreign)->get()->count();

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

            $security->where('gender', 'Pria')->count();

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

        $security->where('gender', 'Wanita')->count();

        $data['totalAll'] = $total;
        $data['totalAllMan'] = $totalMan;
        $data['totalAllWoman'] = $totalWoman;
        

        $data['persons'] = MonthlyResponsiblePerson::with('person')->where('monthly_report_id', $monthlyId)->get();
        $data['securities'] = MonthlySecurityExternal::with('security')->where('monthly_report_id', $monthlyId)->get();
        $data['agreements'] = MonthlyAgreementExternal::with('agreement')->where('monthly_report_id', $monthlyId)->get();
        
        $data['securityForms'] = SecurityForm::with('security')->where('monthly_report_id', $monthlyId)->get();
        
        $data['aghts'] = AghtData::where('monthly_report_id', $monthlyId)->get();

        $attribute = FormAttribute::join('attributes', 'attributes.id', 'form_attributes.attribute_id')
        ->select('form_attributes.*', 'attributes.name', 'attributes.status_ownership', 'attributes.unit', 'attributes.standard_contract')->where('monthly_report_id', $monthlyId);
    
        $data['attributes'] = (clone $attribute)->where('attributes.type_attribute', 'Attribute')->get();
        $data['administrations'] = (clone $attribute)->where('attributes.type_attribute', 'Administrasi')->get();
        $data['saranas'] = (clone $attribute)->where('attributes.type_attribute', 'Sarana')->get();
        $data['foreignWorkers'] = ForeignWorker::where('monthly_report_id', $monthlyId)->get();
        $data['programs'] = MonthlySecurityProgram::with('securityProgram','programs')->where('monthly_report_id',$monthlyId)->get();

        $data['internals'] = InternalVulnerability::with('vulnerability')->where('monthly_report_id', $monthlyId)->get();
        $data['externals'] = ExternalVulnerability::with('vulnerability')->where('monthly_report_id', $monthlyId)->get();
        $dataBiaya = LaporanBulananBiaya::where('monthly_report_id', $monthlyId)
            ->whereIn('type', ['administrasi', 'pemeliharaan'])
            ->get()
            ->map(function ($item) {
                $item->prosentase_penyerapan = $item->jumlah_anggaran != 0
                    ? ($item->penyerapan_anggaran / $item->jumlah_anggaran) * 100
                    : 0;
                return $item;
            })
            ->groupBy('type');

        $data['administrasi'] = $dataBiaya->get('administrasi', collect());
        $data['pemeliharaan'] = $dataBiaya->get('pemeliharaan', collect());
        return view('admin.monthly-audit.show',$data);
    }

}
