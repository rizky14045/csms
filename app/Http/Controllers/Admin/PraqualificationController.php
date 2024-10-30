<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PraqualificationController extends Controller
{
    public function index(){
        return view('admin.praqualification.index');
    }

    public function create(){
        return view('admin.praqualification.create');
    }

    public function edit(){
        return view('admin.praqualification.edit');
    }
}
