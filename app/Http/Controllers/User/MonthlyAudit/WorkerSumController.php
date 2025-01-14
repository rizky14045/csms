<?php

namespace App\Http\Controllers\User\MonthlyAudit;

use Illuminate\Http\Request;
use App\Models\SecurityExternal;
use App\Http\Helper\BlockMonthly;
use App\Models\AgreementExternal;
use App\Models\ResponsiblePerson;
use App\Http\Controllers\Controller;

class WorkerSumController extends Controller
{
    public function index($monthlyId){

        $data['monthlyId'] = $monthlyId;
        $data['persons'] = ResponsiblePerson::where('monthly_report_id', $monthlyId)->get();
        $data['securities'] = SecurityExternal::where('monthly_report_id', $monthlyId)->get();
        $data['agreements'] = AgreementExternal::where('monthly_report_id', $monthlyId)->get();
        return view('user.monthly-audit.worker-sum',$data);
    }

}
