<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\SubArea;
use App\Http\Controllers\Controller;
use App\Http\Validation\SubAreaValidation;
use App\Models\Area;
use App\Services\SubArea\SubAreaService;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;

class KPISubAreaController extends Controller
{
    protected $subareaService;

    public function __construct(SubAreaService $subareaService)
    {
        $this->subareaService = $subareaService;

        $this->middleware('can:create.kpi.subarea')->only(['create', 'store']);
        $this->middleware('can:edit.kpi.subarea')->only(['edit', 'update']);
        $this->middleware('can:delete.kpi.subarea')->only(['destroy']);
    }

    protected function validator(array $data, $validation, array $messages = [])
    {
        return Validator::make($data, $validation, $messages);
    }
    
    public function create(Area $area){
        $data['area'] = $area;
        return view('admin.kpi-sub-area.create',$data);
    }

    public function store(Request $request, Area $area){

        try {
             // Validation rules
            $validator = $this->validator($request->all(), SubAreaValidation::rulesForCreate(), SubAreaValidation::messages());
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
    
            $type = 'kpi';
            $request->merge(['type' => $type]);
            $this->subareaService->createSubArea($request->all(), $area);

            Alert::success('Tambah Berhasil', 'Sub Area berhasil dibuat!');
            return redirect()->route('admin.kpi-area.index');
            
        } catch (\Throwable $th) {
            Alert::error('Tambah Gagal', 'Sub Area gagal dibuat!');
            return redirect()->route('admin.kpi-area.index');
        }
    }

    public function edit(SubArea $subArea, Area $area){
        $data['subArea'] = $subArea;
        $data['area'] = $area;
        return view('admin.kpi-sub-area.edit',$data);
    }

    public function update(Request $request, SubArea $subArea, Area $area){

        try {
            // Validation rules
            $validator = $this->validator($request->all(), SubAreaValidation::rulesForUpdate(), SubAreaValidation::messages());
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $this->subareaService->updateSubArea($subArea,$request->all());

            Alert::success('Update Berhasil', 'Sub Area berhasil diubah!');
            return redirect()->route('admin.kpi-area.index');
            
        } catch (\Throwable $th) {
            Alert::error('Update Gagal', 'Sub Area gagal diubah!');
            return redirect()->route('admin.kpi-area.index');
        }
    }

    public function destroy(SubArea $subArea, Area $area){
        try {
            $this->subareaService->deleteSubArea($subArea);

            Alert::success('Delete Berhasil', 'Sub Area berhasil dihapus!');
            return redirect()->route('admin.kpi-area.index');
            
        } catch (\Throwable $th) {
            Alert::error('Delete Gagal', 'Sub Area gagal dihapus!');
            return redirect()->route('admin.kpi-area.index');
        }
    }
}
