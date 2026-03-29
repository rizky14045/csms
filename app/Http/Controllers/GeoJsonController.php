<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Province;
use Illuminate\Http\Request;

class GeoJsonController extends Controller
{
    // ambil geojson provinsi
    public function getProvince($id)
    {
        $province = Province::findOrFail($id);

        return response()->json([
            'geometry' => json_decode($province->geometry)
        ]);
    }


    // list kota (tanpa geo biar ringan)
    public function getCities($province_id)
    {
        $cities = City::select('id','name')
            ->where('province_id', $province_id)
            ->get();

        return response()->json($cities);
    }

    // ambil geojson kota
    public function getCity($id)
    {
        $city = City::findOrFail($id);

        return response()->json([
            'geometry' => json_decode($city->geometry)
        ]);
    }
}
