<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MonthlyAuditController extends Controller
{
    public function index(){
        return view('user.monthly-audit.index');
    }

    public function create(){
        return view('user.monthly-audit.create');
    }

    public function edit(){
        return view('user.monthly-audit.edit');
    }
    public function show(){
        return view('user.monthly-audit.show');
    }

}
