<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PraqualificationController extends Controller
{
    public function index(){
        return view('user.praqualification.index');
    }

    public function create(){
        return view('user.praqualification.create');
    }

    public function edit(){
        return view('user.praqualification.edit');
    }
}
