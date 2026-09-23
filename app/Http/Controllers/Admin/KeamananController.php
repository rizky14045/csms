<?php

namespace App\Http\Controllers\Admin;

use App\Models\Kpi;
use App\Models\KpiArea;
use App\Models\Unit;
use App\Models\KpiLevel;
use App\Services\Score\MlActualCalculator;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Kpi\KpiService;

class KeamananController extends Controller
{
    protected $kpiService;

    public function __construct(KpiService $kpiService)
    {
        $this->kpiService = $kpiService;

        $this->middleware('can:view.security.kpi.admin')->only(['index', 'show']);
        $this->middleware('can:validate.kpi.admin')->only(['check', 'finish']);
    }

    public function index(){
        $unitId = request('unit_id');

        $result = $this->kpiService->getAllKpi(10, true, $unitId, ['unit', 'getInvalidItemsNotesByUnit'], true);
        $data['kpis'] = getPaginate($result);
        $data['unit_lists'] = Unit::orderBy('name', 'asc')->get();
        $data['unitId'] = $unitId;
        return view('admin.keamanan.index',$data);
    }
    public function show(Kpi $kpi){
        if($kpi->send_status == false){
            abort(404);
        }

        $result = $this->kpiService->getAllKpiArea(0, false, $kpi->id, ['subAreas', 'subAreas.levels']);

        $areas   = getData($result);
        $checked = $this->kpiService->getCheckedMap($kpi);

        $data['areas']   = $areas;
        $data['kpi']     = $kpi;
        $data['mode']    = ((int) $kpi->status === 2 && auth()->user()->can('validate.kpi.admin')) ? 'pusat' : 'view';
        $data['checked'] = $checked;
        $data['actual']  = MlActualCalculator::kpi($areas, $checked);
        $data['backUrl'] = route('admin.keamanan.index');

        return view('admin.keamanan.show', $data);
    }

    public function check(Request $request, Kpi $kpi, KpiLevel $level){
        [$ok, $message] = $this->kpiService->toggleCheck(
            $kpi,
            $level,
            filter_var($request->input('checked'), FILTER_VALIDATE_BOOLEAN)
        );

        if (!$ok) {
            return response()->json(['success' => false, 'message' => $message], 422);
        }

        $areas   = getData($this->kpiService->getAllKpiArea(0, false, $kpi->id, ['subAreas', 'subAreas.levels']));
        $checked = $this->kpiService->getCheckedMap($kpi);

        return response()->json([
            'success' => true,
            'checked' => $checked,
            'actual'  => MlActualCalculator::kpi($areas, $checked),
        ]);
    }

    public function finish(Kpi $kpi){
        if ($this->kpiService->finishValidation($kpi)) {
            Alert::success('Berhasil', 'Validasi KPI selesai!');
        } else {
            Alert::error('Gagal', 'Data tidak dalam tahap validasi Pusat!');
        }

        return redirect()->route('admin.keamanan.index');
    }

    public function export(Kpi $kpi){
        if($kpi->send_status == false){
            abort(404);
        }

        $result = $this->kpiService->getAllKpiArea(0, false, $kpi->id, ['subAreas', 'subAreas.levels']);
        $areas  = getData($result);

        $totalSubAreas = collect($areas)->sum(fn($a) => count($a['sub_areas']));
        $bobot         = $totalSubAreas > 0 ? 1 / $totalSubAreas : 0;

        $filename = 'kpi-keamanan-' . $kpi->year . '-s' . $kpi->semester . '.xls';

        $html = view('exports.keamanan', [
            'areas'         => $areas,
            'kpi'           => $kpi,
            'bobot'         => $bobot,
            'totalSubAreas' => $totalSubAreas,
        ])->render();

        return response($html, 200, [
            'Content-Type'        => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}
