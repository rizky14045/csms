<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Validation\EvidenceAuditSMPValidation;
use App\Models\AuditSMP;
use App\Services\AuditSMP\ElementAuditSMPService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class EvidenceAuditSMPController extends Controller
{
    protected $elementAuditSMPService;

    public function __construct(ElementAuditSMPService $elementAuditSMPService)
    {
        $this->elementAuditSMPService = $elementAuditSMPService;

        $this->middleware('can:view.evidence.audit.smp.admin')->only(['index']);
        $this->middleware('can:create.evidence.audit.smp.admin')->only(['create', 'store']);
        $this->middleware('can:edit.evidence.audit.smp.admin')->only(['edit', 'update']);
        $this->middleware('can:delete.evidence.audit.smp.admin')->only(['destroy']);
    }

    protected function validator(array $data, $validation, array $messages = [])
    {
        return Validator::make($data, $validation, $messages);
    }
    
    public function create(AuditSMP $audit){
        $data['audit'] = $audit;
        return view('admin.audit-smp.create-evident',$data);
    }

    public function store(Request $request, AuditSMP $audit){
        // Validation rules
        $validator = $this->validator($request->all(), EvidenceAuditSMPValidation::rulesForCreate(), EvidenceAuditSMPValidation::messages());
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $request->merge([
            'type' => 'evidence'
        ]);

            $this->elementAuditSMPService->createAuditElement($request->all(),$audit->id);

        Alert::success('Tambah Berhasil', 'Audit SMP berhasil dibuat!');
        return redirect()->route('admin.audit-smp.index');
    }

    public function edit(AuditSMP $evidence, AuditSMP $audit){
        if($evidence->parent_id != $audit->id){
            Alert::error('Error', 'Evidence tidak ditemukan!');
            return redirect()->route('admin.audit-smp.index');
        }
        $data['audit'] = $audit;
        $data['evidence'] = $evidence;
        return view('admin.audit-smp.edit-evident',$data);
    }

    public function update(Request $request, AuditSMP $evidence, AuditSMP $audit){
        if($evidence->parent_id != $audit->id){
            Alert::error('Error', 'Evidence tidak ditemukan!');
            return redirect()->route('admin.audit-smp.index');
        }
        // Validation rules
        $validator = $this->validator($request->all(), EvidenceAuditSMPValidation::rulesForUpdate(), EvidenceAuditSMPValidation::messages());
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $this->elementAuditSMPService->updateAuditElement($evidence, $request->all());

        Alert::success('Update Berhasil', 'Element Audit SMP berhasil diubah!');
        return redirect()->route('admin.audit-smp.index');
    }
    public function delete(AuditSMP $evidence, AuditSMP $audit){
        if($evidence->parent_id != $audit->id){
            Alert::error('Error', 'Evidence tidak ditemukan!');
            return redirect()->route('admin.audit-smp.index');
        }
        
        $this->elementAuditSMPService->deleteElement($evidence);

        Alert::success('Delete Berhasil', 'Audit SMP berhasil dihapus!');
        return redirect()->route('admin.audit-smp.index');
    }
}
