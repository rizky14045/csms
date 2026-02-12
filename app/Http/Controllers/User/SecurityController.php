<?php

namespace App\Http\Controllers\User;

use App\Models\Security;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Security\SecurityService;
use App\Http\Validation\SecurityValidation;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;

class SecurityController extends Controller
{
    protected $securityService;

    public function __construct(SecurityService $securityService)
    {
        $this->securityService = $securityService;

        $this->middleware('can:view.security.unit')->only(['index']);
        $this->middleware('can:create.security.unit')->only(['create', 'store']);
        $this->middleware('can:edit.security.unit')->only(['edit', 'update']);
        $this->middleware('can:delete.security.unit')->only(['destroy']);
    }

    protected function validator(array $data, $validation, array $messages = [])
    {
        return Validator::make($data, $validation, $messages);
    }

    public function index(){
        $result = $this->securityService->getAllSecurity(25, true, auth()->user()->id);
        $data['securities'] = getPaginate($result);
        $data['request'] = request();
        
        return view('user.security.index',$data);

    }

    public function create(){
        return view('user.security.create');
    }

    public function store(Request $request){

        try {
            // Validation rules
            $validator = $this->validator($request->all(), SecurityValidation::rulesForCreate(), SecurityValidation::messages());
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $user_id = auth()->user()->id;
            $request->merge(['user_id' => $user_id]);
            $this->securityService->createSecurity($request->all());
            
            Alert::success('Tambah Berhasil', 'Satuan Pengamanan berhasil dibuat!');
            return redirect()->route('user.security.index');
            
        } catch (\Throwable $th) {
            Alert::error('Tambah Gagal', 'Satuan Pengamanan gagal dibuat!');
            return redirect()->route('user.security.index');
        }
    }

    public function edit(Security $security){
        $result = $this->securityService->getSecurityById($security->id, auth()->user()->id);
        $status = getStatus($result);
        if(!$status){
            return abort(404);
        }
        
        $data['security'] = $security;

        return view('user.security.edit',$data);
    }

    public function update(Request $request, Security $security){

        try {
             // Validation rules
            $validator = $this->validator($request->all(), SecurityValidation::rulesForUpdate(), SecurityValidation::messages());
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $result = $this->securityService->getSecurityById($security->id, auth()->user()->id);
            $status = getStatus($result);
            if(!$status){
                return abort(404);
            }
            
            $this->securityService->updateSecurity($security, $request->all());

            Alert::success('Update Berhasil', 'Satuan Pengamanan berhasil diubah!');
            return redirect()->route('user.security.index');
            
        } catch (\Throwable $th) {
            Alert::error('Ubah Gagal', 'Satuan Pengamanan gagal diubah!');
            return redirect()->route('user.security.index');
        }
    }

    public function destroy(Security $security){
        
        try {
            $result = $this->securityService->getSecurityById($security->id, auth()->user()->id);
            $status = getStatus($result);
            if(!$status){
                return abort(404);
            }

             $this->securityService->deleteSecurity($security);

            Alert::success('Delete Berhasil', 'Satuan Pengamanan berhasil dihapus!');
            return redirect()->route('user.security.index');
            
        } catch (\Throwable $th) {
            Alert::error('Hapus Gagal', 'Satuan Pengamanan gagal dihapus!');
            return redirect()->route('user.security.index');
        }
    }
}
