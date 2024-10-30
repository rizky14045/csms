<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ListWorkController extends Controller
{
    public function index(){
        return view('admin.list-work.index');
    }

    public function create(){
        return view('admin.list-work.create');
    }

    public function edit(){
        return view('admin.list-work.edit');
    }

}
