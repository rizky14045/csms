<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AssesmentController extends Controller
{
    public function index(){
        return view('admin.assesment.index');
    }

    public function create(){
        return view('admin.assesment.create');
    }

    public function edit(){
        return view('admin.assesment.edit');
    }
    public function show(){
        return view('admin.assesment.show');
    }
}
