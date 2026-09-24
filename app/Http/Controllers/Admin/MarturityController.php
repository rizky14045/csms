<?php

namespace App\Http\Controllers\Admin;

use App\Models\Marturity;
use App\Models\Unit;
use App\Models\MarturityLevel;
use App\Services\Score\MlActualCalculator;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
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
        $this->middleware('can:validate.marturity.admin')->only(['check', 'finish']);
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

        $result = $this->marturityService->getAlMarturityArea(['subAreas', 'subAreas.levels', 'subAreas.levels.notes'], $marturity->id);
        $areas = getData($result);
        $checked = $this->marturityService->getCheckedMap($marturity);

        $data['areas']     = $areas;
        $data['marturity'] = $marturity;
        $data['mode']      = ((int) $marturity->status === 2 && auth()->user()->can('validate.marturity.admin')) ? 'pusat' : 'view';
        $data['checked']   = $checked;
        $data['actual']    = MlActualCalculator::marturity($areas, $checked);
        $data['backUrl']   = route('admin.marturity.index');

        return view('admin.marturity.show', $data);
    }

    public function check(Request $request, Marturity $marturity, MarturityLevel $level){
        [$ok, $message] = $this->marturityService->toggleCheck(
            $marturity,
            $level,
            (string) $request->input('filename'),
            filter_var($request->input('checked'), FILTER_VALIDATE_BOOLEAN)
        );

        if (!$ok) {
            return response()->json(['success' => false, 'message' => $message], 422);
        }

        $areas   = getData($this->marturityService->getAlMarturityArea(['subAreas', 'subAreas.levels'], $marturity->id));
        $checked = $this->marturityService->getCheckedMap($marturity);

        return response()->json([
            'success' => true,
            'checked' => $checked,
            'actual'  => MlActualCalculator::marturity($areas, $checked),
        ]);
    }

    public function finish(Marturity $marturity){
        if ($this->marturityService->finishValidation($marturity)) {
            Alert::success('Berhasil', 'Validasi Maturity selesai!');
        } else {
            Alert::error('Gagal', 'Data tidak dalam tahap validasi Pusat!');
        }

        return redirect()->route('admin.marturity.index');
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
        $filename = 'maturity-' . $unitName . '-' . $marturity->year . '-' . $marturity->period_slug . '.xls';

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
