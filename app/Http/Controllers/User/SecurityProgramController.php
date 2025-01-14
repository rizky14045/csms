<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use App\Models\Security;
use Illuminate\Http\Request;
use App\Models\SecurityProgram;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class SecurityProgramController extends Controller
{
    public function index(){

        $userId = Auth::user()->id;
        $data['programs'] = SecurityProgram::with('programs')->where('user_id',$userId)->paginate(25);
        return view('user.security-program.index',$data);

    }

    public function create(){
        return view('user.security-program.create');
    }

    public function store(Request $request){

        try {
            DB::beginTransaction();

            $userId = Auth::user()->id;
            $request->validate([
                'program_name' => 'required',
                'description' => 'required',
                'year' => 'required',
         
            ],[
                'program_name.required' => 'Nama program harus diisi!',
                'description.required' => 'Description kerja harus diisi!',
                'year.required' => 'Tahun harus diisi!',
            ]);

            SecurityProgram::create([
                'user_id' => $userId,
                'program_name' => $request->program_name,
                'description' => $request->description,
                'year' => $request->year,
            ]);
            
            DB::commit();
            Alert::success('Tambah Berhasil', 'Program Keamanan berhasil dibuat!');
            return redirect()->route('user.security-program.index');
            
        } catch (\Throwable $th) {

            DB::rollback();
            throw $th;
        }
    }

    public function edit($id){

        $userId = Auth::user()->id;
        $program = SecurityProgram::where('id', $id)->where('user_id',$userId)->first();
        if(!$program){
            abort(404);
        }
        $data['program'] = $program;
        return view('user.security-program.edit',$data);
    }

    public function update(Request $request,$id){

        try {

            DB::beginTransaction();

            $userId = Auth::user()->id;
            $request->validate([
                'program_name' => 'required',
                'description' => 'required',
                'year' => 'required',
         
            ],[
                'program_name.required' => 'Nama program harus diisi!',
                'description.required' => 'Description kerja harus diisi!',
                'year.required' => 'Tahun harus diisi!',
            ]);

            $program = SecurityProgram::where('id',$id)->where('user_id',$userId)->first();
            $program->program_name = $request->program_name;
            $program->description = $request->description;
            $program->year = $request->year;
            $program->save();
            
            DB::commit();
            Alert::success('Update Berhasil', 'Program Keamanan berhasil diubah!');
            return redirect()->route('user.security-program.index');
            
        } catch (\Throwable $th) {

            DB::rollback();
            throw $th;
        }
    }

    public function destroy($id){
        
        try {
            DB::beginTransaction();

            $userId = Auth::user()->id;
            $program = SecurityProgram::where('id',$id)->where('user_id',$id)->first();
            $program->delete();
            
            DB::commit();
            Alert::success('Delete Berhasil', 'Program Keamanan berhasil dihapus!');
            return redirect()->route('user.security-program.index');
            
        } catch (\Throwable $th) {

            DB::rollback();
            throw $th;
        }
    }
}
