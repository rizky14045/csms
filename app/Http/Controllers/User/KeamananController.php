<?php

namespace App\Http\Controllers\User;

use App\Models\Kpi;
use App\Models\Area;
use App\Models\Note;
use App\Models\Level;
use App\Models\KpiArea;
use App\Models\KpiNote;
use App\Models\SubArea;
use App\Models\KpiLevel;
use App\Models\KpiSubArea;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Kpi\KpiService;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;
use App\Http\Validation\KpiValidation;

class KeamananController extends Controller
{
    protected $kpiService;

    public function __construct(KpiService $kpiService)
    {
        $this->kpiService = $kpiService;

        $this->middleware('can:view.security.kpi.unit')->only(['index']);
        $this->middleware('can:create.security.kpi.unit')->only(['create', 'store']);
        $this->middleware('can:edit.security.kpi.unit')->only(['edit', 'update']);
        $this->middleware('can:delete.security.kpi.unit')->only(['destroy']);
    }

    protected function validator(array $data, $validation, array $messages = [])
    {
        return Validator::make($data, $validation, $messages);
    }

    public function index(){
        $user = Auth::guard('web')->user();
        $result = $this->kpiService->getAllKpi(10, true, $user->unit_id, ['unit', 'getInvalidItemsNotesByUnit']);
        $data['kpis'] = getPaginate($result);
        return view('user.keamanan.index',$data);
    }

    public function create(){
        return view('user.keamanan.create');
    }

    public function store(Request $request){
        // Validation rules
        $validator = $this->validator($request->all(), KpiValidation::rulesForCreate(), KpiValidation::messages());
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $year = (int)$request->year;
        $triwulan = (int)$request->triwulan;
        $unitId = auth()->user()->unit_id;

        // Validasi periode: tidak duplikat, tidak melewati triwulan berjalan, dan berurutan (T1 -> T4 per tahun).
        $error = \App\Services\Score\TriwulanRule::validate(\App\Models\Kpi::class, $unitId, $year, $triwulan);
        if ($error) {
            Alert::error('Gagal', $error);
            return back()->withErrors(['triwulan' => $error])->withInput();
        }

        $result = $this->kpiService->createKpi($request->all());

        if ($result->getStatusCode() !== 201) {
            $message = json_decode($result->getContent(), true)['message'] ?? 'KPI gagal ditambah!';
            Alert::error('Tambah Gagal', $message);
            return back()->withInput();
        }

        Alert::success('Tambah Berhasil', 'KPI berhasil ditambah!');
        if(auth()->user()->roles[0]->name == 'Pusat') {
            return redirect()->route('admin.keamanan.index');
        }
        return redirect()->route('user.keamanan.index');
    }

    public function show(Kpi $kpi){

        $user = Auth::guard('web')->user();
        if ($kpi->unit_id !== $user->unit_id) {
            abort(404);
        }

        if($kpi->send_status == true) {
            Alert::warning('Warning', 'KPI sudah dikirm!');
            return redirect()->route('user.keamanan.index');
        }

        $result = $this->kpiService->getAllKpiArea(0, false, $kpi->id, ['subAreas', 'subAreas.levels', 'subAreas.levels.notes']);

        $data['areas'] = getData($result);
        $data['kpi']   = $kpi;
        // level yang sudah dicentang Pusat terkunci pada tahap sanggahan
        $data['checked'] = $this->kpiService->getCheckedMap($kpi);

        return view('user.keamanan.show', $data);
    }

    /** Mulai sanggahan (sekali, maksimal 7 hari setelah validasi Pusat selesai). */
    public function rebuttal(Kpi $kpi){
        if($kpi->unit_id != Auth::guard('web')->user()->unit_id){
            abort(404);
        }
        abort_unless(auth()->user()->can('send.security.kpi.unit'), 403);

        if ($kpi->startRebuttal()) {
            Alert::success('Sanggahan Dimulai', 'Silakan ubah data yang belum divalidasi Pusat, lalu kirim ke MMRK.');
            return redirect()->route('user.keamanan.show', ['kpi' => $kpi->id]);
        }

        Alert::warning('Sanggah Tidak Tersedia', 'Sanggahan hanya dapat diajukan satu kali dan maksimal 7 hari setelah validasi Pusat selesai.');
        return redirect()->route('user.keamanan.index');
    }

