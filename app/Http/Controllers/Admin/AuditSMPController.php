<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Validation\AuditSMPValidation;
use App\Models\Area;
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

        // $this->middleware('can:view.kpi.area')->only(['index']);
        // $this->middleware('can:create.kpi.area')->only(['create', 'store']);
        // $this->middleware('can:edit.kpi.area')->only(['edit', 'update']);
        // $this->middleware('can:delete.kpi.area')->only(['destroy']);
    }

    protected function validator(array $data, $validation, array $messages = [])
    {
        return Validator::make($data, $validation, $messages);
    }
    
    public function index(){
        $result = $this->auditSMPService->getAllAudit(0, false, ['child']);
        $data['audits'] = getData($result);
        $data['request'] = request();
        return view('admin.audit-smp.index',$data);
    }

    public function create(){
        return view('admin.audit-smp.create');
    }

    public function store(Request $request){
        try {
            // Validation rules
            $validator = $this->validator($request->all(), AuditSMPValidation::rulesForCreate(), AuditSMPValidation::messages());
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $this->auditSMPService->createAudit($request->all());

            Alert::success('Tambah Berhasil', 'Audit SMP berhasil dibuat!');
            return redirect()->route('admin.audit-smp.index');
            
        } catch (\Throwable $th) {
            Alert::error('Tambah Gagal', 'Audit SMP gagal dibuat!');
            return redirect()->route('admin.audit-smp.index');
        }
    }

    public function edit(AuditSMP $audit){
        $data['audit'] = $audit;
        return view('admin.audit-smp.edit',$data);
    }

    public function update(Request $request, AuditSMP $audit){

        try {
            // Validation rules
            $validator = $this->validator($request->all(), AuditSMPValidation::rulesForUpdate(), AuditSMPValidation::messages());
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $this->auditSMPService->updateAudit($audit, $request->all());

            Alert::success('Update Berhasil', 'Audit SMP berhasil diubah!');
            return redirect()->route('admin.audit-smp.index');
            
        } catch (\Throwable $th) {
            Alert::error('Update Gagal', 'Audit SMP gagal diubah!');
            return redirect()->route('admin.audit-smp.index');
        }
    }

    public function destroy(AuditSMP $audit){
        try {
            $this->auditSMPService->deleteAudit($audit);

            Alert::success('Delete Berhasil', 'Audit SMP berhasil dihapus!');
            return redirect()->route('admin.audit-smp.index');
            
        } catch (\Throwable $th) {
            Alert::error('Delete Gagal', 'Audit SMP gagal dihapus!');
            return redirect()->route('admin.audit-smp.index');
        }
    }
    public function createElement($id){
        
        $data['audit'] = $this->auditSMPService->getAuditById($id);
        return view('admin.audit-smp.create-element',$data);
    }
    public function storeElement(Request $request,$auditId){
        try {
            // Validation rules
            $validator = $this->validator($request->all(), AuditSMPValidation::rulesForCreateElement(), AuditSMPValidation::messages());
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $this->auditSMPService->createAuditElement($request->all(),$auditId);

            Alert::success('Tambah Berhasil', 'Audit SMP berhasil dibuat!');
            return redirect()->route('admin.audit-smp.index');
            
        } catch (\Throwable $th) {
            Alert::error('Tambah Gagal', 'Audit SMP gagal dibuat!');
            return redirect()->route('admin.audit-smp.index');
        }
    }
    public function editElement($auditId,$elementId){
        $data['audit'] = $this->auditSMPService->getAuditById($auditId);
        $data['element'] = $this->auditSMPService->getElementById($auditId,$elementId);
        return view('admin.audit-smp.edit-element',$data);
    }
    public function updateElement(Request $request, $auditId,$elementId){

        try {
            // Validation rules
            $validator = $this->validator($request->all(), AuditSMPValidation::rulesForCreateElement(), AuditSMPValidation::messages());
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            $element = $this->auditSMPService->getElementById($auditId,$elementId);
            $this->auditSMPService->updateAuditElement($element, $request->all());

            Alert::success('Update Berhasil', 'Element Audit SMP berhasil diubah!');
            return redirect()->route('admin.audit-smp.index');
            
        } catch (\Throwable $th) {
            Alert::error('Update Gagal', 'Element Audit SMP gagal diubah!');
            return redirect()->route('admin.audit-smp.index');
        }
    }
    public function deleteElement($auditId,$elementId){
        try {
            $element = $this->auditSMPService->getElementById($auditId,$elementId);
            $this->auditSMPService->deleteElement($element);

            Alert::success('Delete Berhasil', 'Audit SMP berhasil dihapus!');
            return redirect()->route('admin.audit-smp.index');
            
        } catch (\Throwable $th) {
            Alert::error('Delete Gagal', 'Audit SMP gagal dihapus!');
            return redirect()->route('admin.audit-smp.index');
        }
    }
    public function createEvident($id){
        
        $data['audit'] = $this->auditSMPService->getAuditById($id);
        return view('admin.audit-smp.create-eviden',$data);
    }
    public function storeEvident(Request $request,$auditId){
        try {
            // Validation rules
            $validator = $this->validator($request->all(), AuditSMPValidation::rulesForCreateEvident(), AuditSMPValidation::messages());
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $this->auditSMPService->createAuditElement($request->all(),$auditId);

            Alert::success('Tambah Berhasil', 'Audit SMP berhasil dibuat!');
            return redirect()->route('admin.audit-smp.index');
            
        } catch (\Throwable $th) {
            Alert::error('Tambah Gagal', 'Audit SMP gagal dibuat!');
            return redirect()->route('admin.audit-smp.index');
        }
    }
}
