<?php

namespace App\Http\Controllers\User;

use App\Models\Marturity;
use Illuminate\Http\Request;
use App\Models\MarturityNote;
use App\Http\Controllers\Controller;
use App\Http\Validation\MarturityValidation;
use App\Services\Marturity\MarturityService;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;

class MarturityController extends Controller
{
    protected $marturityService;

    public function __construct(MarturityService $marturityService)
    {
        $this->marturityService = $marturityService;

        $this->middleware('can:view.marturity.unit')->only(['index']);
        $this->middleware('can:create.marturity.unit')->only(['create', 'store']);
        $this->middleware('can:edit.marturity.unit')->only(['edit', 'update']);
        $this->middleware('can:delete.marturity.unit')->only(['destroy']);
    }

    protected function validator(array $data, $validation, array $messages = [])
    {
        return Validator::make($data, $validation, $messages);
    }

    public function index(Request $request){
        $user = Auth::guard('web')->user();
        $result = $this->marturityService->getAlMarturity(10, true, ['unit', 'getInvalidItemsNotesByUnit'], auth()->user()->unit_id);
         $data['marturities'] = getPaginate($result);
        $data['marturities'] = getPaginate($result);
        $data['request'] = $request->all();
        return view('user.marturity.index',$data);
    }

    public function create(){
        return view('user.marturity.create');
    }

    public function store(Request $request){
        // Validation rules
        $validator = $this->validator($request->all(), MarturityValidation::rulesForCreate(), MarturityValidation::messages());
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $year = (int)$request->year;
        $semester = (int)$request->semester;
        $unit_id = auth()->user()->unit_id;

        // ===============================
        // ✅ 1. CEK DUPLICATE
        // ===============================
        $exists = Marturity::where('year', $year)
            ->where('semester', $semester)
            ->where('unit_id', $unit_id)
            ->exists();

        if ($exists) {
            Alert::error('Gagal', 'Semester tersebut sudah diisi untuk tahun ini!');
            return back()->withErrors([
                'semester' => 'Semester sudah ada'
            ])->withInput();
        }

        // ===============================
        // ✅ 2. CEK BELUM WAKTUNYA
        // ===============================
        $currentMonth = now()->month;

        if ($currentMonth <= 6) {
            $currentSemester = 1;
        } else {
            $currentSemester = 2;
        }

        if ($year == now()->year && $semester > $currentSemester) {
            Alert::error('Gagal', 'Belum waktunya mengisi semester tersebut!');
            return back()->withErrors([
                'semester' => 'Belum waktunya mengisi semester tersebut'
            ])->withInput();
        }

        $this->marturityService->createMarturity($request->all());
        
        Alert::success('Tambah Berhasil', 'Marturity berhasil ditambah!');
        if(auth()->user()->roles[0]->name == 'Pusat'){
            return redirect()->route('admin.marturity.index');
        } else {
            return redirect()->route('user.marturity.index');
        }
    }

    public function show(Marturity $marturity){
        $user = Auth::guard('web')->user();
        if($marturity->send_status == true){
            Alert::warning('Warning', 'Marturity sudah dikirm!');
            return redirect()->route('user.marturity.index');
        }

        if($marturity->unit_id !== $user->unit_id){
            abort(404);
        }

        $result = $this->marturityService->getAlMarturityArea(['subAreas','subAreas.levels','subAreas.levels.notes'], $marturity->id);
        $data['areas'] = getData($result);
        return view('user.marturity.show',$data);
    }

    public function preview(Marturity $marturity){
         $user = Auth::guard('web')->user();
        if($marturity->unit_id !== $user->unit_id){
            abort(404);
        }
        if($marturity->send_status != true){
            Alert::warning('Warning', 'Marturity belum dikirm!');
            return redirect()->route('user.marturity.index');
        }
        $result = $this->marturityService->getAlMarturityArea(['subAreas','subAreas.levels','subAreas.levels.notes'], $marturity->id);
        $data['areas'] = getData($result);

        return view('user.marturity.preview',$data);
    }

    public function send(Marturity $marturity){
        if($marturity->send_status == true){
            Alert::warning('Warning', 'Marturity sudah dikirm!');
            return redirect()->route('user.marturity.index');
        }

        $marturity->load('getInvalidItemsNotesByUnit');

        if(count($marturity->getInvalidItemsNotesByUnit) > 0){
            Alert::error('Gagal Dikirim', 'Marturity tidak bisa dikirim karena terdapat catatan yang belum diisi!');
            return redirect()->route('user.marturity.index');
        }

        $this->marturityService->sendMarturity($marturity);
        
        Alert::success('Berhasil Dikirim', 'Marturity berhasil dikirim!');
        return redirect()->route('user.marturity.index');
    }

    public function destroy(Marturity $marturity){
        if($marturity->send_status == true){
            Alert::warning('Warning', 'Marturity sudah dikirm!');
            return redirect()->route('user.marturity.index');
        }
        $user = Auth::guard('web')->user();
        
        $marturity = Marturity::where('unit_id',$user->id)->where('id',$marturity->id)->first();

        if(!$marturity){
            abort(404);
        }

        $this->marturityService->deleteMarturity($marturity);

        Alert::success('Delete Berhasil', 'Marturity berhasil dihapus!');
        return redirect()->route('user.marturity.index');
    }
    public function uploadNote(Request $request, Marturity $marturity, $areaId, MarturityNote $note)
    {
        try {

            // VALIDATION
            $validator = $this->validator(
                $request->all(),
                MarturityValidation::rulesForUploadNote($note->id),
                MarturityValidation::messages($note->id)
            );

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            // PROCESS UPLOAD
            $updatedNote = $this->marturityService->uploadNote($request, $marturity, $areaId, $note);

            return response()->json([
                'success' => true,
                'message' => 'Upload berhasil',
                'data' => [
                    'id' => $note->id,
                    'attachment_file' => $updatedNote->attachment_file ?? $note->attachment_file
                ]
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Upload gagal',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
