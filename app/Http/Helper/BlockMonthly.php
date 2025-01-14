<?php

namespace App\Http\Helper;

use App\Models\MonthlyReport;
use Illuminate\Support\Facades\Auth;

class BlockMonthly
{

    public static function accountTakeOver($monthlyId){

        $userId = Auth::guard('web')->user()->id;
        $report = MonthlyReport::where('user_id', $userId)->where('id',$monthlyId)->where('send_status',0)->first();
        if (!$report){
            return redirect()->route('user.monthly-audit.index');
        }

        return null;
    }
        
}