<?php

namespace App\Http\Controllers\Bujp;

use App\Models\Assesment;
use Illuminate\Http\Request;
use App\Models\LevelAssesment;
use App\Models\CategoryAssesment;
use App\Models\QuestionAssesment;
use App\Models\SignLevelAssesment;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\SignCategoryAssesment;
use App\Models\SignQuestionAssesment;
use RealRashid\SweetAlert\Facades\Alert;

class AssesmentController extends Controller
{
    public function index(){

        $vendorId = Auth::guard('vendor')->user()->id;
        $data['assesments'] = Assesment::with('vendor')->where('vendor_id', $vendorId)->latest()->get();
        return view('bujp.assesment.index',$data);

    }

    public function create(){
        return view('bujp.assesment.create');
    }

    public function store(Request $request){

        try {
            DB::beginTransaction();
            $vendor = Auth::guard('vendor')->user();
            
            $assesment = Assesment::create([
                'unit_id' => $vendor->parent_user_id,
                'vendor_id' => $vendor->id,
                'date' => $request->date,
                'contract' => $request->contract,
                'triwulan' => $request->triwulan,
            ]);
            
            $categories = CategoryAssesment::get();

            foreach ($categories as $category) {

                $signCategory = SignCategoryAssesment::create([
                    'vendor_id' => $vendor->id,
                    'assesment_id' => $assesment->id,
                    'category_assesment_id' => $category->id,
                    'category_name' => $category->name,
                    
                ]);
                $questions = QuestionAssesment::where('category_id', $category->id)->get();
                foreach ($questions as $question) {

                    $signQuestion = SignQuestionAssesment::create([
                        'vendor_id' => $vendor->id,
                        'assesment_id' => $assesment->id,
                        'question_assesment_id' => $question->id,
                        'sign_category_id' => $signCategory->id,
                        'indicator' => $question->indicator,
                        
                    ]);

                    $leves = LevelAssesment::where('question_id', $question->id)->get();
                    foreach ($leves as $level){

                        $signLevel = SignLevelAssesment::create([
                            'vendor_id' => $vendor->id,
                            'assesment_id' => $assesment->id,
                            'level_assesment_id' => $level->id,
                            'sign_question_id' => $signQuestion->id,
                            'level' => $level->level,
                            'level_description' => $level->level_description,
                            
                        ]);
                    }
                }
            }
            DB::commit();
            Alert::success('Tambah Berhasil', 'Assesment berhasil ditambah!');
            return redirect()->route('bujp.assesment.index');
            
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }
    public function edit($assesmentId){

        $vendorId = Auth::guard('vendor')->user()->id;
        $assesment = Assesment::where('vendor_id', $vendorId)->where('id', $assesmentId)->first();
        if(!$assesment){
            abort(404);
        }
        $data['assesment'] = $assesment;
        return view('bujp.assesment.edit',$data);
    }
    
    public function update(Request $request,$assesmentId){

        try {
            DB::beginTransaction();
            Assesment::where('id',$assesmentId)->update([
                'date' => $request->date,
                'triwulan' => $request->triwulan,
                'contract' => $request->contract,
            ]);

            DB::commit();
            Alert::success('Update Berhasil', 'Assesment berhasil diubah!');
            return redirect()->route('bujp.assesment.index');
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }

    public function destroy($assesmentId){

        try {

            DB::beginTransaction();
            
            Assesment::where('id',$assesmentId)->delete();
            SignCategoryAssesment::where('assesment_id',$assesmentId)->delete();
            SignQuestionAssesment::where('assesment_id',$assesmentId)->delete();
            SignLevelAssesment::where('assesment_id',$assesmentId)->delete();

            DB::commit();
            Alert::success('Delete Berhasil', 'Assesment berhasil dihapus!');
            return redirect()->route('bujp.assesment.index');

        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }

    public function send($assesmentId){

        try {
            DB::beginTransaction();
            Assesment::where('id',$assesmentId)->update([
                'send_status' => 2,
                'send_date' => now(),
            ]);

            DB::commit();
            Alert::success('Berhasil Dikirim', 'Assesment berhasil dikirim!');
            return redirect()->route('bujp.assesment.index');
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }

    public function show($assesmentId){
        $assesment = Assesment::where('id', $assesmentId)->first();
        if (!$assesment) {
            abort(404);
        }
        if($assesment->send_status != 1){
            Alert::warning('Warning', 'Assesment sudah dikirm!');
            return redirect()->route('bujp.assesment.index');
        }
        $data['categories'] = SignCategoryAssesment::with('questions','questions.levels')->where('assesment_id',$assesmentId)->get();
        return view('bujp.assesment.show',$data);
    }

    public function preview($assesmentId){

        $data['categories'] = SignCategoryAssesment::with('questions','questions.levels')->where('assesment_id',$assesmentId)->get();
        return view('bujp.assesment.preview',$data);
    }

    public function report($assesmentId){

        $assesment = Assesment::find($assesmentId);
        $categories = SignCategoryAssesment::with('questions', 'questions.levels')
        ->where('assesment_id', $assesmentId)
        ->get()
        ->map(function ($category) {
            // Hitung rata-rata untuk setiap kategori berdasarkan level dalam pertanyaan
            $category->average = number_format($category->questions->avg('level'),2);
    
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
        return view('bujp.assesment.report',$data);
    }

    public function updateQuestion(Request $request,$questionId){

        try {

            DB::beginTransaction();

            $request->validate([
                'level_'.$questionId => 'required',
                'attachment_file_'.$questionId => 'mimes:pdf'
            ],[
                'level_'.$questionId.'.required' => 'Level harus diisi!',
                'attachment_file_'.$questionId.'.mimes' => 'File harus beresktensi .pdf!',
            ]);

            $signQuestion = SignQuestionAssesment::where('id',$questionId)->first();

            $questionFile = null;
            
            if($request->hasFile('attachment_file_'.$questionId))
            {      
                $file= $request->file('attachment_file_'.$questionId);
                $file_name = 'question-file-' . time() .'.'. $file->getClientOriginalExtension();
                if ($signQuestion->attachment_file) {
                    unlink(public_path('uploads/attachment_file_question_file/'.$signQuestion->attachment_file));
                }
                $file->move(public_path('uploads/attachment_file_question_file/'),$file_name);   
                $questionFile = $file_name;
            }
            
            $signQuestion->level = $request->input('level_'.$questionId);
            $signQuestion->note = $request->input('note_'.$questionId);
            $signQuestion->attachment_file = $questionFile ?? $signQuestion->attachment_file;
            $signQuestion->save();
            DB::commit();
            
            Alert::success('Update Berhasil', 'Assesment berhasil diupdate!');
            return redirect()->route('bujp.assesment.show',['assesmentId'=>$signQuestion->assesment_id,'signCategoryId'=>$signQuestion->sign_category_id]);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }
}
