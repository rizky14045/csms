<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Models\SecurityExternal;
use App\Http\Controllers\Controller;
use App\Http\Validation\SecurityExternalValidation;
use App\Services\SecurityExternal\SecurityExternalService;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;

class SecurityExternalController extends Controller
{
    protected $securityExternalService;

    public function __construct(SecurityExternalService $securityExternalService)
    {
        $this->securityExternalService = $securityExternalService;

        $this->middleware('can:create.security.external.unit')->only(['create', 'store']);
        $this->middleware('can:edit.security.external.unit')->only(['edit', 'update']);
        $this->middleware('can:delete.security.external.unit')->only(['destroy']);
    }

    protected function validator(array $data, $validation, array $messages = [])
    {
        return Validator::make($data, $validation, $messages);
    }

    public function create(){

        return view('user.security-external.create');

    }
    public function store(Request $request){
        // Validation rules
        $validator = $this->validator($request->all(), SecurityExternalValidation::rulesForCreate(), SecurityExternalValidation::messages());
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $this->securityExternalService->createSecurityExternal($request->all());
        
        Alert::success('Tambah Berhasil', 'Data Keamanan external berhasil dibuat!');
        return redirect()->route('user.worker-sum.index');
    }
    public function edit(SecurityExternal $security){
        if($security->user_id !== auth()->id()){
            Alert::error('Akses Ditolak', 'Anda tidak memiliki akses untuk mengedit data ini!');
            return redirect()->route('user.worker-sum.index');
        }
        $data['security'] = $security;
        return view('user.security-external.edit',$data);
    }

    public function update(Request $request, SecurityExternal $security){
        // Validation rules
        $validator = $this->validator($request->all(), SecurityExternalValidation::rulesForUpdate(), SecurityExternalValidation::messages());
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $this->securityExternalService->updateSecurityExternal($security, $request->all());

        Alert::success('Update Berhasil', 'Data Keamanan external berhasil diubah!');
        return redirect()->route('user.worker-sum.index');

    }
    public function destroy(SecurityExternal $security){
        $this->securityExternalService->deleteSecurityExternal($security);

        Alert::success('Delete Berhasil', 'Data Keamanan external berhasil dihapus!');
        return redirect()->route('user.worker-sum.index');

    }
}
