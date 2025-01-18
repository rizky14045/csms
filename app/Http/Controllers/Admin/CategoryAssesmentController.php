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

class CategoryAssesmentController extends Controller
{
    public function index(){
        $data['categories'] = CategoryAssesment::with('questions','questions.levels')->get();
        return view('admin.category-assesment.index',$data);
    }

    public function create(){
        return view('admin.category-assesment.create');
    }

    public function store(Request $request){
        try {
            DB::beginTransaction();

            $request->validate([
                'name' => 'required',

            ],[
                'name.required' => 'Nama harus diisi!',
       
            ]);
            $lastCategory = CategoryAssesment::latest()->first();
            $order = $lastCategory ? $lastCategory->order + 1 : 1; 
            CategoryAssesment::create([
                'name' => $request->name,
                'order' => $order,

            ]);
            
            DB::commit();
            Alert::success('Tambah Berhasil', 'Kategori berhasil dibuat!');
            return redirect()->route('admin.category-assesment.index');
            
        } catch (\Throwable $th) {

            DB::rollback();
            Alert::error('Tambah Gagal', 'Kategori gagal dibuat!');
            return redirect()->route('admin.category-assesment.index');
        }
    }

    public function edit($categoryId){
        $category = CategoryAssesment::where('id', $categoryId)->first();
        if(!$category){
            abort(404);
        }
        $data['category'] = $category;
        return view('admin.category-assesment.edit',$data);
    }

    public function update(Request $request,$categoryId){

        try {
            DB::beginTransaction();

            $category = CategoryAssesment::where('id',$categoryId)->first();

            $request->validate([
                'name' => 'required',
            ],[
                'name.required' => 'Nama harus diisi!',
            ]);

            $category->name = $request->name;
            $category->save();
            
            DB::commit();
            Alert::success('Update Berhasil', 'Kategori berhasil diubah!');
            return redirect()->route('admin.category-assesment.index');
            
        } catch (\Throwable $th) {

            DB::rollback();
            Alert::error('Update Gagal', 'Kategori gagal diubah!');
            return redirect()->route('admin.category-assesment.index');
        }
    }

    public function destroy($categoryId){
        try {
            DB::beginTransaction();

            $category = CategoryAssesment::where('id',$categoryId)->first();
            $questions = QuestionAssesment::where('category_id',$category->id)->get();

            foreach ($questions as $question){
                LevelAssesment::where('question_id',$question->id)->delete();
            }
            foreach ($questions as $question) {
                $question->delete();
            }
            $category->delete();
            
            DB::commit();
            Alert::success('Delete Berhasil', 'Kategori berhasil dihapus!');
            return redirect()->route('admin.category-assesment.index');
            
        } catch (\Throwable $th) {

            DB::rollback();
            Alert::error('Delete Gagal', 'Kategori gagal dihapus!');
            return redirect()->route('admin.category-assesment.index');
        }
    }
}
