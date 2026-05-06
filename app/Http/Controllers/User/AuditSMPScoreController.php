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

        $this->middleware('can:view.audit.smp.score.unit')->only(['index']);
        $this->middleware('can:edit.audit.smp.score.unit')->only(['edit', 'update']);
    }

    protected function validator(array $data, $validation, array $messages = [])
    {
        return Validator::make($data, $validation, $messages);
    }

    public function index(){
        $result = $this->auditSMPDataService->getAllAuditData(10, true, ['unit', 'getInvalidItemsEvidenceByUnit'], null, auth()->user()->unit_id);
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

        Alert::success('Tambah Berhasil', 'Audit SMP berhasil dibuat!');
        if(auth()->user()->roles[0]->name == 'Pusat'){
            return redirect()->route('admin.audit-smp-score.index');
        }
        return redirect()->route('user.audit-smp-score.index');
    }

    public function show(AuditSmpData $audit){
        if($audit->unit_id != auth()->user()->unit_id) {
            Alert::error('Akses Ditolak', 'Anda tidak memiliki akses untuk melihat data audit ini.');
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk melihat data audit ini.');
        }
        $audit->load('childrenHeader.pernyataan.kriteria.evidence', 'childrenHeader.kriteria.evidence', 'unit');
        $data['auditData'] = $audit;
        return view('user.audit-smp-score.show',$data);
    }

    public function send(AuditSmpData $audit){
        if($audit->status != 0){
            Alert::error('Akses Ditolak', 'Data audit yang sudah selesai tidak dapat dikirim!');
            return redirect()->back()->with('error', 'Data audit yang sudah selesai tidak dapat dikirim');
        }
        $audit->load('getInvalidItemsEvidenceByUnit');
        if($audit->get_invalid_items_evidence_by_unit > 0){
            Alert::error('Akses Ditolak', 'Data audit tidak dapat dikirim karena terdapat item bukti yang belum lengkap!');
            return redirect()->back()->with('error', 'Data audit tidak dapat dikirim karena terdapat item bukti yang belum lengkap');
        }
        
        $this->auditSMPDataService->sendAuditData($audit);
        
        Alert::success('Data Terkirim', 'Data audit berhasil dikirim!');
        return redirect()->route('user.audit-smp-score.index')->with('success', 'Data audit berhasil dikirim');
    }

    public function updateSelfAudit(Request $request, AuditSMPScore $auditScore)
    {
        try {

            $auditScore->load('auditData');

            $audit = $auditScore->auditData;

            // VALIDASI STATUS
            if ($audit->status != 0) {

                return response()->json([
                    'success' => false,
                    'message' => 'Data audit yang sudah selesai tidak dapat diedit'
                ], 422);

            }

            // VALIDATION
            $validator = $this->validator(
                $request->all(),
                AuditSMPDataValidation::rulesForUpdateSelfAudit($auditScore->id),
                AuditSMPDataValidation::messages($auditScore->id)
            );

            if ($validator->fails()) {

                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);

            }

            // UPDATE
            $this->auditSMPDataService->updateSelfAudit(
                $request,
                $auditScore,
                $request->all()
            );

            // AMBIL NILAI TERBARU
            $auditScore->refresh();

            return response()->json([
                'success' => true,
                'message' => 'Data self audit berhasil diperbarui',
                'data' => [
                    'id' => $auditScore->id,
                    'pencapaian_nilai_kriteria_self' => $auditScore->pencapaian_nilai_kriteria_self
                ]
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);

        }
    }

    public function updateEvidence(Request $request, AuditSMPScore $auditScore)
    {
        try {

            $auditScore->load('auditData');

            $audit = $auditScore->auditData;

            // VALIDASI STATUS
            if ($audit->status != 0) {

                return response()->json([
                    'success' => false,
                    'message' => 'Data audit yang sudah selesai tidak dapat diedit'
                ], 422);

            }

            // VALIDATION
            $validator = $this->validator(
                $request->all(),
                AuditSMPDataValidation::rulesForUpdateEvidence($auditScore->id),
                AuditSMPDataValidation::messages($auditScore->id)
            );

            if ($validator->fails()) {

                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);

            }

            // UPDATE FILE
            $this->auditSMPDataService->updateEvidenceAudit(
                $request,
                $auditScore,
                $request->all()
            );

            // REFRESH DATA
            $auditScore->refresh();

            return response()->json([
                'success' => true,
                'message' => 'Data bukti berhasil diperbarui',
                'data' => [
                    'id' => $auditScore->id,
                    'evidence_file' => $auditScore->evidence_file
                ]
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);

        }
    }
}