<?php

namespace App\Http\Controllers\Admin;

use App\Models\Marturity;
use Illuminate\Http\Request;
use App\Models\MarturityArea;
use App\Http\Controllers\Controller;

class MarturityController extends Controller
{
    public function index(){
        $data['marturities'] = Marturity::with('unit')->where('send_status',true)->latest()->paginate(10);
        return view('admin.marturity.index',$data);
    }
    public function show($marturityId){

        $marturity = Marturity::where('id', $marturityId)->first();
        if (!$marturity) {
            abort(404);
        }
        $data['areas'] = MarturityArea::with('subAreas','subAreas.levels','subAreas.levels.notes')->where('marturity_id', $marturity->id)->get();

        return view('admin.marturity.show',$data);
    }
}
