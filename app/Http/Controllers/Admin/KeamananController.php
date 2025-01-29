<?php

namespace App\Http\Controllers\Admin;

use App\Models\Kpi;
use App\Models\KpiArea;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class KeamananController extends Controller
{
    public function index(){
        $data['kpis'] = Kpi::with('unit')->where('send_status',true)->latest()->paginate(10);
        return view('admin.keamanan.index',$data);
    }
    public function show($keamananId){

        $kpi = Kpi::where('id', $keamananId)->first();
        if (!$kpi) {
            abort(404);
        }
        $data['areas'] = KpiArea::with('subAreas','subAreas.levels','subAreas.levels.notes')->where('kpi_id', $kpi->id)->get();

        return view('admin.keamanan.show',$data);
    }
}
