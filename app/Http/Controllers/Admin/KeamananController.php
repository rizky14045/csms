<?php

namespace App\Http\Controllers\Admin;

use App\Models\Kpi;
use App\Models\KpiArea;
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
    }

    public function index(){
        $result = $this->kpiService->getAllKpi(10, true, null, ['unit', 'getInvalidItemsNotesByUnit'], true);
        $data['kpis'] = getPaginate($result);
        return view('admin.keamanan.index',$data);
    }
    public function show(Kpi $kpi){
        if($kpi->send_status == false){
            abort(404);
        }

        $result = $this->kpiService->getAllKpiArea(0, false, $kpi->id, ['subAreas', 'subAreas.levels']);

        $data['areas'] = getData($result);
        $data['kpi']   = $kpi;

        return view('admin.keamanan.show', $data);
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
