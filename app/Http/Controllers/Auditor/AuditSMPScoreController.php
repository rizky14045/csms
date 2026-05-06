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

        $this->middleware('can:view.audit.smp.score.auditor')->only(['index']);
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
        $audit->load('auditors');

        $user = auth()->user();

        // cek apakah user adalah lead auditor
        $isLead = $audit->auditor_lead_id == $user->id;

        // cek apakah user ada di auditors
        $isAuditor = $audit->auditors
            ->pluck('id')
            ->contains($user->id);

        // jika bukan lead dan bukan auditor
        if (!$isLead && !$isAuditor) {

            Alert::error(
                'Akses Ditolak',
                'Anda tidak memiliki akses untuk melihat data audit ini.'
            );

            return redirect()
                ->back()
                ->with(
                    'error',
                    'Anda tidak memiliki akses untuk melihat data audit ini.'
                );
        }
        $audit->load('childrenHeader.pernyataan.kriteria.evidence', 'childrenHeader.kriteria.evidence', 'unit');
        $data['auditData'] = $audit;
        return view('auditor.audit-smp-score.show',$data);
    }

    public function updateEvidence(Request $request, AuditSMPScore $audit_score)
    {
        try {

            // =========================
            // VALIDASI TYPE
            // =========================
            if ($audit_score->type != 'evidence') {

                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak valid untuk diperbarui.'
                ], 422);

            }

            // =========================
            // LOAD RELATION
            // =========================
            $audit_score->load('auditData');

            // =========================
            // VALIDASI AUDITOR
            // =========================
            $checkAuditor = true;

            if ($audit_score->auditData->auditor_lead_id != auth()->id()) {

                $checkDataAuditor = Auditor::where([
                    ['audit_smp_data_id', $audit_score->auditData->id],
                    ['user_id', auth()->id()]
                ])->first();

                if (!$checkDataAuditor) {
                    $checkAuditor = false;
                }
            }

            if (!$checkAuditor) {

                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki izin untuk memperbarui data ini.'
                ], 403);

            }

            // =========================
            // VALIDASI STATUS
            // =========================
            if ($audit_score->auditData->status != 2) {

                return response()->json([
                    'success' => false,
                    'message' => 'Data audit yang sudah selesai tidak dapat diperbarui.'
                ], 422);

            }

            // =========================
            // VALIDATION
            // =========================
            $validator = $this->validator(
                $request->all(),
                AuditSMPDataValidation::rulesForUpdateScoreByAuditor($audit_score->id),
                AuditSMPDataValidation::messages($audit_score->id)
            );

            if ($validator->fails()) {

                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);

            }

            // =========================
            // UPDATE
            // =========================
            $this->auditSMPDataService->updateAuditScore(
                $audit_score,
                $request->all()
            );

            $audit_score->refresh();

            // =========================
            // RESPONSE
            // =========================
            return response()->json([
                'success' => true,
                'message' => 'Data audit berhasil diperbarui!',
                'data' => [
                    'id' => $audit_score->id,
                    'temuan' => $audit_score->temuan,
                    'rekomendasi' => $audit_score->rekomendasi,
                    'due_date' => $audit_score->due_date,
                    'pic' => $audit_score->pic,
                ]
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);

        }
    }

    public function updateAchievement(Request $request, AuditSMPScore $audit_score)
    {
        try {

            // =========================
            // VALIDASI TYPE
            // =========================
            if ($audit_score->type != 'kriteria') {

                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak valid untuk diperbarui.'
                ], 422);

            }

            // =========================
            // LOAD RELATION
            // =========================
            $audit_score->load('auditData');

            // =========================
            // VALIDASI AUDITOR
            // =========================
            $checkAuditor = true;

            if ($audit_score->auditData->auditor_lead_id != auth()->id()) {

                $checkDataAuditor = Auditor::where([
                    ['audit_smp_data_id', $audit_score->auditData->id],
                    ['user_id', auth()->id()]
                ])->first();

                if (!$checkDataAuditor) {
                    $checkAuditor = false;
                }

            }

            if (!$checkAuditor) {

                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki izin untuk memperbarui data ini.'
                ], 403);

            }

            // =========================
            // VALIDASI STATUS
            // =========================
            if ($audit_score->auditData->status != 2) {

                return response()->json([
                    'success' => false,
                    'message' => 'Data audit yang sudah selesai tidak dapat diperbarui.'
                ], 422);

            }

            // =========================
            // VALIDATION
            // =========================
            $validator = $this->validator(
                $request->all(),
                AuditSMPDataValidation::rulesForUpdateAchievementByAuditor($audit_score->id),
                AuditSMPDataValidation::messages($audit_score->id)
            );

            if ($validator->fails()) {

                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);

            }

            // =========================
            // UPDATE
            // =========================
            $this->auditSMPDataService->updateAuditAchievement(
                $audit_score,
                $request->all()
            );

            $audit_score->refresh();

            // =========================
            // RESPONSE
            // =========================
            return response()->json([
                'success' => true,
                'message' => 'Achievement berhasil diperbarui!',
                'data' => [
                    'id' => $audit_score->id,
                    'pencapaian_nilai_kriteria' => $audit_score->pencapaian_nilai_kriteria
                ]
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);

        }
    }

    public function send(AuditSmpData $audit){
        if($audit->auditor_lead_id != auth()->id()){
            Alert::error('Akses Ditolak', 'Hanya ketua auditor yang dapat mengirim data audit!');
            return redirect()->back()->with('error', 'Hanya ketua auditor yang dapat mengirim data audit');
        }

        if($audit->status != 2){
            Alert::error('Akses Ditolak', 'Data audit yang sudah selesai tidak dapat dikirim!');
            return redirect()->back()->with('error', 'Data audit yang sudah selesai tidak dapat dikirim');
        }
        $this->auditSMPDataService->sendAuditData($audit, 3);
        
        Alert::success('Data Terkirim', 'Data audit berhasil dikirim!');
        if(auth()->user()->roles[0]->name == 'Pusat') {
            return redirect()->route('admin.audit-smp-score.index')->with('success', 'Data audit berhasil dikirim');
        }elseif(auth()->user()->roles[0]->name == 'Unit') {
            return redirect()->route('user.audit-smp-score.index')->with('success', 'Data audit berhasil dikirim');
        }
    }
}
