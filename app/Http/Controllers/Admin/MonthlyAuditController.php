<?php

namespace App\Http\Controllers\Admin;

use App\Models\AghtData;
use App\Models\SecurityForm;
use Illuminate\Http\Request;
use App\Models\ForeignWorker;
use App\Models\FormAttribute;
use App\Models\MonthlyReport;
use App\Models\ReportEmployee;
use App\Models\SecurityExternal;
use App\Models\AgreementExternal;
use App\Models\OutsourceEmployee;
use App\Models\ResponsiblePerson;
use App\Http\Controllers\Controller;
use App\Models\ExternalVulnerability;
use App\Models\InternalVulnerability;
use App\Models\MonthlySecurityProgram;

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
        $securityExternal = SecurityExternal::where('monthly_report_id', $monthlyId);
        $data['securityPolri'] = (clone $securityExternal)->where('note', 'Polri')->get()->count();
        $data['securityTNI'] = (clone $securityExternal)->where('note', 'TNI')->get()->count();
        $data['securityExternal'] = (clone $securityExternal)->get()->count();

        $foreign = ForeignWorker::where('monthly_report_id', $monthlyId);
        $data['foreignAhli'] = (clone $foreign)->where('position', 'Tenaga Ahli')->get()->count();
        $data['foreignStaff'] = (clone $foreign)->where('position', 'staff')->get()->count();
        $data['foreign'] = (clone $foreign)->get()->count();

        $data['persons'] = ResponsiblePerson::where('monthly_report_id', $monthlyId)->get();
        $data['securities'] = SecurityExternal::where('monthly_report_id', $monthlyId)->get();
        $data['agreements'] = AgreementExternal::where('monthly_report_id', $monthlyId)->get();
        
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
        return view('admin.monthly-audit.show',$data);
    }

}
