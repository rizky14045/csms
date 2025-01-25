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

class MarturityLevelController extends Controller
{

    public function create($subAreaId){

        $data['subAreaId'] = $subAreaId;
        return view('admin.marturity-level.create',$data);
    }

    public function store(Request $request,$subAreaId){
        try {
            DB::beginTransaction();

            $request->validate([
                'level' => 'required',
                'description' => 'required',
            ],[
                'level.required' => 'Level harus diisi!',
                'description.required' => 'Uraian harus diisi!',
            ]);

            $lastLevel = Level::where('type','marturity')->latest()->first();
            $order = $lastLevel ? $lastLevel->order + 1 : 1; 
            Level::create([
                'sub_area_id' => $subAreaId,
                'level' => $request->level,
                'description' => $request->description,
                'order' => $order,
                'type' =>'marturity'
            ]);
            
            DB::commit();
            Alert::success('Tambah Berhasil', 'Level berhasil dibuat!');
            return redirect()->route('admin.marturity-area.index');
            
        } catch (\Throwable $th) {

            DB::rollback();
            throw $th;
            Alert::error('Tambah Gagal', 'Level gagal dibuat!');
            return redirect()->route('admin.marturity-area.index');
        }
    }

    public function edit($levelId,$subAreaId){

        $level = Level::where('sub_area_id',$subAreaId)->where('id', $levelId)->first();
        if(!$level){
            abort(404);
        }
        $data['level'] = $level;
        $data['subAreaId'] = $subAreaId;
        return view('admin.marturity-level.edit',$data);
    }

    public function update(Request $request,$levelId,$subAreaId){

        try {
            DB::beginTransaction();

            $level = Level::where('sub_area_id',$subAreaId)->where('id',$levelId)->first();

            $request->validate([
                'level' => 'required',
                'description' => 'required',
            ],[
                'level.required' => 'Level harus diisi!',
                'description.required' => 'Uraian harus diisi!',
            ]);

            $level->level = $request->level;
            $level->description = $request->description;
            $level->save();
            
            DB::commit();
            Alert::success('Update Berhasil', 'Level berhasil diubah!');
            return redirect()->route('admin.marturity-area.index');
            
        } catch (\Throwable $th) {

            DB::rollback();
            throw $th;
            Alert::error('Update Gagal', 'Level gagal diubah!');
            return redirect()->route('admin.marturity-area.index');
        }
    }

    public function destroy($subAreaId,$levelId){

        try {
            DB::beginTransaction();

            $level = Level::where('sub_area_id',$subAreaId)->where('id',$levelId)->first();
            $level->delete();
            
            DB::commit();
            Alert::success('Delete Berhasil', 'Level berhasil dihapus!');
            return redirect()->route('admin.marturity-area.index');
            
        } catch (\Throwable $th) {

            DB::rollback();
            Alert::error('Delete Gagal', 'Level gagal dihapus!');
            return redirect()->route('admin.marturity-area.index');
        }
    }
}
