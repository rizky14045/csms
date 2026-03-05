<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Models\SecurityProgram;
use App\Http\Controllers\Controller;
use App\Http\Validation\SecurityProgramValidation;
use App\Services\SecurityProgram\SecurityProgramService;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;

class SecurityProgramController extends Controller
{
    protected $securityProgramService;

    public function __construct(SecurityProgramService $securityProgramService)
    {
        $this->securityProgramService = $securityProgramService;

        $this->middleware('can:view.security.program.unit')->only(['index']);
        $this->middleware('can:create.security.program.unit')->only(['create', 'store']);
        $this->middleware('can:edit.security.program.unit')->only(['edit', 'update']);
        $this->middleware('can:delete.security.program.unit')->only(['destroy']);
    }

    protected function validator(array $data, $validation, array $messages = [])
    {
        return Validator::make($data, $validation, $messages);
    }

    public function index(){
        $userId = Auth::user()->id;
        $result = $this->securityProgramService->getAllSecurityProgram(25, true, ['programs'], $userId);
        $data['programs'] = getPaginate($result);
        return view('user.security-program.index',$data);

    }

    public function create(){
        return view('user.security-program.create');
    }

    public function store(Request $request){
        // Validation rules
        $validator = $this->validator($request->all(), SecurityProgramValidation::rulesForCreate(), SecurityProgramValidation::messages());
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $this->securityProgramService->createSecurityProgram($request->all());
            
        Alert::success('Tambah Berhasil', 'Program Keamanan berhasil dibuat!');
        dd("test");
        return redirect()->route('user.security-program.index');
    }

    public function edit(SecurityProgram $program){
        $userId = Auth::user()->id;
        if ($program->user_id !== $userId) {
            abort(403);
        }
        $data['program'] = $program;
        return view('user.security-program.edit',$data);
    }

    public function update(Request $request, SecurityProgram $program){
        $userId = Auth::user()->id;
        if ($program->user_id !== $userId) {
            abort(403);
        }
        // Validation rules
        $validator = $this->validator($request->all(), SecurityProgramValidation::rulesForUpdate(), SecurityProgramValidation::messages());
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $this->securityProgramService->updateSecurityProgram($program, $request->all());
        
        Alert::success('Update Berhasil', 'Program Keamanan berhasil diubah!');
        return redirect()->route('user.security-program.index');
    }

    public function destroy(SecurityProgram $program){
        $userId = Auth::user()->id;
        if ($program->user_id !== $userId) {
            abort(403);
        }

        $this->securityProgramService->deleteSecurityProgram($program);

        Alert::success('Delete Berhasil', 'Program Keamanan berhasil dihapus!');
        return redirect()->route('user.security-program.index');
    }
}
