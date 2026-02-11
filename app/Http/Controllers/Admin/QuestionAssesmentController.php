<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\CategoryAssesment;
use App\Models\QuestionAssesment;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use RealRashid\SweetAlert\Facades\Alert;
use App\Http\Validation\QuestionAssesmentValidation;
use App\Services\QuestionAssesment\QuestionAssesmentService;
use Illuminate\Support\Facades\Validator;

class QuestionAssesmentController extends Controller
{
    protected $questionAssesmentService;

    public function __construct(QuestionAssesmentService $questionAssesmentService)
    {
        $this->questionAssesmentService = $questionAssesmentService;

        $this->middleware('can:create.question.assesment')->only(['create', 'store']);
        $this->middleware('can:edit.question.assesment')->only(['edit', 'update']);
        $this->middleware('can:delete.question.assesment')->only(['destroy']);
    }

    protected function validator(array $data, $validation, array $messages = [])
    {
        return Validator::make($data, $validation, $messages);
    }

    public function create(CategoryAssesment $category_assesment){
        $data['category'] = $category_assesment;
        return view('admin.question-assesment.create',$data);

    }

    public function store(Request $request, CategoryAssesment $category_assesment){
        try {
            // Validation rules
            $validator = $this->validator($request->all(), QuestionAssesmentValidation::rulesForCreate(), QuestionAssesmentValidation::messages());
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $this->questionAssesmentService->createQuestionAssesment($request->all(), $category_assesment);

            Alert::success('Tambah Berhasil', 'Question assesment berhasil dibuat!');
            return redirect()->route('admin.category-assesment.index');
            
        } catch (\Throwable $th) {
            Alert::error('Tambah Gagal', 'Question assesment gagal dibuat!');
            return redirect()->route('admin.category-assesment.index');
        }
    }

    public function edit(QuestionAssesment $question_assesment, CategoryAssesment $category_assesment){
        $question = QuestionAssesment::where('category_id', $category_assesment->id)->where('id',$question_assesment->id)->first();
        if(!$question){
            abort(404);
        }
        $data['categoryId'] = $category_assesment->id;
        $data['question'] = $question;
        return view('admin.question-assesment.edit',$data);
    }

    public function update(Request $request,QuestionAssesment $question_assesment, CategoryAssesment $category_assesment){

        try {
            // Validation rules
            $validator = $this->validator($request->all(), QuestionAssesmentValidation::rulesForUpdate(), QuestionAssesmentValidation::messages());
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $this->questionAssesmentService->updateQuestionAssesment($question_assesment, $request->all());

            Alert::success('Update Berhasil', 'Question berhasil diubah!');
            return redirect()->route('admin.category-assesment.index');
            
        } catch (\Throwable $th) {
            Alert::error('Update Gagal', 'Question assesment gagal diubah!');
            return redirect()->route('admin.category-assesment.index');
        }
    }

    public function destroy(QuestionAssesment $question_assesment, CategoryAssesment $category_assesment){
        try {
            $this->questionAssesmentService->deleteQuestionAssesment($question_assesment);
            Alert::success('Delete Berhasil', 'Question berhasil dihapus!');
            return redirect()->route('admin.category-assesment.index');
            
        } catch (\Throwable $th) {

            DB::rollback();
            Alert::error('Delete Gagal', 'Question assesment gagal dihapus!');
            return redirect()->route('admin.category-assesment.index');
        }
    }
}
