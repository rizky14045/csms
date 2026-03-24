<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Validation\ElementAuditSMPValidation;
use App\Models\AuditSMP;
use App\Services\AuditSMP\ElementAuditSMPService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class ElementAuditSMPController extends Controller
{
    protected $elementAuditSMPService;

    public function __construct(ElementAuditSMPService $elementAuditSMPService)
    {
        $this->elementAuditSMPService = $elementAuditSMPService;

        $this->middleware('can:view.element.audit.smp.admin')->only(['index']);
        $this->middleware('can:create.element.audit.smp.admin')->only(['create', 'store']);
        $this->middleware('can:edit.element.audit.smp.admin')->only(['edit', 'update']);
        $this->middleware('can:delete.element.audit.smp.admin')->only(['destroy']);
    }

    protected function validator(array $data, $validation, array $messages = [])
    {
        return Validator::make($data, $validation, $messages);
    }

    public function create(AuditSMP $audit){
        $data['audit'] = $audit;
        return view('admin.audit-smp.create-element',$data);
    }
    public function store(Request $request, AuditSMP $audit){
        // Validation rules
        $validator = $this->validator($request->all(), ElementAuditSMPValidation::rulesForCreate(), ElementAuditSMPValidation::messages());
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $this->elementAuditSMPService->createAuditElement($request->all(), $audit->id);

        Alert::success('Tambah Berhasil', 'Audit SMP berhasil dibuat!');
        return redirect()->route('admin.audit-smp.index');
    }

    public function edit(AuditSMP $element, AuditSMP $audit){
        $data['audit'] = $audit;
        $data['element'] = $this->elementAuditSMPService->getElementById($audit->id, $element->id);
        if(!$data['element']){
            Alert::error('Error', 'Element tidak ditemukan!');
            return redirect()->route('admin.audit-smp.index');
        }

        if($data['element']->parent_id != $audit->id){
            Alert::error('Error', 'Element tidak ditemukan!');
            return redirect()->route('admin.audit-smp.index');
        }

        return view('admin.audit-smp.edit-element',$data);
    }

    public function update(Request $request, AuditSMP $element, AuditSMP $audit){

        try {
            // Validation rules
            $validator = $this->validator($request->all(), ElementAuditSMPValidation::rulesForUpdate(), ElementAuditSMPValidation::messages());
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $element = $this->elementAuditSMPService->getElementById($audit->id, $element->id);
            if(!$element){
                Alert::error('Error', 'Element tidak ditemukan!');
                return redirect()->route('admin.audit-smp.index');
            }
            if($element->parent_id != $audit->id){
                Alert::error('Error', 'Element tidak ditemukan!');
                return redirect()->route('admin.audit-smp.index');
            }
            $this->elementAuditSMPService->updateAuditElement($element, $request->all());

            Alert::success('Update Berhasil', 'Element Audit SMP berhasil diubah!');
            return redirect()->route('admin.audit-smp.index');
            
        } catch (\Throwable $th) {
            Alert::error('Update Gagal', 'Element Audit SMP gagal diubah!');
            return redirect()->route('admin.audit-smp.index');
        }
    }
    public function delete(AuditSMP $element, AuditSMP $audit){
        if($element->parent_id != $audit->id){
            Alert::error('Error', 'Element tidak ditemukan!');
            return redirect()->route('admin.audit-smp.index');
        }

        $this->elementAuditSMPService->deleteElement($element);

        Alert::success('Delete Berhasil', 'Audit SMP berhasil dihapus!');
        return redirect()->route('admin.audit-smp.index');
    }
}
