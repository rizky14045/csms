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
        if(!\App\Services\Unit\UnitScope::canAccess($program, auth()->user())){
            abort(404);
        }

        $userId = Auth::user()->id;
        $result = $this->mainSecurityProgramService->getAllMainSecurityProgram(0, false, [], $userId, $program->id);

        $data['programId'] = $program->id;
        $data['securityProgram'] = $program;
        $data['months'] = MainSecurityProgram::MONTHS;
        $data['weeks'] = MainSecurityProgram::WEEKS;
        $data['rows'] = collect(getData($result) ?? [])->map(fn($row) => [
            'id'    => $row['id'],
            'name'  => $row['program_name'],
            'cells' => MainSecurityProgram::cellsFor($row),
        ])->values();

        return view('user.main-security-program.index',$data);

    }

    public function create(SecurityProgram $program){
        $data['programId'] = $program->id;
        return view('user.main-security-program.create',$data);

    }

    public function store(Request $request, SecurityProgram $program){
        if ($request->expectsJson()) {
            return $this->saveTimelineRow($request, $program, null);
        }

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

        if(!\App\Services\Unit\UnitScope::canAccess($program, auth()->user())){
            abort(404);
        }

        if($main->program_id != $program->id){
            abort(404);
        }
        
        $data['main'] = $main;
        $data['programId'] = $program->id;
        return view('user.main-security-program.edit',$data);
    }

    public function update(Request $request, SecurityProgram $program, MainSecurityProgram $main){
        if ($request->expectsJson()) {
            return $this->saveTimelineRow($request, $program, $main);
        }

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
        $json = request()->expectsJson();

        if(!\App\Services\Unit\UnitScope::canAccess($program, auth()->user())){
            abort(404);
        }

        if($main->program_id != $program->id){
            abort(404);
        }

        $this->mainSecurityProgramService->deleteMainSecurityProgram($main);

        if ($json) {
            return response()->json(['success' => true, 'message' => 'Program berhasil dihapus.']);
        }

        Alert::success('Delete Berhasil', 'Program Keamanan berhasil dihapus!');
        return redirect()->route('user.main-security-program.index',['program'=>$program->id]);
    }

    /** Simpan satu baris timeline (AJAX). $main null = baris baru. */
    protected function saveTimelineRow(Request $request, SecurityProgram $program, ?MainSecurityProgram $main)
    {
        if(!\App\Services\Unit\UnitScope::canAccess($program, auth()->user())){
            abort(404);
        }

        if ($main && ($main->program_id != $program->id)) {
            abort(404);
        }

        $validator = $this->validator($request->all(), MainSecurityProgramValidation::rulesForTimeline(), MainSecurityProgramValidation::timelineMessages());
        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first(), 'errors' => $validator->errors()], 422);
        }

        $payload = ['program_name' => $request->program_name, 'cells' => $request->cells];

        if ($main) {
            $this->mainSecurityProgramService->updateMainSecurityProgram($main, $payload);
            $saved = $main->fresh();
        } else {
            $created = getData($this->mainSecurityProgramService->createMainSecurityProgram($payload, $program->id));
            $saved = MainSecurityProgram::findOrFail($created['id']);
        }

        return response()->json([
            'success' => true,
            'message' => 'Program berhasil disimpan.',
            'id'      => $saved->id,
            'name'    => $saved->program_name,
            'cells'   => MainSecurityProgram::cellsFor($saved),
            'update_url' => route('user.main-security-program.update', ['program' => $program->id, 'main' => $saved->id]),
            'destroy_url' => route('user.main-security-program.destroy', ['program' => $program->id, 'main' => $saved->id]),
        ]);
    }
}
