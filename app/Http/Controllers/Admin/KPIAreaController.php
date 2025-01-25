<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Area;
use App\Models\Note;
use App\Models\Level;
use App\Models\SubArea;
use App\Models\QuestionAssesment;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use RealRashid\SweetAlert\Facades\Alert;

class KPIAreaController extends Controller
{
    public function index(){
        $data['areas'] = Area::where('type','kpi')->get();
        return view('admin.kpi-area.index',$data);
    }

    public function create(){
        return view('admin.kpi-area.create');
    }

    public function store(Request $request){
        try {
            DB::beginTransaction();

            $request->validate([
                'name' => 'required',
            ],[
                'name.required' => 'Nama harus diisi!',
            ]);
            $lastarea = Area::where('type','kpi')->latest()->first();
            $order = $lastarea ? $lastarea->order + 1 : 1; 
            Area::create([
                'name' => $request->name,
                'order' => $order,
                'type' => 'kpi',
            ]);
            
            DB::commit();
            Alert::success('Tambah Berhasil', 'Area berhasil dibuat!');
            return redirect()->route('admin.kpi-area.index');
            
        } catch (\Throwable $th) {

            DB::rollback();
            Alert::error('Tambah Gagal', 'Area gagal dibuat!');
            return redirect()->route('admin.kpi-area.index');
        }
    }

    public function edit($areaId){
        $area = Area::where('id', $areaId)->first();
        if(!$area){
            abort(404);
        }
        $data['area'] = $area;
        return view('admin.kpi-area.edit',$data);
    }

    public function update(Request $request,$areaId){

        try {
            DB::beginTransaction();

            $area = Area::where('id',$areaId)->first();

            $request->validate([
                'name' => 'required',
            ],[
                'name.required' => 'Nama harus diisi!',
            ]);

            $area->name = $request->name;
            $area->save();
            
            DB::commit();
            Alert::success('Update Berhasil', 'Area berhasil diubah!');
            return redirect()->route('admin.kpi-area.index');
            
        } catch (\Throwable $th) {

            DB::rollback();
            Alert::error('Update Gagal', 'Area gagal diubah!');
            return redirect()->route('admin.kpi-area.index');
        }
    }

    public function destroy($areaId){
        try {
            DB::beginTransaction();

            $area = Area::where('id',$areaId)->first();
            $subAreas = SubArea::where('area_id',$area->id)->get();
            $notes = Note::where('area_id',$area->id)->get();
            
            foreach ($subAreas as $subArea){

                $levels = Level::where('sub_area_id',$subArea->id)->get();
                foreach ($levels as $level){
                    Note::where('level_id',$level->id)->delete();
                }
                foreach ($levels as $level){
                    $level->delete();
                }
            }
            foreach ($subAreas as $subArea) {
                $subArea->delete();
            }
            $area->delete();
            
            DB::commit();
            Alert::success('Delete Berhasil', 'Area berhasil dihapus!');
            return redirect()->route('admin.kpi-area.index');
            
        } catch (\Throwable $th) {

            DB::rollback();
            Alert::error('Delete Gagal', 'Area gagal dihapus!');
            return redirect()->route('admin.kpi-area.index');
        }
    }
}
