<?php

namespace App\Http\Controllers\User;

use App\Models\Security;
use App\Models\Attribute;
use App\Models\SecurityForm;
use Illuminate\Http\Request;
use App\Models\FormAttribute;
use App\Models\MonthlyReport;
use App\Models\Vulnerability;
use App\Models\ReportEmployee;
use App\Models\SecurityProgram;
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
        $data['forms'] = MonthlyReport::latest()->paginate(25);
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
    public function show(){
        return view('user.monthly-audit.show');
    }

}