    public function preview(Kpi $kpi){
        if($kpi->unit_id != Auth::guard('web')->user()->unit_id){
            abort(404);
        }

        if($kpi->send_status == false){
            Alert::warning('Warning', 'KPI belum dikirm!');
            return redirect()->route('user.keamanan.index');
        }
    
        $result  = $this->kpiService->getAllKpiArea(0, false, $kpi->id, ['subAreas', 'subAreas.levels', 'subAreas.levels.notes']);
        $areas   = getData($result);
        $checked = $this->kpiService->getCheckedMap($kpi);

        $data['areas']   = $areas;
        $data['kpi']     = $kpi;
        $data['mode']    = 'view';
        $data['checked'] = $checked;
        $data['actual']  = \App\Services\Score\MlActualCalculator::kpi($areas, $checked);
        $data['backUrl'] = route('user.keamanan.index');

        return view('admin.keamanan.show', $data);
    }

    public function send(Kpi $kpi){
        if($kpi->unit_id != Auth::guard('web')->user()->unit_id){
            abort(404);
        }

        if($kpi->send_status == true){
            Alert::warning('Kirim Gagal', 'KPI sudah dikirm!');
            return redirect()->route('user.keamanan.index');
        }

        // Dinonaktifkan sesuai permintaan: KPI boleh dikirim ke Pusat
        // walau belum semua catatan/evidence terisi.
        // $kpi->load(['getInvalidItemsNotesByUnit']);
        // if(count($kpi->getInvalidItemsNotesByUnit) > 0){
        //     Alert::warning('Kirim Gagal', 'Terdapat catatan yang belum valid!');
        //     return redirect()->route('user.keamanan.index');
        // }

        $result = $this->kpiService->sendKpi($kpi);
        if ($result->getStatusCode() !== 200) {
            Alert::error('Gagal Dikirim', 'KPI sudah dikirim atau gagal dikirim!');
            return redirect()->route(auth()->user()->hasRole('Pusat') ? 'admin.keamanan.index' : 'user.keamanan.index');
        }

        $kpi->refresh();
        Alert::success('Berhasil Dikirim', (int) $kpi->status === 1 ? 'KPI berhasil dikirim ke MMRK!' : 'KPI berhasil dikirim!');
        if(auth()->user()->roles[0]->name == 'Pusat') {
            return redirect()->route('admin.keamanan.index');
        }
        return redirect()->route('user.keamanan.index');
    }

    public function destroy(Kpi $kpi){
        if($kpi->unit_id != Auth::guard('web')->user()->unit_id){
            abort(404);
        }

        if($kpi->send_status == true){
            Alert::warning('Delete Gagal', 'KPI sudah dikirm!');
            return redirect()->route('user.keamanan.index');
        }
        
        $this->kpiService->deleteKpi($kpi);

        Alert::success('Delete Berhasil', 'KPI berhasil dihapus!');
        return redirect()->route('user.keamanan.index');
    }

    public function uploadLevel(Request $request, Kpi $kpi, KpiLevel $level)
    {
        try {
            if ($kpi->send_status || $level->kpi_id != $kpi->id) {
                return response()->json(['success' => false, 'message' => 'Data sudah terkunci!'], 403);
            }

            // Level yang sudah dicentang (divalidasi) Pusat tidak dapat diubah lagi.
            if (\App\Models\KpiLevelCheck::where('level_id', $level->id)->exists()) {
                return response()->json(['success' => false, 'message' => 'Level sudah divalidasi Pusat dan tidak dapat diubah!'], 403);
            }

            if (!$this->kpiService->isLevelUnlocked($level)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Selesaikan evidence Level sebelumnya terlebih dahulu!',
                ], 422);
            }

            $validator = Validator::make($request->all(), [
                'file' => 'required|file|mimes:pdf|max:25600',
            ], [
                'file.required' => 'File harus dipilih!',
                'file.mimes'    => 'File harus berupa PDF!',
                'file.max'      => 'Ukuran file maksimal 25MB!',
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }

            // Delete old file
            if ($level->attachment_file) {
                $oldPath = public_path('uploads/attachment_file_kpi_file/' . $level->attachment_file);
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }

            $file     = $request->file('file');
            $filename = 'kpi-level-' . time() . '-' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/attachment_file_kpi_file'), $filename);

            $level->update([
                'attachment_file' => $filename,
                'updated_by'      => auth()->id(),
            ]);

            return response()->json([
                'success'  => true,
                'filename' => $filename,
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function uploadNote(Request $request, Kpi $kpi, $areaId, KpiNote $note)
    {
        try {

            $validator = Validator::make($request->all(), [
                'attachment_file_'.$note->id => 'required|file|mimes:pdf|max:2048'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            $file = $request->file('attachment_file_'.$note->id);
            $filename = time().'_'.$file->getClientOriginalName();

            $file->move(public_path('uploads/attachment_file_kpi_file'), $filename);

            $note->update([
                'attachment_file' => $filename
            ]);

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $note->id,
                    'attachment_file' => $filename
                ]
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
