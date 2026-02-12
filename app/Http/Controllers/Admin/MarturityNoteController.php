<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Note;
use App\Http\Controllers\Controller;
use App\Http\Validation\NoteValidation;
use App\Models\Level;
use App\Services\Note\NoteService;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;

class MarturityNoteController extends Controller
{
    protected $noteService;

    public function __construct(NoteService $noteService)
    {
        $this->noteService = $noteService;

        $this->middleware('can:create.marturity.note')->only(['create', 'store']);
        $this->middleware('can:edit.marturity.note')->only(['edit', 'update']);
        $this->middleware('can:delete.marturity.note')->only(['destroy']);
    }

    protected function validator(array $data, $validation, array $messages = [])
    {
        return Validator::make($data, $validation, $messages);
    }

    public function create(Level $level){

        $data['level'] = $level;
        return view('admin.marturity-note.create',$data);
    }

    public function store(Request $request, Level $level){

        try {
            // Validation rules
            $validator = $this->validator($request->all(), NoteValidation::rulesForCreate(), NoteValidation::messages());
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $type = 'marturity';
            $request->merge(['type' => $type]);
            $this->noteService->createNote($request->all(), $level);
            
            Alert::success('Tambah Berhasil', 'Note berhasil dibuat!');
            return redirect()->route('admin.marturity-area.index');
            
        } catch (\Throwable $th) {
            Alert::error('Tambah Gagal', 'Note gagal dibuat!');
            return redirect()->route('admin.marturity-area.index');
        }
    }

    public function edit(Note $note, Level $level){
        $data['note'] = $note;
        $data['level'] = $level;
        return view('admin.marturity-note.edit',$data);
    }

    public function update(Request $request, Note $note, Level $level){

        try {
            // Validation rules
            $validator = $this->validator($request->all(), NoteValidation::rulesForUpdate(), NoteValidation::messages());
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            
            $this->noteService->updateNote($note, $request->all());
            
            Alert::success('Update Berhasil', 'Note berhasil diubah!');
            return redirect()->route('admin.marturity-area.index');
            
        } catch (\Throwable $th) {
            Alert::error('Update Gagal', 'Note gagal diubah!');
            return redirect()->route('admin.marturity-area.index');
        }
    }

    public function destroy(Note $note, Level $level){

        try {
            $this->noteService->deleteNote($note);
            Alert::success('Delete Berhasil', 'Note berhasil dihapus!');
            return redirect()->route('admin.marturity-area.index');
            
        } catch (\Throwable $th) {
            Alert::error('Delete Gagal', 'Note gagal dihapus!');
            return redirect()->route('admin.marturity-area.index');
        }
    }
}
