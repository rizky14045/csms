<?php

namespace App\Http\Controllers\Admin;

use App\Models\Marturity;
use App\Models\Unit;
use App\Http\Controllers\Controller;
use App\Services\Marturity\MarturityService;
use Illuminate\Support\Str;

class MarturityController extends Controller
{
    protected $marturityService;

    public function __construct(MarturityService $marturityService)
    {
        $this->marturityService = $marturityService;

        $this->middleware('can:view.marturity.admin')->only(['index', 'show']);
    }

    public function index(){
        $unitId = request('unit_id');

        $result = $this->marturityService->getAlMarturity(10, true, ['unit', 'getInvalidItemsNotesByUnit'], $unitId, true);
        $data['marturities'] = getPaginate($result);
        $data['unit_lists'] = Unit::orderBy('name', 'asc')->get();
        $data['unitId'] = $unitId;
        return view('admin.marturity.index',$data);
    }

    public function show(Marturity $marturity){
        if(!$marturity->send_status){
            abort(404);
        }

        $result = $this->marturityService->getAlMarturityArea(['subAreas', 'subAreas.levels'], $marturity->id);
        $data['areas']    = getData($result);
        $data['marturity'] = $marturity;

        return view('admin.marturity.show', $data);
    }

    public function export(Marturity $marturity){
        if(!$marturity->send_status){
            abort(404);
        }

        $result = $this->marturityService->getAlMarturityArea(['subAreas', 'subAreas.levels'], $marturity->id);
        $areas  = getData($result);

        $totalSubAreas = collect($areas)->sum(fn($a) => count($a['sub_areas']));
        $bobot         = $totalSubAreas > 0 ? 1 / $totalSubAreas : 0;

        $unitName = Str::slug($marturity->unit->name ?? 'unit');
        $filename = 'maturity-' . $unitName . '-' . $marturity->year . '-s' . $marturity->semester . '.xls';

        $html = view('exports.marturity', [
            'areas'        => $areas,
            'marturity'    => $marturity,
            'bobot'        => $bobot,
            'totalSubAreas' => $totalSubAreas,
        ])->render();

        return response($html, 200, [
            'Content-Type'        => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}
