<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\SubArea;
use App\Models\Note;
use App\Models\Level;
use App\Models\QuestionAssesment;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use RealRashid\SweetAlert\Facades\Alert;

class MarturitySubAreaController extends Controller
{

    public function create($areaId){
        $data['areaId'] = $areaId;
        return view('admin.marturity-sub-area.create',$data);
    }

    public function store(Request $request,$areaId){

        try {
            DB::beginTransaction();

            $request->validate([
                'name' => 'required',
                'description' => 'required',
                'reference' => 'required',
            ],[
                'name.required' => 'Nama harus diisi!',
                'description.required' => 'Uraian harus diisi!',
                'reference.required' => 'Referensi harus diisi!',
            ]);

            $lastSubArea = SubArea::where('type','marturity')->latest()->first();
            $order = $lastSubArea ? $lastSubArea->order + 1 : 1; 
            SubArea::create([
                'area_id' => $areaId,
                'name' => $request->name,
                'description' => $request->description,
                'reference' => $request->reference,
                'order' => $order,
                'type' =>'marturity'
            ]);
            
            DB::commit();
            Alert::success('Tambah Berhasil', 'Sub Area berhasil dibuat!');
            return redirect()->route('admin.marturity-area.index');
            
        } catch (\Throwable $th) {

            DB::rollback();
            throw $th;
            Alert::error('Tambah Gagal', 'Sub Area gagal dibuat!');
            return redirect()->route('admin.marturity-area.index');
        }
    }

    public function edit($subAreaId,$areaId){
        $subArea = SubArea::where('area_id',$areaId)->where('id', $subAreaId)->first();
        if(!$subArea){
            abort(404);
        }
        $data['subArea'] = $subArea;
        $data['areaId'] = $areaId;
        return view('admin.marturity-sub-area.edit',$data);
    }

    public function update(Request $request,$subAreaId,$areaId){

        try {
            DB::beginTransaction();

            $subArea = SubArea::where('area_id',$areaId)->where('id',$subAreaId)->first();

            $request->validate([
                'name' => 'required',
                'description' => 'required',
                'reference' => 'required',
            ],[
                'name.required' => 'Nama harus diisi!',
                'description.required' => 'Uraian harus diisi!',
                'reference.required' => 'Referensi harus diisi!',
            ]);

            $subArea->name = $request->name;
            $subArea->description = $request->description;
            $subArea->reference = $request->reference;
            $subArea->save();
            
            DB::commit();
            Alert::success('Update Berhasil', 'Sub Area berhasil diubah!');
            return redirect()->route('admin.marturity-area.index');
            
        } catch (\Throwable $th) {

            DB::rollback();
            throw $th;
            Alert::error('Update Gagal', 'Sub Area gagal diubah!');
            return redirect()->route('admin.marturity-area.index');
        }
    }

    public function destroy($subAreaId,$areaId){
        try {
            DB::beginTransaction();

            $subArea = SubArea::where('area_id',$areaId)->where('id',$subAreaId)->first();
           
            $subArea->delete();
            
            DB::commit();
            Alert::success('Delete Berhasil', 'Sub Area berhasil dihapus!');
            return redirect()->route('admin.marturity-area.index');
            
        } catch (\Throwable $th) {

            DB::rollback();
            throw $th;
            Alert::error('Delete Gagal', 'Sub Area gagal dihapus!');
            return redirect()->route('admin.marturity-area.index');
        }
    }
}
