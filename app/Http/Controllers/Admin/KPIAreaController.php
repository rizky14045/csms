<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Area;
use App\Http\Controllers\Controller;
use App\Http\Validation\AreaValidation;
use App\Services\Area\AreaService;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;

class KPIAreaController extends Controller
{
    protected $areaService;

    public function __construct(AreaService $areaService)
    {
        $this->areaService = $areaService;

        $this->middleware('can:view.kpi.area')->only(['index']);
        $this->middleware('can:create.kpi.area')->only(['create', 'store']);
        $this->middleware('can:edit.kpi.area')->only(['edit', 'update']);
        $this->middleware('can:delete.kpi.area')->only(['destroy']);
    }

    protected function validator(array $data, $validation, array $messages = [])
    {
        return Validator::make($data, $validation, $messages);
    }
    
    public function index(){
        $result = $this->areaService->getAllArea(100, true, 'kpi', ['subAreas.levels.notes']);
        $data['areas'] = getPaginate($result);
        $data['request'] = request();
        return view('admin.kpi-area.index',$data);
    }

    public function create(){
        return view('admin.kpi-area.create');
    }

    public function store(Request $request){
        try {
            // Validation rules
            $validator = $this->validator($request->all(), AreaValidation::rulesForCreate(), AreaValidation::messages());
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $type = 'kpi';
            $request->merge(['type' => $type]);
            $this->areaService->createArea($request->all());

            Alert::success('Tambah Berhasil', 'Area berhasil dibuat!');
            return redirect()->route('admin.kpi-area.index');
            
        } catch (\Throwable $th) {
            Alert::error('Tambah Gagal', 'Area gagal dibuat!');
            return redirect()->route('admin.kpi-area.index');
        }
    }

    public function edit(Area $area){
        $data['area'] = $area;
        return view('admin.kpi-area.edit',$data);
    }

    public function update(Request $request, Area $area){

        try {
            // Validation rules
            $validator = $this->validator($request->all(), AreaValidation::rulesForUpdate(), AreaValidation::messages());
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $this->areaService->updateArea($area, $request->all());

            Alert::success('Update Berhasil', 'Area berhasil diubah!');
            return redirect()->route('admin.kpi-area.index');
            
        } catch (\Throwable $th) {
            Alert::error('Update Gagal', 'Area gagal diubah!');
            return redirect()->route('admin.kpi-area.index');
        }
    }

    public function destroy(Area $area){
        try {
            $this->areaService->deleteArea($area);

            Alert::success('Delete Berhasil', 'Area berhasil dihapus!');
            return redirect()->route('admin.kpi-area.index');
            
        } catch (\Throwable $th) {
            Alert::error('Delete Gagal', 'Area gagal dihapus!');
            return redirect()->route('admin.kpi-area.index');
        }
    }
}
