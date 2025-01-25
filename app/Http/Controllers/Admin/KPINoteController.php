<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\SubArea;
use App\Models\Note;
use App\Models\LevelAssesment;
use App\Models\Level;
use App\Models\QuestionAssesment;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use RealRashid\SweetAlert\Facades\Alert;

class KPINoteController extends Controller
{

    public function create($levelId){

        $data['levelId'] = $levelId;
        return view('admin.kpi-note.create',$data);
    }

    public function store(Request $request,$levelId){

        try {
            DB::beginTransaction();

            $request->validate([
                'note' => 'required',
            ],[
                'note.required' => 'Note harus diisi!',
            ]);

            $lastNote = Note::where('type','kpi')->latest()->first();
            $order = $lastNote ? $lastNote->order + 1 : 1; 
            Note::create([
                'level_id' => $levelId,
                'note' => $request->note,
                'order' => $order,
                'type' => 'kpi',
            ]);
            
            DB::commit();
            Alert::success('Tambah Berhasil', 'Note berhasil dibuat!');
            return redirect()->route('admin.kpi-area.index');
            
        } catch (\Throwable $th) {

            DB::rollback();
            throw $th;
            Alert::error('Tambah Gagal', 'Note gagal dibuat!');
            return redirect()->route('admin.kpi-area.index');
        }
    }

    public function edit($noteId,$levelId){

        $note = Note::where('level_id',$levelId)->where('id', $noteId)->first();
        if(!$note){
            abort(404);
        }
        $data['note'] = $note;
        $data['levelId'] = $levelId;
        return view('admin.kpi-note.edit',$data);
    }

    public function update(Request $request,$noteId,$levelId){

        try {
            DB::beginTransaction();

            $note = Note::where('level_id',$levelId)->where('id',$noteId)->first();

            $request->validate([
                'note' => 'required',
            ],[
                'note.required' => 'Note harus diisi!',
            ]);

            $note->note = $request->note;
            $note->save();
            
            DB::commit();
            Alert::success('Update Berhasil', 'Note berhasil diubah!');
            return redirect()->route('admin.kpi-area.index');
            
        } catch (\Throwable $th) {

            DB::rollback();
            throw $th;
            Alert::error('Update Gagal', 'Note gagal diubah!');
            return redirect()->route('admin.kpi-area.index');
        }
    }

    public function destroy($levelId,$noteId){

        try {
            DB::beginTransaction();

            $note = Note::where('level_id',$levelId)->where('id',$noteId)->first();
            $note->delete();
            
            DB::commit();
            Alert::success('Delete Berhasil', 'Note berhasil dihapus!');
            return redirect()->route('admin.kpi-area.index');
            
        } catch (\Throwable $th) {

            DB::rollback();
            Alert::error('Delete Gagal', 'Note gagal dihapus!');
            return redirect()->route('admin.kpi-area.index');
        }
    }
}
