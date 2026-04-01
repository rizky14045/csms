<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Validation\AuditSMPDataValidation;
use App\Models\AuditSmpData;
use App\Services\AuditSMPData\AuditSMPDataService;
use App\Services\Unit\UnitService;
use App\Services\User\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuditSMPScoreController extends Controller
{
    protected $auditSMPDataService, $unitService, $userService;

    public function __construct(AuditSMPDataService $auditSMPDataService, UnitService $unitService, UserService $userService)
    {
        $this->auditSMPDataService = $auditSMPDataService;
        $this->unitService = $unitService;
        $this->userService = $userService;

        $this->middleware('can:view.audit.smp.score.admin')->only(['index', 'show']);
        $this->middleware('can:create.audit.smp.score.admin')->only(['create', 'store']);
        $this->middleware('can:edit.audit.smp.score.admin')->only(['edit', 'update']);
        $this->middleware('can:delete.audit.smp.score.admin')->only(['destroy']);
    }

    protected function validator(array $data, $validation, array $messages = [])
    {
        return Validator::make($data, $validation, $messages);
    }

    public function index(){
        $result = $this->auditSMPDataService->getAllAuditData(10, true, ['unit']);
        $data['audits'] = getPaginate($result);
        $data['request'] = request();
        return view('admin.audit-smp-score.index',$data);
    }

    public function create(){
        $result = $this->unitService->getAllUnit(0, false);
        $data['units_list'] = getData($result);
        $result = $this->userService->getAllUser(0, false, "auditor");
        $data['auditors'] = getData($result);
        return view('admin.audit-smp-score.create',$data);
    }

    public function store(Request $request){
        // Validation rules
        $validator = $this->validator($request->all(), AuditSMPDataValidation::rulesForCreate(), AuditSMPDataValidation::messages());

        $validator->after(function ($validator) use ($request) {
            $lead = $request->lead_auditor_id;
            $members = $request->auditors_ids ?? [];

            if (in_array($lead, $members)) {
                $validator->errors()->add('auditors_ids', 'Ketua auditor tidak boleh menjadi anggota.');
            }
        });

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $this->auditSMPDataService->createAuditData($request->all());

        return redirect()->route('admin.audit-smp-score.index')->with('success', 'Data audit berhasil disimpan');
    }

    public function show(AuditSmpData $audit){
        $audit->load('childrenHeader.pernyataan.kriteria.evidence', 'childrenHeader.kriteria.evidence', 'unit');
        $data['auditData'] = $audit;
        return view('admin.audit-smp-score.show',$data);
    }

    public function edit(AuditSmpData $audit){
        if($audit->status != 0){
            return redirect()->back()->with('error', 'Data audit yang sudah selesai tidak dapat diedit');
        }
        $result = $this->unitService->getAllUnit(0, false);
        $data['units_list'] = getData($result);
        $result = $this->userService->getAllUser(0, false, "auditor");
        $data['auditors'] = getData($result);
        $audit->load('auditors');
        $data['audit'] = $audit;
        return view('admin.audit-smp-score.edit',$data);
    }

    public function update(Request $request, AuditSmpData $audit){
        if($audit->status != 0){
            return redirect()->back()->with('error', 'Data audit yang sudah selesai tidak dapat diedit');
        }
        // Validation rules
        $validator = $this->validator($request->all(), AuditSMPDataValidation::rulesForUpdate(), AuditSMPDataValidation::messages());

        $validator->after(function ($validator) use ($request) {
            $lead = $request->lead_auditor_id;
            $members = $request->auditors_ids ?? [];

            if (in_array($lead, $members)) {
                $validator->errors()->add('auditors_ids', 'Ketua auditor tidak boleh menjadi anggota.');
            }
        });

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $this->auditSMPDataService->updateAuditData($audit, $request->all());

        return redirect()->route('admin.audit-smp-score.index')->with('success', 'Data audit berhasil disimpan');
    }

    public function destroy(AuditSmpData $audit){
        if($audit->status != 0){
            return redirect()->back()->with('error', 'Data audit yang sudah selesai tidak dapat dihapus');
        }
        $this->auditSMPDataService->deleteAuditData($audit);
        return redirect()->route('admin.audit-smp-score.index')->with('success', 'Data audit berhasil dihapus');
    }
}
