<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\LevelAssesment;
use App\Http\Validation\LevelAssesmentValidation;
use App\Models\QuestionAssesment;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use RealRashid\SweetAlert\Facades\Alert;
use App\Services\LevelAssesment\LevelAssesmentService;
use Illuminate\Support\Facades\Validator;

class LevelAssesmentController extends Controller
{
    protected $levelAssesmentService;

    public function __construct(LevelAssesmentService $levelAssesmentService)
    {
        $this->levelAssesmentService = $levelAssesmentService;

        $this->middleware('can:create.level.assesment')->only(['create', 'store']);
        $this->middleware('can:edit.level.assesment')->only(['edit', 'update']);
        $this->middleware('can:delete.level.assesment')->only(['destroy']);
    }

    protected function validator(array $data, $validation, array $messages = [])
    {
        return Validator::make($data, $validation, $messages);
    }

    public function create(QuestionAssesment $question_assesment){
        $data['question'] = $question_assesment;
        return view('admin.level-assesment.create',$data);

    }

    public function store(Request $request, QuestionAssesment $question_assesment){

        try {
            // Validation rules
            $validator = $this->validator($request->all(), LevelAssesmentValidation::rulesForCreate(), LevelAssesmentValidation::messages());
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $this->levelAssesmentService->createLevelAssesment($request->all(), $question_assesment);
            
            Alert::success('Tambah Berhasil', 'Level assesment berhasil dibuat!');
            return redirect()->route('admin.category-assesment.index');
            
        } catch (\Throwable $th) {
            Alert::error('Tambah Gagal', 'Level assesment gagal dibuat!');
            return redirect()->route('admin.category-assesment.index');
        }
    }

    public function edit(LevelAssesment $level_assesment, QuestionAssesment $question_assesment){
        $data['question'] = $question_assesment;
        $data['level'] = $level_assesment;
        return view('admin.level-assesment.edit',$data);
    }

    public function update(Request $request, LevelAssesment $level_assesment, QuestionAssesment $question_assesment){

        try {
            // Validation rules
            $validator = $this->validator($request->all(), LevelAssesmentValidation::rulesForUpdate(), LevelAssesmentValidation::messages());
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            
            $this->levelAssesmentService->updateLevelAssesment($level_assesment, $request->all());

            Alert::success('Update Berhasil', 'Level berhasil diubah!');
            return redirect()->route('admin.category-assesment.index');
            
        } catch (\Throwable $th) {
            Alert::error('Update Gagal', 'Level assesment gagal diubah!');
            return redirect()->route('admin.category-assesment.index');
        }
    }

    public function destroy(LevelAssesment $level_assesment, QuestionAssesment $question_assesment){
        try {
            $this->levelAssesmentService->deleteLevelAssesment($level_assesment);

            Alert::success('Delete Berhasil', 'Level berhasil dihapus!');
            return redirect()->route('admin.category-assesment.index');
            
        } catch (\Throwable $th) {
            Alert::error('Delete Gagal', 'Level assesment gagal dihapus!');
            return redirect()->route('admin.category-assesment.index');
        }
    }
}
