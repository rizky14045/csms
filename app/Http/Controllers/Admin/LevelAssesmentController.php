<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\LevelAssesment;
use App\Models\CategoryAssesment;
use App\Models\QuestionAssesment;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use RealRashid\SweetAlert\Facades\Alert;

class LevelAssesmentController extends Controller
{
    public function create($questionId){

        $data['questionId'] = $questionId;
        return view('admin.level-assesment.create',$data);

    }

    public function store(Request $request,$questionId){

        try {
            DB::beginTransaction();

            $request->validate([
                'level' => 'required',
                'level_description' => 'required',

            ],[
                'level.required' => 'Level harus diisi!',
                'level_description.required' => 'Level Penilaian harus diisi!',
       
            ]);

            $lastLevel = LevelAssesment::where('question_id',$questionId)->latest()->first();
            $order = $lastLevel ? $lastLevel->order + 1 : 1; 

            LevelAssesment::create([
                'question_id' => $questionId,
                'level' => $request->level,
                'level_description' => $request->level_description,
                'order' => $order,

            ]);
            
            DB::commit();
            Alert::success('Tambah Berhasil', 'Level assesment berhasil dibuat!');
            return redirect()->route('admin.category-assesment.index');
            
        } catch (\Throwable $th) {

            DB::rollback();
            Alert::error('Tambah Gagal', 'Level assesment gagal dibuat!');
            return redirect()->route('admin.category-assesment.index');
        }
    }

    public function edit($questionId,$levelId){

        $level = LevelAssesment::where('question_id', $questionId)->where('id',$questionId)->first();
        if(!$level){
            abort(404);
        }
        $data['questionId'] = $questionId;
        $data['level'] = $level;
        return view('admin.level-assesment.edit',$data);
    }

    public function update(Request $request,$questionId,$levelId){

        try {
            DB::beginTransaction();

            $level = LevelAssesment::where('question_id', $questionId)->where('id',$levelId)->first();

            $request->validate([
                'level' => 'required',
                'level_description' => 'required',

            ],[
                'level.required' => 'Level harus diisi!',
                'level_description.required' => 'Level Penilaian harus diisi!',
       
            ]);
            $level->level = $request->level;
            $level->level_description = $request->level_description;
            $level->save();
            
            DB::commit();
            Alert::success('Update Berhasil', 'Level berhasil diubah!');
            return redirect()->route('admin.category-assesment.index');
            
        } catch (\Throwable $th) {

            DB::rollback();
            Alert::error('Update Gagal', 'Level assesment gagal diubah!');
            return redirect()->route('admin.category-assesment.index');
        }
    }

    public function destroy($levelId,$questionId){
        try {
            DB::beginTransaction();

            $level = LevelAssesment::where('question_id', $questionId)->where('id',$levelId)->first();
            $level->delete();
            
            DB::commit();
            Alert::success('Delete Berhasil', 'Level berhasil dihapus!');
            return redirect()->route('admin.category-assesment.index');
            
        } catch (\Throwable $th) {

            DB::rollback();
            Alert::error('Delete Gagal', 'Level assesment gagal dihapus!');
            return redirect()->route('admin.category-assesment.index');
        }
    }
}
