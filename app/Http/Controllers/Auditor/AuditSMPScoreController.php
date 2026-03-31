<?php

namespace App\Http\Controllers\Auditor;

use App\Http\Controllers\Controller;
use App\Http\Validation\AuditSMPDataValidation;
use App\Models\Auditor;
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

        $this->middleware('can:view.audit.smp.score.auditor')->only(['index', 'show']);
    }

    protected function validator(array $data, $validation, array $messages = [])
    {
        return Validator::make($data, $validation, $messages);
    }

    public function index(){
        $result = $this->auditSMPDataService->getAllAuditData(10, true, ['unit'], auth()->id());
        $data['audits'] = getPaginate($result);
        $data['request'] = request();
        return view('auditor.audit-smp-score.index',$data);
    }

    public function show(AuditSmpData $audit){
        // $audit->load('unit', 'leadAuditor', 'auditors');
        $audit->load('childrenHeader.pernyataan.kriteria.evidence', 'childrenHeader.kriteria.evidence');
        $data['auditData'] = $audit;
        return view('auditor.audit-smp-score.show',$data);
    }

    public function updateEvidence(Request $request, AuditSMPScore $audit_score)
    {
        if ($audit_score->type != 'evidence') {
            Alert::error('Akses Ditolak', 'Data tidak valid untuk diperbarui.');
            return redirect()->back()->with('error', 'Data tidak valid untuk diperbarui.');
        }

        $audit_score->load('auditData');

        $checkAuditor = true;
        if($audit_score->auditData->auditor_lead_id != auth()->id()){
            $checkDataAuditor = Auditor::where([
                ['audit_smp_data_id', $audit_score->auditData->id],
                ['user_id', auth()->id()]
            ])->first();

            if(!$checkDataAuditor){
                $checkAuditor = false;
            }            
        }

        if (!$checkAuditor) {
            Alert::error('Akses Ditolak', 'Anda tidak memiliki izin untuk memperbarui data ini!');
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk memperbarui data ini.');
        }

        if($audit_score->auditData->status != 0){
            return redirect()->back()->with('error', 'Data audit yang sudah selesai tidak dapat dikirim');
        }

         // Validation rules
        $validator = $this->validator($request->all(), AuditSMPDataValidation::rulesForUpdateScoreByAuditor($audit_score->id), AuditSMPDataValidation::messages($audit_score->id));
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $this->auditSMPDataService->updateAuditScore($audit_score, $request->all());

        Alert::success('Update Berhasil', 'Data audit berhasil diperbarui!');
        return redirect()->route('auditor.audit-smp-score.show', $audit_score->auditData->id)->with('success', 'Data audit berhasil diperbarui');

    }

    public function updateAchievement(Request $request, AuditSMPScore $audit_score)
    {
        if ($audit_score->type != 'kriteria') {
            dd("masuk sini");
            Alert::error('Akses Ditolak', 'Data tidak valid untuk diperbarui.');
            return redirect()->back()->with('error', 'Data tidak valid untuk diperbarui.');
        }

        $audit_score->load('auditData');

        $checkAuditor = true;
        if($audit_score->auditData->auditor_lead_id != auth()->id()){
            $checkDataAuditor = Auditor::where([
                ['audit_smp_data_id', $audit_score->auditData->id],
                ['user_id', auth()->id()]
            ])->first();

            if(!$checkDataAuditor){
                $checkAuditor = false;
            }            
        }

        if (!$checkAuditor) {
            dd("masuk sini2");
            Alert::error('Akses Ditolak', 'Anda tidak memiliki izin untuk memperbarui data ini!');
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk memperbarui data ini.');
        }

        if($audit_score->auditData->status != 0){
            dd("masuk sini3");
            Alert::error('Akses Ditolak', 'Data audit yang sudah selesai tidak dapat diperbarui!');
            return redirect()->back()->with('error', 'Data audit yang sudah selesai tidak dapat diperbarui!');
        }

         // Validation rules
        $validator = $this->validator($request->all(), AuditSMPDataValidation::rulesForUpdateAchievementByAuditor($audit_score->id), AuditSMPDataValidation::messages($audit_score->id));
        if ($validator->fails()) {
            dd($validator->errors());
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $this->auditSMPDataService->updateAuditAchievement($audit_score, $request->all());

        Alert::success('Update Berhasil', 'Data audit berhasil diperbarui!');
        return redirect()->route('auditor.audit-smp-score.show', $audit_score->auditData->id)->with('success', 'Data audit berhasil diperbarui');

    }

    public function send(AuditSmpData $audit){
        if($audit->auditor_lead_id != auth()->id()){
            return redirect()->back()->with('error', 'Hanya ketua auditor yang dapat mengirim data audit');
        }

        if($audit->status != 0){
            return redirect()->back()->with('error', 'Data audit yang sudah selesai tidak dapat dikirim');
        }
        $this->auditSMPDataService->sendAuditData($audit);
        
        return redirect()->route('admin.audit-smp-score.index')->with('success', 'Data audit berhasil dikirim');
    }
}
