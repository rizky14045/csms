<?php

namespace App\Http\Controllers\Admin;

use App\Models\Assesment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SignCategoryAssesment;

class AssesmentController extends Controller
{
    public function index(){
        $data['assesments'] = Assesment::where('send_status',3)->paginate(25);
        return view('admin.assesment.index',$data);
    }

    public function create(){
        return view('admin.assesment.create');
    }

    public function edit(){
        return view('admin.assesment.edit');
    }
    public function show($assesmentId){
        $data['categories'] = SignCategoryAssesment::with('questions','questions.levels')->where('assesment_id',$assesmentId)->get();
        return view('admin.assesment.show',$data);
    }
}
