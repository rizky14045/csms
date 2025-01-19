<?php

namespace App\Http\Controllers\User;

use App\Models\Assesment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\SignCategoryAssesment;
use RealRashid\SweetAlert\Facades\Alert;

class AssesmentController extends Controller
{
    public function index(){
        $userId = Auth::guard('web')->user()->id;
        $data['assesments'] = Assesment::where('send_status','!=',1)->where('unit_id', $userId)->paginate(25);
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
}
