<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Models\SecurityExternal;
use App\Http\Helper\BlockMonthly;
use App\Models\AgreementExternal;
use App\Models\ResponsiblePerson;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class WorkerSumController extends Controller
{
    public function index(){

        $userId = Auth::guard('web')->user()->id;
        $data['persons'] = ResponsiblePerson::where('user_id', $userId)->get();
        $data['securities'] = SecurityExternal::where('user_id', $userId)->get();
        $data['agreements'] = AgreementExternal::where('user_id', $userId)->get();
        return view('user.worker-sum.index',$data);
    }

}
