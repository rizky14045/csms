<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AssesmentController extends Controller
{
    public function index(){
        return view('user.assesment.index');
    }

    public function create(){
        return view('user.assesment.create');
    }

    public function edit(){
        return view('user.assesment.edit');
    }
}
