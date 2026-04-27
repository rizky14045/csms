<?php

namespace App\Http\Controllers;

use App\Models\Fasum;
use App\Models\FasumType;
use App\Models\SecurityForm;
use App\Models\Unit;
use App\Models\User;
use App\Services\User\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardFasumController extends Controller
{

    public function index(Request $request)
    {
            $unitId = $request->unit_id;

            // ambil semua unit untuk dropdown
            $units = Unit::orderBy('name', 'asc')->get();

            // query fasum + relasi unit
            $fasum = Fasum::select(
                    'fasum.id',
                    'fasum.unit_id',
                    'fasum.name',
                    'fasum.type_id',
                    'fasum.latitude',
                    'fasum.longitude',
                    'fasum.address'
                )
                ->with([
                    'unit:id,name',
                    'type:id,name,color_code'
                ]);

            // filter by unit
            if (!empty($unitId)) {
                $fasum->where('unit_id', $unitId);
            }

            $fasum = $fasum->get();

            $fasumTypes = FasumType::select('id', 'name', 'color_code')
                        ->orderBy('name', 'asc')
                        ->get();
            return view('fasum.dashboard', [
                'all_units'   => $units,
                'fasum'   => $fasum,
                'unitId'  => $unitId,
                'fasumTypes' => $fasumTypes,
            ]);
    }
}
