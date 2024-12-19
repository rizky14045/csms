<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class KeamananController extends Controller
{
    public function index(){
        return view('admin.keamanan.index');
    }
    public function show(){
        return view('admin.keamanan.show');
    }
}
