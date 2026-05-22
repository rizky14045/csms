<?php

namespace App\Http\Controllers\User;

use App\Models\SecurityExternal;
use App\Models\AgreementExternal;
use App\Models\ResponsiblePerson;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkerSumController extends Controller
{
    public function index(Request $request){

        $userId = Auth::guard('web')->user()->id;

        $qPerson    = $request->get('q_person', '');
        $qSecurity  = $request->get('q_security', '');
        $qAgreement = $request->get('q_agreement', '');

        $personsQuery = ResponsiblePerson::where('user_id', $userId);
        if ($qPerson) {
            $personsQuery->where(function ($q) use ($qPerson) {
                $q->where('name', 'ILIKE', "%{$qPerson}%")
                  ->orWhere('position', 'ILIKE', "%{$qPerson}%")
                  ->orWhere('work_unit', 'ILIKE', "%{$qPerson}%");
            });
        }

        $securitiesQuery = SecurityExternal::where('user_id', $userId);
        if ($qSecurity) {
            $securitiesQuery->where(function ($q) use ($qSecurity) {
                $q->where('name', 'ILIKE', "%{$qSecurity}%")
                  ->orWhere('instansi', 'ILIKE', "%{$qSecurity}%")
                  ->orWhere('regional_unit', 'ILIKE', "%{$qSecurity}%");
            });
        }

        $agreementsQuery = AgreementExternal::where('user_id', $userId);
        if ($qAgreement) {
            $agreementsQuery->where(function ($q) use ($qAgreement) {
                $q->where('name', 'ILIKE', "%{$qAgreement}%")
                  ->orWhere('instansi', 'ILIKE', "%{$qAgreement}%")
                  ->orWhere('pkt_title', 'ILIKE', "%{$qAgreement}%");
            });
        }

        $data['persons']    = $personsQuery->get();
        $data['securities'] = $securitiesQuery->get();
        $data['agreements'] = $agreementsQuery->get();

        return view('user.worker-sum.index', $data);
    }

}
