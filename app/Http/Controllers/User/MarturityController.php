<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MarturityController extends Controller
{
    public function index(){
        return view('user.marturity.index');
    }

    public function create(){
        return view('user.marturity.create');
    }

    public function edit(){
        return view('user.marturity.edit');
    }
    public function show(){
        return view('user.marturity.show');
    }
}
