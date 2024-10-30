<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ListWorkController extends Controller
{
    public function index(){
        return view('user.list-work.index');
    }

    public function create(){
        return view('user.list-work.create');
    }

    public function edit(){
        return view('user.list-work.edit');
    }

}
