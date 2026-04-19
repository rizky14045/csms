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

        $this->middleware('can:view.security.kpi.unit')->only(['index', 'show']);
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
        $result = $this->kpiService->getAllKpi(10, true, $user->id);
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
        $semester = (int)$request->semester;
        $userId = auth()->id();
        
        // ===============================
        // ✅ 1. CEK DUPLICATE
        // ===============================
        $exists = Kpi::where('year', $year)
            ->where('semester', $semester)
            ->where('unit_id', $userId)
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

        $this->kpiService->createKpi($request->all());

        Alert::success('Tambah Berhasil', 'KPI berhasil ditambah!');
        return redirect()->route('user.keamanan.index');
    }

    public function edit(Kpi $kpi){
        $user = Auth::guard('web')->user();
        if($kpi->unit_id != $user->id){
            abort(404);
        }

        if($kpi->send_status == true){
            Alert::warning('Warning', 'KPI sudah dikirm!');
            return redirect()->route('user.keamanan.index');
        }

        $data['kpi'] = $kpi;
        return view('user.keamanan.edit',$data);
    }
    public function update(Request $request , Kpi $kpi){
        // Validation rules
        $validator = $this->validator($request->all(), KpiValidation::rulesForUpdate(), KpiValidation::messages());
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $this->kpiService->updateKpi($kpi, $request->all());

        Alert::success('Update Berhasil', 'KPI berhasil diubah!');
        return redirect()->route('user.keamanan.index');
    }

    public function show(Kpi $kpi){

        $user = Auth::guard('web')->user();
        if ($kpi->unit_id !== $user->id) {
            abort(404);
        }

        if($kpi->send_status == true) {
            Alert::warning('Warning', 'KPI sudah dikirm!');
            return redirect()->route('user.keamanan.index');
        }

        $result = $this->kpiService->getAllKpiArea(0, false, $kpi->id, ['subAreas', 'subAreas.levels', 'subAreas.levels.notes']);
        
        $data['areas'] = getData($result);

        return view('user.keamanan.show',$data);
    }

    public function preview(Kpi $kpi){
        if($kpi->unit_id != Auth::guard('web')->user()->id){
            abort(404);
        }

        if($kpi->send_status == false){
            Alert::warning('Warning', 'KPI belum dikirm!');
            return redirect()->route('user.keamanan.index');
        }
    
        $result = $this->kpiService->getAllKpiArea(0, false, $kpi->id, ['subAreas', 'subAreas.levels', 'subAreas.levels.notes']);
        
        $data['areas'] = getData($result);
        
        return view('user.keamanan.preview',$data);
    }

    public function send(Kpi $kpi){
        if($kpi->unit_id != Auth::guard('web')->user()->id){
            abort(404);
        }

        if($kpi->send_status == true){
            Alert::warning('Kirim Gagal', 'KPI sudah dikirm!');
            return redirect()->route('user.keamanan.index');
        }

        $this->kpiService->sendKpi($kpi);

        Alert::success('Berhasil Dikirim', 'KPI berhasil dikirim!');
        return redirect()->route('user.keamanan.index');
    }

    public function destroy(Kpi $kpi){
        if($kpi->unit_id != Auth::guard('web')->user()->id){
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

    public function uploadNote(Request $request, Kpi $kpi, $areaId, KpiNote $note){
        if($kpi->unit_id != Auth::guard('web')->user()->id){
            abort(404);
        }
        if($kpi->send_status == true){
            Alert::warning('Update Gagal', 'KPI sudah dikirm!');
            return redirect()->back();
        }

        if($note->kpi_id != $kpi->id){
            abort(404);
        }

        // Validation rules
        $validator = $this->validator($request->all(), KpiValidation::rulesForUploadNote($note->id), KpiValidation::messages($note->id));
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $this->kpiService->uploadNote($request, $kpi, $areaId, $note);

        Alert::success('Update Berhasil', 'KPI berhasil diupdate!');
        return redirect()->route('user.keamanan.show',['kpi'=>$note->kpi_id,'areaId'=>$areaId]);
    }
}
