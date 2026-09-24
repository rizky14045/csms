<?php

namespace App\Http\Helper;

use App\Models\MonthlyReport;
use Illuminate\Support\Facades\Auth;

class BlockMonthly
{

    public static function accountTakeOver($monthlyId){

        $unitId = Auth::guard('web')->user()->unit_id;
        $report = MonthlyReport::where('unit_id', $unitId)->where('id',$monthlyId)
            ->where('send_status', 0)
            ->where('sent_to_parent', false)
            ->first();
        if (!$report){
            return redirect()->route('user.monthly-audit.index');
        }

        return null;
    }
        
}