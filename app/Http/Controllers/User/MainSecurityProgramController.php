<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Models\SecurityProgram;
use App\Models\MainSecurityProgram;
use App\Http\Controllers\Controller;
use App\Http\Validation\MainSecurityProgramValidation;
use App\Services\MainSecurityProgram\MainSecurityProgramService;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;

class MainSecurityProgramController extends Controller
{
    protected $mainSecurityProgramService;

    public function __construct(MainSecurityProgramService $mainSecurityProgramService)
    {
        $this->mainSecurityProgramService = $mainSecurityProgramService;

        $this->middleware('can:view.main.security.program.unit')->only(['index']);
        $this->middleware('can:create.main.security.program.unit')->only(['create', 'store']);
        $this->middleware('can:edit.main.security.program.unit')->only(['edit', 'update']);
        $this->middleware('can:delete.main.security.program.unit')->only(['destroy']);
    }

    protected function validator(array $data, $validation, array $messages = [])
    {
        return Validator::make($data, $validation, $messages);
    }

    public function index(SecurityProgram $program){
        if($program->user_id != auth()->id()){
            abort(404);
        }

        $userId = Auth::user()->id;
        $data['programId'] = $program->id;
        $result = $this->mainSecurityProgramService->getAllMainSecurityProgram(25, true, [], $userId, $program->id);
        $data['mains'] = getPaginate($result);

        return view('user.main-security-program.index',$data);

    }

    public function create(SecurityProgram $program){
        $data['programId'] = $program->id;
        return view('user.main-security-program.create',$data);

    }

    public function store(Request $request, SecurityProgram $program){
        // Validation rules
        $validator = $this->validator($request->all(), MainSecurityProgramValidation::rulesForCreate(), MainSecurityProgramValidation::messages());
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $this->mainSecurityProgramService->createMainSecurityProgram($request->all(), $program->id);
            
        Alert::success('Tambah Berhasil', 'Program Keamanan berhasil dibuat!');
        return redirect()->route('user.main-security-program.index',['program' => $program->id]);
    }

    public function edit(SecurityProgram $program, MainSecurityProgram $main){

        $userId = Auth::user()->id;

        if($program->user_id != $userId){
            abort(404);
        }

        if($main->user_id != $userId || $main->program_id != $program->id){
            abort(404);
        }
        
        $data['main'] = $main;
        $data['programId'] = $program->id;
        return view('user.main-security-program.edit',$data);
    }

    public function update(Request $request, SecurityProgram $program, MainSecurityProgram $main){
        // Validation rules
        $validator = $this->validator($request->all(), MainSecurityProgramValidation::rulesForUpdate(), MainSecurityProgramValidation::messages());
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $this->mainSecurityProgramService->updateMainSecurityProgram($main, $request->all());
        
            
        Alert::success('Update Berhasil', 'Program Keamanan berhasil diubah!');
        return redirect()->route('user.main-security-program.index',['program'=>$program->id]);
    }

    public function destroy(SecurityProgram $program, MainSecurityProgram $main){
        $userId = Auth::user()->id;

        if($program->user_id != $userId){
            abort(404);
        }

        if($main->user_id != $userId || $main->program_id != $program->id){
            abort(404);
        }

        $this->mainSecurityProgramService->deleteMainSecurityProgram($main);

        Alert::success('Delete Berhasil', 'Program Keamanan berhasil dihapus!');
        return redirect()->route('user.main-security-program.index',['program'=>$program->id]);
    }

    public function visual(SecurityProgram $program){
        if($program->user_id != auth()->id()){
            abort(404);
        }

        $userId = Auth::user()->id;
        $data['programId'] = $program->id;
        $data['securityProgram'] = $program;
        $result = $this->mainSecurityProgramService->getAllMainSecurityProgram(0, false, [], $userId, $program->id);
        $data['programs'] = getData($result);

        return view('user.main-security-program.visual',$data);
    }
}
