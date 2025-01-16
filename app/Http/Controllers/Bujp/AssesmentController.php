<?php

namespace App\Http\Controllers\Bujp;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AssesmentController extends Controller
{
    public function index(){
        return view('bujp.assesment.index');
    }

    public function create(){
        return view('bujp.assesment.create');
    }

    public function edit(){
        return view('bujp.assesment.edit');
    }
}
