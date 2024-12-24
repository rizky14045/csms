<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use App\Models\Security;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class SecurityController extends Controller
{
    public function index(){

        $userId = Auth::user()->id;
        $data['securities'] = Security::where('user_id',$userId)->paginate(25);
        return view('user.security.index',$data);

    }

    public function create(){
        return view('user.security.create');
    }

    public function store(Request $request){

        try {
            DB::beginTransaction();

            $userId = Auth::user()->id;
            $request->validate([
                'name' => 'required',
                'gender' => 'required',
                'unit_work' => 'required',
                'nid' => 'required',
                'registration_number' => 'required',
                'expired_card_date' => 'required',
                'position' => 'required',
                'birth_place' => 'required',
                'birth_date' => 'required',
                'qualification' => 'required',
                'last_education' => 'required',
         
            ],[
                'name.required' => 'Nama harus diisi!',
                'unit_work.required' => 'Unit kerja harus diisi!',
                'nid.required' => 'NID harus diisi!',
                'registration_number.required' => 'Nomor REG KTA harus diisi!',
                'expired_card_date.required' => 'Expired KTA harus diisi!',
                'position.required' => 'Jabatan harus diisi!',
                'birth_place.required' => 'Tempat lahir harus diisi!',
                'birth_date.required' => 'Tanggal lahir harus diisi!',
                'qualification.required' => 'Kualifikasi harus diisi!',
                'last_education.required' => 'Pendidikan terakhir harus diisi!',
                'gender.required' => 'Jenis kelamin harus diisi!',
       
            ]);

            Security::create([
                'user_id' => $userId,
                'name' => $request->name,
                'unit_work' => $request->unit_work,
                'nid' => $request->nid,
                'registration_number' => $request->registration_number,
                'expired_card_date' => $request->expired_card_date,
                'position' => $request->position,
                'birth_place' => $request->birth_place,
                'birth_date' => $request->birth_date,
                'qualification' => $request->qualification,
                'last_education' => $request->last_education,
                'gender' => $request->gender,
                'note' => $request->note,
            ]);
            
            DB::commit();
            Alert::success('Tambah Berhasil', 'Satuan Pengamanan berhasil dibuat!');
            return redirect()->route('user.security.index');
            
        } catch (\Throwable $th) {

            DB::rollback();
            throw $th;
        }
    }

    public function edit($id){

        $userId = Auth::user()->id;
        $security = Security::where('id', $id)->where('user_id',$userId)->first();
        if(!$security){
            abort(404);
        }
        $data['security'] = $security;
        return view('user.security.edit',$data);
    }

    public function update(Request $request,$id){

        try {

            DB::beginTransaction();

            $userId = Auth::user()->id;
            $request->validate([
                'name' => 'required',
                'unit_work' => 'required',
                'nid' => 'required',
                'registration_number' => 'required',
                'expired_card_date' => 'required',
                'position' => 'required',
                'birth_place' => 'required',
                'birth_date' => 'required',
                'qualification' => 'required',
                'last_education' => 'required',
                'gender' => 'required',
                'note' => 'required',
         
            ],[
                'name.required' => 'Nama harus diisi!',
                'unit_work.required' => 'Unit kerja harus diisi!',
                'nid.required' => 'NID harus diisi!',
                'registration_number.required' => 'Nomor REG KTA harus diisi!',
                'expired_card_date.required' => 'Expired KTA harus diisi!',
                'position.required' => 'Jabatan harus diisi!',
                'birth_place.required' => 'Tempat lahir harus diisi!',
                'birth_date.required' => 'Tanggal lahir harus diisi!',
                'qualification.required' => 'Kualifikasi harus diisi!',
                'last_education.required' => 'Pendidikan terakhir harus diisi!',
                'gender.required' => 'Jenis kelamin harus diisi!',
                'note.required' => 'note harus diisi!',
       
            ]);

            $security = Security::where('id',$id)->where('user_id',$userId)->first();
            $security->name = $request->name;
            $security->unit_work = $request->unit_work;
            $security->nid = $request->nid;
            $security->registration_number = $request->registration_number;
            $security->expired_card_date = $request->expired_card_date;
            $security->position = $request->position;
            $security->birth_place = $request->birth_place;
            $security->birth_date = $request->birth_date;
            $security->qualification = $request->qualification;
            $security->last_education = $request->last_education;
            $security->gender = $request->gender;
            $security->note = $request->note;
            $security->save();
            
            DB::commit();
            Alert::success('Update Berhasil', 'Satuan Pengamanan berhasil diubah!');
            return redirect()->route('user.security.index');
            
        } catch (\Throwable $th) {

            DB::rollback();
            throw $th;
        }
    }

    public function destroy($id){
        
        try {
            DB::beginTransaction();

            $userId = Auth::user()->id;
            $security = Security::where('id',$id)->where('user_id',$id)->first();
            $security->delete();
            
            DB::commit();
            Alert::success('Delete Berhasil', 'Satuan Pengamanan berhasil dihapus!');
            return redirect()->route('user.security.index');
            
        } catch (\Throwable $th) {

            DB::rollback();
            throw $th;
        }
    }
}
