<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FaqController extends Controller
{
    public function index(){
        return view('admin.faq.index');
    }

    public function create(){
        return view('admin.faq.create');
    }

    public function edit(){
        return view('admin.faq.edit');
    }
}
