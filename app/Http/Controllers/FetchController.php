<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;

class FetchController extends Controller
{
    public function fetchUnitByType(Request $request){
        $typeUnit = $request->type_unit;

        $units = [];
        if($typeUnit) {
            $units = Unit::where('type', $typeUnit)->get();
        }

        return response()->json($units);
    }
}
