<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\SubArea;
use App\Models\Note;
use App\Models\Level;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Validation\LevelValidation;
use App\Services\Level\LevelService;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;

class MarturityLevelController extends Controller
{
    protected $levelService;

    public function __construct(LevelService $levelService)
    {
        $this->levelService = $levelService;

        $this->middleware('can:create.marturity.level')->only(['create', 'store']);
        $this->middleware('can:edit.marturity.level')->only(['edit', 'update']);
        $this->middleware('can:delete.marturity.level')->only(['destroy']);
    }

    protected function validator(array $data, $validation, array $messages = [])
    {
        return Validator::make($data, $validation, $messages);
    }

    public function create(SubArea $subArea){

        $data['subArea'] = $subArea;
        return view('admin.marturity-level.create',$data);
    }

    public function store(Request $request,SubArea $subArea){
        try {
            // Validation rules
            $validator = $this->validator($request->all(), LevelValidation::rulesForCreate(), LevelValidation::messages());
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $type = 'marturity';
            $request->merge(['type' => $type]);
            $this->levelService->createLevel($request->all(), $subArea);

            Alert::success('Tambah Berhasil', 'Level berhasil dibuat!');
            return redirect()->route('admin.marturity-area.index');
            
        } catch (\Throwable $th) {
            Alert::error('Tambah Gagal', 'Level gagal dibuat!');
            return redirect()->route('admin.marturity-area.index');
        }
    }

    public function edit(Level $level, SubArea $subArea){
        $data['level'] = $level;
        $data['subArea'] = $subArea;
        return view('admin.marturity-level.edit',$data);
    }

    public function update(Request $request,Level $level, SubArea $subArea){
        try {
            // Validation rules
            $validator = $this->validator($request->all(), LevelValidation::rulesForUpdate(), LevelValidation::messages());
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $this->levelService->updateLevel($level, $request->all());

            Alert::success('Update Berhasil', 'Level berhasil diubah!');
            return redirect()->route('admin.marturity-area.index');
            
        } catch (\Throwable $th) {
            Alert::error('Update Gagal', 'Level gagal diubah!');
            return redirect()->route('admin.marturity-area.index');
        }
    }

    public function destroy(Level $level, SubArea $subArea){
        try {
            $this->levelService->deleteLevel($level);

            Alert::success('Delete Berhasil', 'Level berhasil dihapus!');
            return redirect()->route('admin.marturity-area.index');
        } catch (\Throwable $th) {
            Alert::error('Delete Gagal', 'Level gagal dihapus!');
            return redirect()->route('admin.marturity-area.index');
        }
    }
}
