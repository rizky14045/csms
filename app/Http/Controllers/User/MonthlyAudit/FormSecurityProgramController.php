<?php

namespace App\Http\Controllers\User\MonthlyAudit;

use Illuminate\Http\Request;
use App\Models\SecurityExternal;
use App\Http\Helper\BlockMonthly;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\MonthlySecurityProgram;
use RealRashid\SweetAlert\Facades\Alert;

class FormSecurityProgramController extends Controller
{
    public function index($monthlyId){

        $data['monthlyId'] = $monthlyId;
        $data['programs'] = MonthlySecurityProgram::with('securityProgram','programs')->where('monthly_report_id',$monthlyId)->get();
        return view('user.monthly-audit.form-security-program',$data);

    }

    public function visual($monthlyId){

        $data['monthlyId'] = $monthlyId;
        $data['items'] = MonthlySecurityProgram::with('securityProgram','programs')->where('monthly_report_id',$monthlyId)->get();
        return view('user.monthly-audit.form-security-program-visual',$data);

    }

   
}
