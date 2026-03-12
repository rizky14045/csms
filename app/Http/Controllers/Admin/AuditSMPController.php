<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Validation\AuditSMPValidation;
use App\Models\AuditSMP;
use App\Services\AuditSMP\AuditSMPService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class AuditSMPController extends Controller
{
    protected $auditSMPService;

    public function __construct(AuditSMPService $auditSMPService)
    {
        $this->auditSMPService = $auditSMPService;

        $this->middleware('can:view.audit.smp.admin')->only(['index']);
        $this->middleware('can:create.audit.smp.admin')->only(['create', 'store']);
        $this->middleware('can:edit.audit.smp.admin')->only(['edit', 'update']);
        $this->middleware('can:delete.audit.smp.admin')->only(['destroy']);
    }

    protected function validator(array $data, $validation, array $messages = [])
    {
        return Validator::make($data, $validation, $messages);
    }
    
    public function index(){
        $result = $this->auditSMPService->getAllAudit(0, false, 'header', []);
        $data['audits'] = getData($result);
        $data['request'] = request();
        return view('admin.audit-smp.index',$data);
    }

    public function create(){
        return view('admin.audit-smp.create');
    }

    public function store(Request $request){
        // Validation rules
        $validator = $this->validator($request->all(), AuditSMPValidation::rulesForCreate(), AuditSMPValidation::messages());
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $request->merge([
            'type' => 'header'
        ]);

        $this->auditSMPService->createAudit($request->all());

        Alert::success('Tambah Berhasil', 'Audit SMP berhasil dibuat!');
        return redirect()->route('admin.audit-smp.index');
    }

    public function edit(AuditSMP $audit){
        $data['audit'] = $audit;
        return view('admin.audit-smp.edit',$data);
    }

    public function update(Request $request, AuditSMP $audit){
        // Validation rules
        $validator = $this->validator($request->all(), AuditSMPValidation::rulesForUpdate(), AuditSMPValidation::messages());
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $this->auditSMPService->updateAudit($audit, $request->all());

        Alert::success('Update Berhasil', 'Audit SMP berhasil diubah!');
        return redirect()->route('admin.audit-smp.index');
    }

    public function destroy(AuditSMP $audit){
        $this->auditSMPService->deleteAudit($audit);

        Alert::success('Delete Berhasil', 'Audit SMP berhasil dihapus!');
        return redirect()->route('admin.audit-smp.index');
    }
}
