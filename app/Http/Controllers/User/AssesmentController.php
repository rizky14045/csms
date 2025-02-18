<?php

namespace App\Http\Controllers\User;

use App\Models\Assesment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\SignCategoryAssesment;
use App\Models\SignQuestionAssesment;
use RealRashid\SweetAlert\Facades\Alert;

class AssesmentController extends Controller
{
    public function index(){
        $userId = Auth::guard('web')->user()->id;
        $data['assesments'] = Assesment::where('send_status','!=',1)->where('unit_id', $userId)->latest()->paginate(25);
        return view('user.assesment.index',$data);
    }

    public function create(){
        return view('user.assesment.create');
    }

    public function edit(){
        return view('user.assesment.edit');
    }
    
    public function show($assesmentId){

        $data['categories'] = SignCategoryAssesment::with('questions','questions.levels')->where('assesment_id',$assesmentId)->get();
        return view('user.assesment.show',$data);
    }

    public function preview($assesmentId){

        $data['categories'] = SignCategoryAssesment::with('questions','questions.levels')->where('assesment_id',$assesmentId)->get();
        return view('user.assesment.preview',$data);
    }

    public function report($assesmentId){

        $assesment = Assesment::find($assesmentId);
        $categories = SignCategoryAssesment::with('questions', 'questions.levels')
        ->where('assesment_id', $assesmentId)
        ->get()
        ->map(function ($category) {
            // Hitung rata-rata untuk setiap kategori berdasarkan level dalam pertanyaan
            $category->average = number_format($category->questions->avg('evaluation_unit'),2);
    
            return $category;
        });

        $chartData = [
            'labels' => $categories->pluck('category_name'), // Ambil category_name sebagai label
            'data' => $categories->pluck('average'), // Ambil nilai rata-rata untuk data
        ];
        $chartJson = json_encode($chartData);
        $data['assesment'] = $assesment;
        $data['categories'] = $categories;
        $data['chartJson'] = $chartJson;
        return view('user.assesment.report',$data);
    }

    public function send($assesmentId){

        try {

            DB::beginTransaction();
            Assesment::where('id',$assesmentId)->update([
                'send_status' => 3,
            ]);

            DB::commit();
            Alert::success('Berhasil Dikirim', 'Assesment berhasil dikirim!');
            return redirect()->route('user.assesment.index');
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }

    public function updateQuestion(Request $request,$questionId){

        try {

            DB::beginTransaction();

            $request->validate([
                'evaluation_unit_'.$questionId => 'required',
            ],[
                'evaluation_unit_'.$questionId.'.required' => 'Level harus diisi!',
            ]);

            $signQuestion = SignQuestionAssesment::where('id',$questionId)->first();          
            $signQuestion->evaluation_unit = $request->input('evaluation_unit_'.$questionId);
            $signQuestion->save();
            DB::commit();
            
            Alert::success('Update Berhasil', 'Assesment berhasil diupdate!');
            return redirect()->route('user.assesment.show',['assesmentId'=>$signQuestion->assesment_id,'signCategoryId'=>$signQuestion->sign_category_id]);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }
}
