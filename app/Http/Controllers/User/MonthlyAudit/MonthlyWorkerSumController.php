<?php

namespace App\Http\Controllers\User\MonthlyAudit;

use Illuminate\Http\Request;
use App\Http\Helper\BlockMonthly;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\MonthlySecurityExternal;
use App\Models\MonthlyAgreementExternal;
use App\Models\MonthlyResponsiblePerson;

class MonthlyWorkerSumController extends Controller
{
    public function index($monthlyId){

        $userId = Auth::guard('web')->user()->id;
        $data['persons'] = MonthlyResponsiblePerson::with('person')->where('monthly_report_id',$monthlyId)->where('user_id', $userId)->get();
        $data['securities'] = MonthlySecurityExternal::with('security')->where('monthly_report_id',$monthlyId)->where('user_id', $userId)->get();
        $data['agreements'] = MonthlyAgreementExternal::with('agreement')->where('monthly_report_id',$monthlyId)->where('user_id', $userId)->get();
        $data['monthlyId']=$monthlyId;
        return view('user.monthly-audit.worker-sum',$data);
    }

}
