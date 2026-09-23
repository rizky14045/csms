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
            $units = Unit::whereIn('type', array_map('trim', explode(',', $typeUnit)))->orderBy('name')->get();
        }

        return response()->json($units);
    }
}
