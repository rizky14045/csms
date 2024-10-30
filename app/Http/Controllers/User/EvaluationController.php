<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EvaluationController extends Controller
{
    public function index(){
        return view('user.evaluation.index');
    }

    public function create(){
        return view('user.evaluation.create');
    }

    public function edit(){
        return view('user.evaluation.edit');
    }

}
