<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MarturityController extends Controller
{
    public function index(){
        return view('admin.marturity.index');
    }
    public function show(){
        return view('admin.marturity.show');
    }
}
