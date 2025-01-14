<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use App\Models\Security;
use Illuminate\Http\Request;
use App\Models\SecurityProgram;
use Illuminate\Support\Facades\DB;
use App\Models\MainSecurityProgram;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class MainSecurityProgramController extends Controller
{
    public function index($programId){

        $userId = Auth::user()->id;
        $data['programId'] = $programId;
        $data['mains'] = MainSecurityProgram::where('program_id',$programId)->where('user_id',$userId)->paginate(25);

        return view('user.main-security-program.index',$data);

    }

    public function create($programId){

        $data['programId'] = $programId;
        return view('user.main-security-program.create',$data);

    }

    public function store(Request $request,$programId){

        try {
            DB::beginTransaction();

            $userId = Auth::user()->id;
            $request->validate([
                'program_name' => 'required',
                'start_month' => 'required',
                'start_week' => 'required',
                'end_month' => 'required',
                'end_week' => 'required',
         
            ],[
                'program_name.required' => 'Nama program harus diisi!',
                'start_month.required' => 'Bulan Planning kerja harus diisi!',
                'start_week.required' => 'Minggu Planning harus diisi!',
                'end_month.required' => 'Bulan Planning kerja harus diisi!',
                'end_week.required' => 'Minggu Planning harus diisi!',
            ]);

            MainSecurityProgram::create([
                'program_id' => $programId,
                'user_id' => $userId,
                'program_name' => $request->program_name,
                'start_month' => $request->start_month,
                'start_week' => $request->start_week,
                'end_month' => $request->end_month,
                'end_week' => $request->end_week,
            ]);
            
            DB::commit();
            Alert::success('Tambah Berhasil', 'Program Keamanan berhasil dibuat!');
            return redirect()->route('user.main-security-program.index',['programId' => $programId]);
            
        } catch (\Throwable $th) {

            DB::rollback();
            throw $th;
        }
    }

    public function edit($programId,$id){

        $userId = Auth::user()->id;
        $main = MainSecurityProgram::where('id', $id)->where('user_id',$userId)->first();
        if(!$main){
            abort(404);
        }
        $data['main'] = $main;
        $data['programId'] = $programId;
        return view('user.main-security-program.edit',$data);
    }

    public function update(Request $request,$programId,$id){

        try {

            DB::beginTransaction();

            $userId = Auth::user()->id;
            $request->validate([
                'program_name' => 'required',
                'start_month' => 'required',
                'start_week' => 'required',
                'end_month' => 'required',
                'end_week' => 'required',
         
            ],[
                'program_name.required' => 'Nama program harus diisi!',
                'start_month.required' => 'Bulan Planning kerja harus diisi!',
                'start_week.required' => 'Minggu Planning harus diisi!',
                'end_month.required' => 'Bulan Planning kerja harus diisi!',
                'end_week.required' => 'Minggu Planning harus diisi!',
            ]);


            $program = MainSecurityProgram::where('id',$id)->where('user_id',$userId)->first();
            $program->program_name = $request->program_name;
            $program->start_month = $request->start_month;
            $program->start_week = $request->start_week;
            $program->end_month = $request->end_month;
            $program->end_week = $request->end_week;
            $program->save();
            
            DB::commit();
            Alert::success('Update Berhasil', 'Program Keamanan berhasil diubah!');
            return redirect()->route('user.main-security-program.index',['programId'=>$programId]);
            
        } catch (\Throwable $th) {

            DB::rollback();
            throw $th;
        }
    }

    public function destroy($programId,$id){
        
        try {
            DB::beginTransaction();

            $userId = Auth::user()->id;
            $program = MainSecurityProgram::where('id',$id)->where('program_id',$programId)->first();
            $program->delete();
            
            DB::commit();
            Alert::success('Delete Berhasil', 'Program Keamanan berhasil dihapus!');
            return redirect()->route('user.main-security-program.index',['programId'=>$programId]);
            
        } catch (\Throwable $th) {

            DB::rollback();
            throw $th;
        }
    }

    public function visual($programId){

        $userId = Auth::user()->id;
        $data['programId'] = $programId;
        $data['securityProgram'] = SecurityProgram::where('id',$programId)->where('user_id',$userId)->first();
        $data['programs'] = MainSecurityProgram::where('program_id',$programId)->where('user_id',$userId)->get();

        return view('user.main-security-program.visual',$data);
    }
}
