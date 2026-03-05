<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Models\AgreementExternal;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Validation\AgreementExternalValidation;
use App\Services\AgreementExternal\AgreementExternalService;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;

class AgreementExternalController extends Controller
{
    protected $agreementExternalService;

    public function __construct(AgreementExternalService $agreementExternalService)
    {
        $this->agreementExternalService = $agreementExternalService;

        $this->middleware('can:create.agreement.external.unit')->only(['create', 'store']);
        $this->middleware('can:edit.agreement.external.unit')->only(['edit', 'update']);
        $this->middleware('can:delete.agreement.external.unit')->only(['destroy']);
    }

    protected function validator(array $data, $validation, array $messages = [])
    {
        return Validator::make($data, $validation, $messages);
    }

    public function create(){

        return view('user.agreement-external.create');

    }
    public function store(Request $request){
        // Validation rules
        $validator = $this->validator($request->all(), AgreementExternalValidation::rulesForCreate(), AgreementExternalValidation::messages());
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $this->agreementExternalService->createAgreementExternal($request->all());

        Alert::success('Tambah Berhasil', 'Data kerja sama external berhasil dibuat!');
        return redirect()->route('user.worker-sum.index');
    }
    public function edit(AgreementExternal $agreement){
        if($agreement->user_id !== auth()->id()){
            abort(404);
        }
        $data['agreement'] = $agreement;
        return view('user.agreement-external.edit',$data);
    }

    public function update(Request $request, AgreementExternal $agreement){
        if($agreement->user_id !== auth()->id()){
            abort(404);
        }
        // Validation rules
        $validator = $this->validator($request->all(), AgreementExternalValidation::rulesForUpdate(), AgreementExternalValidation::messages());
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $this->agreementExternalService->updateAgreementExternal($agreement, $request->all());
        
        Alert::success('Update Berhasil', 'Data kerja sama external berhasil diubah!');
        return redirect()->route('user.worker-sum.index');
    }
    public function destroy(AgreementExternal $agreement){
        if($agreement->user_id !== auth()->id()){
            abort(404);
        }
        $this->agreementExternalService->deleteAgreementExternal($agreement);
        Alert::success('Delete Berhasil', 'Data kerja sama external berhasil dihapus!');
        return redirect()->route('user.worker-sum.index');
    }

}
