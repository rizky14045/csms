<?php

namespace App\Http\Controllers\User;

use App\Models\AghtData;
use App\Models\Security;
use App\Models\Attribute;
use App\Models\SecurityForm;
use Illuminate\Http\Request;
use App\Models\ForeignWorker;
use App\Models\FormAttribute;
use App\Models\MonthlyReport;
use App\Models\Vulnerability;
use App\Models\ReportEmployee;
use App\Models\SecurityProgram;
use App\Models\SecurityExternal;
use App\Models\AgreementExternal;
use App\Models\OutsourceEmployee;
use App\Models\ResponsiblePerson;
use Illuminate\Support\Facades\DB;
use App\Models\MainSecurityProgram;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\ExternalVulnerability;
use App\Models\InternalVulnerability;
use App\Models\MonthlySecurityProgram;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\MonthlyMainSecurityProgram;

class MonthlyAuditController extends Controller
{
    public function index(){
        $userId = Auth::user()->id;
        $data['forms'] = MonthlyReport::where('user_id',$userId)->latest()->paginate(25);
        return view('user.monthly-audit.index',$data);
    }

    public function create(){
        return view('user.monthly-audit.create');
    }
    
    public function store(Request $request){

        try {
            DB::beginTransaction();
            
            $userId = Auth::user()->id;
            $date = explode("-",$request->report_date);
            $administrations = Attribute::select('id')->where('type_attribute','Administrasi')->get();
            $attributes = Attribute::select('id')->where('user_id',$userId)->get();
            $securities = Security::select('id')->where('user_id',$userId)->get();
            $externals = Vulnerability::select('id')->where('type','eksternal')->get();
            $internals = Vulnerability::select('id')->where('type','internal')->get();
            $programs = SecurityProgram::where('user_id',$userId)->where('year',$date[0])->get();

            $report = MonthlyReport::create([
                'user_id' => $userId,
                'report_date' => $request->report_date,
                'send_status' => false,
            ]);

            ReportEmployee::create([
                'monthly_report_id' => $report->id,
                'user_id' => $userId,
            ]);
            if ($administrations) {
                foreach ($administrations as $administration) {
                    FormAttribute::create([
                        'monthly_report_id' => $report->id,
                        'user_id' => $userId,
                        'attribute_id' => $administration->id
                    ]);
                }
            }
            if ($attributes) {
                foreach ($attributes as $attribute) {
                    FormAttribute::create([
                        'monthly_report_id' => $report->id,
                        'user_id' => $userId,
                        'attribute_id' => $attribute->id
                    ]);
                }
            }
            if ($securities) {
                foreach ($securities as $security) {
                    SecurityForm::create([
                        'monthly_report_id' => $report->id,
                        'user_id' => $userId,
                        'security_id' => $security->id
                    ]);
                }
            }
            if ($externals) {
                foreach ($externals as $external) {
                    ExternalVulnerability::create([
                        'monthly_report_id' => $report->id,
                        'user_id' => $userId,
                        'vulnerability_id' => $external->id
                    ]);
                }
            }
            if ($internals) {
                foreach ($internals as $internal) {
                    InternalVulnerability::create([
                        'monthly_report_id' => $report->id,
                        'user_id' => $userId,
                        'vulnerability_id' => $internal->id
                    ]);
                }
            }  
           
            if ($programs) {
               
                foreach($programs as $program) {
                    $monthlyProgram = MonthlySecurityProgram::create([
                        'monthly_report_id' => $report->id,
                        'user_id' => $userId,
                        'program_id' => $program->id
                    ]);
                    $mainPrograms = MainSecurityProgram::where('program_id', $program->id)->where('user_id', $userId)->get();
                    foreach ($mainPrograms as $item) {
                        MonthlyMainSecurityProgram::create([
                            'monthly_report_id' => $report->id,
                            'user_id' => $userId,
                            'monthly_program_id' => $monthlyProgram->id,
                            'program_id' => $program->id,
                            'main_program_id' => $item->id
                        ]);
                    }
                    
                }
            }

            DB::commit();
            Alert::success('Tambah Berhasil', 'Laporan bulanan berhasil dibuat!');
            return redirect()->route('user.monthly-audit.index');
            
        } catch (\Throwable $th) {

            DB::rollback();
            throw $th;
        }
    }

    public function edit(){
        return view('user.monthly-audit.edit');
    }

    public function sendReport($monthlyId){

        try {

            DB::beginTransaction();
            $report = MonthlyReport::where('id', $monthlyId)->first();
            $report->send_status = true;
            $report->send_date = now();
            $report->save();

            DB::commit();
            Alert::success('Berhasil Dikirim', 'Laporan bulanan berhasil dikirm!');
            return redirect()->route('user.monthly-audit.index');
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
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
        return view('user.monthly-audit.show',$data);
    }


}
