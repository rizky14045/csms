<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Validation\CriteriaAuditSMPValidation;
use App\Models\AuditSMP;
use App\Services\AuditSMP\ElementAuditSMPService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class CriteriaAuditSMPController extends Controller
{
    protected $elementAuditSMPService;

    public function __construct(ElementAuditSMPService $elementAuditSMPService)
    {
        $this->elementAuditSMPService = $elementAuditSMPService;

        $this->middleware('can:view.criteria.audit.smp.admin')->only(['index']);
        $this->middleware('can:create.criteria.audit.smp.admin')->only(['create', 'store']);
        $this->middleware('can:edit.criteria.audit.smp.admin')->only(['edit', 'update']);
        $this->middleware('can:delete.criteria.audit.smp.admin')->only(['destroy']);
    }

    protected function validator(array $data, $validation, array $messages = [])
    {
        return Validator::make($data, $validation, $messages);
    }

    public function create(AuditSMP $audit){
        $data['audit'] = $audit;
        return view('admin.audit-smp.create-kriteria',$data);
    }

    public function store(Request $request, AuditSMP $audit){
        // Validation rules
        $validator = $this->validator($request->all(), CriteriaAuditSMPValidation::rulesForCreate(), CriteriaAuditSMPValidation::messages());
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $request->merge([
            'type' => 'kriteria'
        ]);

        $this->elementAuditSMPService->createAuditElement($request->all(), $audit->id);

        Alert::success('Tambah Berhasil', 'Audit SMP berhasil dibuat!');
        return redirect()->route('admin.audit-smp.index');
    }

    public function edit(AuditSMP $kriteria, AuditSMP $audit){
        if($kriteria->parent_id != $audit->id){
            Alert::error('Error', 'Kriteria tidak ditemukan!');
            return redirect()->route('admin.audit-smp.index');
        }

        $data['audit'] = $audit;
        $data['kriteria'] = $kriteria;
        return view('admin.audit-smp.edit-kriteria',$data);
    }

    public function update(Request $request, AuditSMP $kriteria, AuditSMP $audit){
        if($kriteria->parent_id != $audit->id) {
            Alert::error('Error', 'Kriteria tidak ditemukan!');
            return redirect()->route('admin.audit-smp.index');
        }

        // Validation rules
        $validator = $this->validator($request->all(), CriteriaAuditSMPValidation::rulesForUpdate(), CriteriaAuditSMPValidation::messages());
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $this->elementAuditSMPService->updateAuditElement($kriteria, $request->all());

        Alert::success('Update Berhasil', 'Element Audit SMP berhasil diubah!');
        return redirect()->route('admin.audit-smp.index');
    }

    public function delete( AuditSMP $kriteria, AuditSMP $audit){
        if($kriteria->parent_id != $audit->id){
            Alert::error('Error', 'Kriteria tidak ditemukan!');
            return redirect()->route('admin.audit-smp.index');
        }
        
        $this->elementAuditSMPService->deleteElement($kriteria);

        Alert::success('Delete Berhasil', 'Audit SMP berhasil dihapus!');
        return redirect()->route('admin.audit-smp.index');
    }
}
