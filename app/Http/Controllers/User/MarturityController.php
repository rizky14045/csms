<?php

namespace App\Http\Controllers\User;

use App\Models\Marturity;
use App\Models\MarturityLevel;
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
        $triwulan = (int)$request->triwulan;
        $unitId = auth()->user()->unit_id;

        // Validasi periode: tidak duplikat, tidak melewati triwulan berjalan, dan berurutan (T1 -> T4 per tahun).
        $error = \App\Services\Score\TriwulanRule::validate(\App\Models\Marturity::class, $unitId, $year, $triwulan);
        if ($error) {
            Alert::error('Gagal', $error);
            return back()->withErrors(['triwulan' => $error])->withInput();
        }

        $result = $this->marturityService->createMarturity($request->all());

        if ($result->getStatusCode() !== 201) {
            $message = json_decode($result->getContent(), true)['message'] ?? 'Marturity gagal ditambah!';
            Alert::error('Tambah Gagal', $message);
            return back()->withInput();
        }

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

        $result = $this->marturityService->getAlMarturityArea(['subAreas', 'subAreas.levels', 'subAreas.levels.notes'], $marturity->id);
        $data['areas'] = getData($result);
        $data['marturity'] = $marturity;
        return view('user.marturity.show', $data);
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
        $result = $this->marturityService->getAlMarturityArea(['subAreas', 'subAreas.levels', 'subAreas.levels.notes'], $marturity->id);
        $areas   = getData($result);
        $checked = $this->marturityService->getCheckedMap($marturity);

        $data['areas']     = $areas;
        $data['marturity'] = $marturity;
        $data['mode']      = 'view';
        $data['checked']   = $checked;
        $data['actual']    = \App\Services\Score\MlActualCalculator::marturity($areas, $checked);
        $data['backUrl']   = route('user.marturity.index');

        return view('admin.marturity.show', $data);
    }

    public function send(Marturity $marturity){
        if($marturity->send_status == true){
            Alert::warning('Warning', 'Marturity sudah dikirm!');
            return redirect()->route('user.marturity.index');
        }

        // Dinonaktifkan sesuai permintaan: Marturity boleh dikirim ke Pusat
        // walau belum semua catatan/evidence terisi.
        // $marturity->load('getInvalidItemsNotesByUnit');
        // if(count($marturity->getInvalidItemsNotesByUnit) > 0){
        //     Alert::error('Gagal Dikirim', 'Maturity tidak bisa dikirim karena terdapat catatan yang belum diisi!');
        //     return redirect()->route('user.marturity.index');
        // }

        $result = $this->marturityService->sendMarturity($marturity);
        if ($result->getStatusCode() !== 200) {
            Alert::error('Gagal Dikirim', 'Marturity sudah dikirim atau gagal dikirim!');
            return redirect()->route(auth()->user()->hasRole('Pusat') ? 'admin.marturity.index' : 'user.marturity.index');
        }

        $marturity->refresh();
        Alert::success('Berhasil Dikirim', (int) $marturity->status === 1 ? 'Marturity berhasil dikirim ke MMRK!' : 'Marturity berhasil dikirim!');
        if(auth()->user()->roles[0]->name == 'Pusat'){
            return redirect()->route('admin.marturity.index');
        } else {
            return redirect()->route('user.marturity.index');
        }
    }

    public function destroy(Marturity $marturity){
        if($marturity->send_status == true){
            Alert::warning('Warning', 'Marturity sudah dikirm!');
            return redirect()->route('user.marturity.index');
        }
        $user = Auth::guard('web')->user();
        
        $marturity = Marturity::where('unit_id',$user->unit_id)->where('id',$marturity->id)->first();

        if(!$marturity){
            abort(404);
        }

        $this->marturityService->deleteMarturity($marturity);

        Alert::success('Delete Berhasil', 'Marturity berhasil dihapus!');
        return redirect()->route('user.marturity.index');
    }
    public function uploadLevel(Request $request, Marturity $marturity, MarturityLevel $level)
    {
        try {
            if ($marturity->send_status || $level->marturity_id != $marturity->id) {
                return response()->json(['success' => false, 'message' => 'Data sudah terkunci!'], 403);
            }

            if (!$this->marturityService->isLevelUnlocked($level)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Selesaikan evidence Level sebelumnya terlebih dahulu!',
                ], 422);
            }

            $totalEvidence  = $level->total_evidence ?? 999;
            $existingCount  = count(json_decode($level->attachment_files ?? '[]', true) ?: []);
            $remainingSlots = max(0, $totalEvidence - $existingCount);

            $validator = $this->validator(
                array_merge($request->all(), ['files' => $request->file('files')]),
                [
                    'files'   => "required|array|min:1|max:{$remainingSlots}",
                    'files.*' => 'mimes:pdf|max:25600',
                ],
                [
                    'files.required' => 'Minimal 1 file harus dipilih!',
                    'files.min'      => 'Minimal 1 file harus dipilih!',
                    'files.max'      => "Maksimal {$remainingSlots} file lagi yang bisa ditambahkan!",
                    'files.*.mimes'  => 'File harus berupa PDF!',
                    'files.*.max'    => 'Ukuran file maksimal 25MB!',
                ]
            );

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $result     = $this->marturityService->uploadLevelFiles($request, $level);
            $statusCode = $result->getStatusCode();
            $data       = json_decode($result->getContent(), true);

            if ($statusCode !== 200) {
                return response()->json([
                    'success' => false,
                    'message' => $data['message'] ?? 'Upload gagal',
                ], $statusCode);
            }

            return response()->json([
                'success' => true,
                'message' => 'Upload berhasil',
                'files'   => $data['data']['files'] ?? [],
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function deleteLevelFile(Request $request, Marturity $marturity, MarturityLevel $level)
    {
        try {
            if ($marturity->send_status || $level->marturity_id != $marturity->id) {
                return response()->json(['success' => false, 'message' => 'Data sudah terkunci!'], 403);
            }

            $filename = $request->input('filename');
            if (!$filename) {
                return response()->json(['error' => 'Filename tidak ditemukan'], 422);
            }

            $result     = $this->marturityService->deleteLevelFile($level, $filename);
            $statusCode = $result->getStatusCode();
            $data       = json_decode($result->getContent(), true);

            if ($statusCode !== 200) {
                return response()->json([
                    'success' => false,
                    'message' => $data['message'] ?? 'Hapus gagal',
                ], $statusCode);
            }

            return response()->json([
                'success' => true,
                'message' => 'File berhasil dihapus',
                'files'   => $data['data']['files'] ?? [],
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
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
