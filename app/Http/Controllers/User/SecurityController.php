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
            $validator = $this->validator(
                array_merge($request->all(), ['kta_file' => $request->file('kta_file')]),
                SecurityValidation::rulesForCreate(),
                SecurityValidation::messages()
            );
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $user = auth()->user();
            $data = $request->all();
            $data['user_id'] = $user->id;

            $ktaFile = null;
            if ($request->hasFile('kta_file')) {
                $file = $request->file('kta_file');
                $file_name = 'kta-file-' . time() . '.' . $file->getClientOriginalExtension();
                $uploadPath = public_path('uploads/kta_files/');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0775, true);
                }
                $file->move($uploadPath, $file_name);
                $ktaFile = $file_name;
            }
            $data['kta_file'] = $ktaFile;

            $this->securityService->createSecurity($data);

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
            $validator = $this->validator(
                array_merge($request->all(), ['kta_file' => $request->file('kta_file')]),
                SecurityValidation::rulesForUpdate(),
                SecurityValidation::messages()
            );
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $result = $this->securityService->getSecurityById($security->id, auth()->user()->id);
            $status = getStatus($result);
            if (!$status) {
                return abort(404);
            }

            $data = $request->all();

            $ktaFile = null;
            if ($request->hasFile('kta_file')) {
                $file = $request->file('kta_file');
                $file_name = 'kta-file-' . time() . '.' . $file->getClientOriginalExtension();
                if ($security->kta_file) {
                    $oldPath = public_path('uploads/kta_files/' . $security->kta_file);
                    if (file_exists($oldPath)) {
                        unlink($oldPath);
                    }
                }
                $uploadPath = public_path('uploads/kta_files/');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0775, true);
                }
                $file->move($uploadPath, $file_name);
                $ktaFile = $file_name;
            }
            $data['kta_file'] = $ktaFile ?? $security->kta_file;

            $this->securityService->updateSecurity($security, $data);

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
