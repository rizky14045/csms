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

        $user = Auth::guard('web')->user();
        $userId = $user->id;
        $assignableUnits = \App\Services\Unit\UnitScope::assignableUnits($user);
        $filterUnit = $request->get('unit_id');

        $qPerson    = $request->get('q_person', '');
        $qSecurity  = $request->get('q_security', '');
        $qAgreement = $request->get('q_agreement', '');

        $personsQuery = \App\Services\Unit\UnitScope::applyMaster(ResponsiblePerson::query(), $user);
        if ($qPerson) {
            $personsQuery->where(function ($q) use ($qPerson) {
                $q->where('name', 'ILIKE', "%{$qPerson}%")
                  ->orWhere('position', 'ILIKE', "%{$qPerson}%")
                  ->orWhere('work_unit', 'ILIKE', "%{$qPerson}%");
            });
        }

        $securitiesQuery = \App\Services\Unit\UnitScope::applyMaster(SecurityExternal::query(), $user);
        if ($qSecurity) {
            $securitiesQuery->where(function ($q) use ($qSecurity) {
                $q->where('name', 'ILIKE', "%{$qSecurity}%")
                  ->orWhere('instansi', 'ILIKE', "%{$qSecurity}%")
                  ->orWhere('regional_unit', 'ILIKE', "%{$qSecurity}%");
            });
        }

        $agreementsQuery = \App\Services\Unit\UnitScope::applyMaster(AgreementExternal::query(), $user);
        if ($qAgreement) {
            $agreementsQuery->where(function ($q) use ($qAgreement) {
                $q->where('name', 'ILIKE', "%{$qAgreement}%")
                  ->orWhere('instansi', 'ILIKE', "%{$qAgreement}%")
                  ->orWhere('pkt_title', 'ILIKE', "%{$qAgreement}%");
            });
        }

        if ($filterUnit && $assignableUnits->contains('id', (int) $filterUnit)) {
            foreach ([$personsQuery, $securitiesQuery, $agreementsQuery] as $q) {
                $q->where('unit_id', (int) $filterUnit);
            }
        }

        $data['assignableUnits'] = $assignableUnits;
        $data['persons']    = $personsQuery->get();
        $data['securities'] = $securitiesQuery->get();
        $data['agreements'] = $agreementsQuery->get();

        return view('user.worker-sum.index', $data);
    }

}
