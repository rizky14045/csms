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

class QuestionAssesmentController extends Controller
{
    public function create($categoryId){

        $data['categoryId'] = $categoryId;
        return view('admin.question-assesment.create',$data);

    }

    public function store(Request $request,$categoryId){
        try {
            DB::beginTransaction();

            $request->validate([
                'indicator' => 'required',

            ],[
                'indicator.required' => 'Indicator harus diisi!',
       
            ]);
            $lastCategory = QuestionAssesment::where('category_id',$categoryId)->latest()->first();
            $order = $lastCategory ? $lastCategory->order + 1 : 1; 

            QuestionAssesment::create([
                'category_id' => $categoryId,
                'indicator' => $request->indicator,
                'order' => $order,

            ]);
            
            DB::commit();
            Alert::success('Tambah Berhasil', 'Question assesment berhasil dibuat!');
            return redirect()->route('admin.category-assesment.index');
            
        } catch (\Throwable $th) {

            DB::rollback();
            throw $th;
        }
    }

    public function edit($questionId,$categoryId){
        $question = QuestionAssesment::where('category_id', $categoryId)->where('id',$questionId)->first();
        if(!$question){
            abort(404);
        }
        $data['categoryId'] = $categoryId;
        $data['question'] = $question;
        return view('admin.question-assesment.edit',$data);
    }

    public function update(Request $request,$questionId,$categoryId){

        try {
            DB::beginTransaction();

            $question = QuestionAssesment::where('category_id', $categoryId)->where('id',$questionId)->first();

            $request->validate([
                'indicator' => 'required',
            ],[
                'indicator.required' => 'Nama harus diisi!',
            ]);

            $question->indicator = $request->indicator;
            $question->save();
            
            DB::commit();
            Alert::success('Update Berhasil', 'Question berhasil diubah!');
            return redirect()->route('admin.category-assesment.index');
            
        } catch (\Throwable $th) {

            DB::rollback();
            throw $th;
        }
    }

    public function destroy($questionId,$categoryId){
        try {
            DB::beginTransaction();

            $question = QuestionAssesment::where('category_id', $categoryId)->where('id',$questionId)->first();
            LevelAssesment::where('question_id',$questionId)->delete();
            $question->delete();
            
            DB::commit();
            Alert::success('Delete Berhasil', 'Question berhasil dihapus!');
            return redirect()->route('admin.category-assesment.index');
            
        } catch (\Throwable $th) {

            DB::rollback();
            throw $th;
        }
    }
}
