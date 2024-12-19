<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MonthlyAuditController extends Controller
{
    public function index(){
        return view('admin.monthly-audit.index');
    }

    public function create(){
        return view('admin.monthly-audit.create');
    }

    public function edit(){
        return view('admin.monthly-audit.edit');
    }
    public function show(){
        return view('admin.monthly-audit.show');
    }

}
