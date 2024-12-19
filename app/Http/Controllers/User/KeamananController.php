<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class KeamananController extends Controller
{
    public function index(){
        return view('user.keamanan.index');
    }

    public function create(){
        return view('user.keamanan.create');
    }

    public function edit(){
        return view('user.keamanan.edit');
    }
    public function show(){
        return view('user.keamanan.show');
    }
}
