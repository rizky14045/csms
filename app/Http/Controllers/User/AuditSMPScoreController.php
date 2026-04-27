<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Validation\AuditSMPDataValidation;
use App\Models\AuditSmpData;
use App\Models\AuditSMPScore;
use App\Services\AuditSMPData\AuditSMPDataService;
use App\Services\Unit\UnitService;
use App\Services\User\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class AuditSMPScoreController extends Controller
{
    protected $auditSMPDataService, $unitService, $userService;

    public function __construct(AuditSMPDataService $auditSMPDataService, UnitService $unitService, UserService $userService)
    {
        $this->auditSMPDataService = $auditSMPDataService;
        $this->unitService = $unitService;
        $this->userService = $userService;

        $this->middleware('can:view.audit.smp.score.unit')->only(['index', 'show']);
        $this->middleware('can:edit.audit.smp.score.unit')->only(['edit', 'update']);
    }

    protected function validator(array $data, $validation, array $messages = [])
    {
        return Validator::make($data, $validation, $messages);
    }

    public function index(){
        $result = $this->auditSMPDataService->getAllAuditData(10, true, ['unit'], null, auth()->user()->unit_id);
        $data['audits'] = getPaginate($result);
        $data['request'] = request();
        return view('user.audit-smp-score.index',$data);
    }

    public function create(){
        return view('user.audit-smp-score.create');
    }

    public function store(Request $request){
        // Validation rules
        $validator = $this->validator($request->all(), AuditSMPDataValidation::rulesForCreate(), AuditSMPDataValidation::messages());

        $request->merge(['unit_id' => auth()->user()->unit_id]);
        $this->auditSMPDataService->createAuditData($request->all());

        return redirect()->route('user.audit-smp-score.index')->with('success', 'Data audit berhasil disimpan');
    }

    public function show(AuditSmpData $audit){
        $audit->load('childrenHeader.pernyataan.kriteria.evidence', 'childrenHeader.kriteria.evidence', 'unit');
        $data['auditData'] = $audit;
        return view('user.audit-smp-score.show',$data);
    }

    public function send(AuditSmpData $audit){
        if($audit->status != 0){
            Alert::error('Akses Ditolak', 'Data audit yang sudah selesai tidak dapat dikirim!');
            return redirect()->back()->with('error', 'Data audit yang sudah selesai tidak dapat dikirim');
        }
        $this->auditSMPDataService->sendAuditData($audit);
        
        Alert::success('Data Terkirim', 'Data audit berhasil dikirim!');
        return redirect()->route('user.audit-smp-score.index')->with('success', 'Data audit berhasil dikirim');
    }

    public function updateSelfAudit(Request $request, AuditSMPScore $auditScore){
        $auditScore->load('auditData');
        $audit = $auditScore->auditData;
        if($audit->status != 0){
            return redirect()->back()->with('error', 'Data audit yang sudah selesai tidak dapat diedit');
        }
        // Validation rules
        $validator = $this->validator($request->all(), AuditSMPDataValidation::rulesForUpdateSelfAudit($auditScore->id), AuditSMPDataValidation::messages($auditScore->id));

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $this->auditSMPDataService->updateSelfAudit($request, $auditScore, $request->all());

        Alert::success('Success', 'Data bukti berhasil diperbarui');
        return redirect()->route('user.audit-smp-score.show', ['audit' => $audit->id])->with('success', 'Data audit berhasil disimpan');
    }

    public function updateEvidence(Request $request, AuditSMPScore $auditScore){
        $auditScore->load('auditData');
        $audit = $auditScore->auditData;
        if($audit->status != 0){
            return redirect()->back()->with('error', 'Data audit yang sudah selesai tidak dapat diedit');
        }
        // Validation rules
        $validator = $this->validator($request->all(), AuditSMPDataValidation::rulesForUpdateEvidence($auditScore->id), AuditSMPDataValidation::messages($auditScore->id));

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $this->auditSMPDataService->updateEvidenceAudit($request, $auditScore, $request->all());

        Alert::success('Success', 'Data bukti berhasil diperbarui');
        return redirect()->route('user.audit-smp-score.show', ['audit' => $audit->id])->with('success', 'Data audit berhasil disimpan');
    }
}