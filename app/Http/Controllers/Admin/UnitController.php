<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UnitController extends Controller
{
    public function index(){
        return view('admin.unit.index');
    }

    public function create(){
        return view('admin.unit.create');
    }

    public function edit(){
        return view('admin.unit.edit');
    }
}
