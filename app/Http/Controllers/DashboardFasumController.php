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
    public function __construct()
    {
        $this->middleware('can:view.fasum.dashboard')->only(['index']);
    }

    public function index(Request $request)
    {
        if(auth()->user()->type == 'user') {
            $unitId = auth()->user()->unit_id;
            $units = Unit::orderBy('name', 'asc')->where(['id'=> $unitId])->get();
        } else {
            $unitId = $request->unit_id;
            // ambil semua unit untuk dropdown
            $units = Unit::orderBy('name', 'asc')->get();
        }

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

            // filter by type
            $typeId = $request->type_id;
            if (!empty($typeId)) {
                $fasum->where('type_id', $typeId);
            }

            $fasum = $fasum->get();

            $fasumTypes = FasumType::select('id', 'name', 'color_code')
                        ->orderBy('name', 'asc')
                        ->get();
            return view('fasum.dashboard', [
                'all_units'   => $units,
                'fasum'   => $fasum,
                'unitId'  => $unitId,
                'typeId'  => $typeId,
                'fasumTypes' => $fasumTypes,
            ]);
    }
}
