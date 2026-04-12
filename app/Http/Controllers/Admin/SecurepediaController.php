<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Validation\SecurepediaValidation;
use App\Models\Securepedia;
use App\Services\Securepedia\SecurepediaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class SecurepediaController extends Controller
{
    protected $securepediaService;

    public function __construct(SecurepediaService $securepediaService)
    {
        $this->securepediaService = $securepediaService;

        $this->middleware('can:view.securepedia.admin')->only(['index']);
        $this->middleware('can:create.securepedia.admin')->only(['create', 'store']);
        $this->middleware('can:edit.securepedia.admin')->only(['edit', 'update']);
        $this->middleware('can:delete.securepedia.admin')->only(['destroy']);
    }

    protected function validator(array $data, $validation, array $messages = [])
    {
        return Validator::make($data, $validation, $messages);
    }

    public function index(){
        $result = $this->securepediaService->getAllSecurepedia(25, true);
        $data['securepedias'] = getPaginate($result);
        $data['request'] = request();
        return view('admin.securepedia.index',$data);
    }

    public function create(){
        return view('admin.securepedia.create');
    }

    public function store(Request $request){
        try {
            // Validation rules
            $validator = $this->validator($request->all(), SecurepediaValidation::rulesForCreate(), SecurepediaValidation::messages());
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $this->securepediaService->createSecurepedia($request);

            Alert::success('Tambah Berhasil', 'Securepedia berhasil dibuat!');
            return redirect()->route('admin.securepedia.index');
            
        } catch (\Throwable $th) {
            Alert::error('Tambah Gagal', 'Securepedia gagal dibuat!');
            return redirect()->route('admin.securepedia.index');
        }
    }

    public function edit(Securepedia $securepedia){
        $data['securepedia'] = $securepedia;
        return view('admin.securepedia.edit',$data);
    }

    public function update(Request $request, Securepedia $securepedia){

        try {
            // Validation rules
            $validator = $this->validator($request->all(), SecurepediaValidation::rulesForUpdate(), SecurepediaValidation::messages());
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $type_attribute = 'Administrasi';
            $request->merge(['type_attribute' => $type_attribute]);
            
            $this->securepediaService->updateSecurepedia($securepedia, $request);

            Alert::success('Update Berhasil', 'Securepedia berhasil diubah!');
            return redirect()->route('admin.securepedia.index');
            
        } catch (\Throwable $th) {
            Alert::error('Update Gagal', 'Securepedia gagal diubah!');
            return redirect()->route('admin.securepedia.index');
        }
    }

    public function destroy(Securepedia $securepedia){
        
        try {
             $this->securepediaService->deleteSecurepedia($securepedia);

            Alert::success('Delete Berhasil', 'Securepedia berhasil dihapus!');
            return redirect()->route('admin.securepedia.index');
            
        } catch (\Throwable $th) {
            Alert::error('Delete Gagal', 'Securepedia gagal dihapus!');
            return redirect()->route('admin.securepedia.index');
        }
    }
}
