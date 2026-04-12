<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Validation\UnitValidation;
use App\Models\City;
use App\Models\Province;
use App\Models\Unit;
use App\Models\User;
use App\Models\UserProfile;
use App\Services\Unit\UnitService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class UnitController extends Controller
{
    protected $unitService;

    public function __construct(UnitService $unitService)
    {
        $this->unitService = $unitService;
    }

    protected function validator(array $data, $validation, array $messages = [])
    {
        return Validator::make($data, $validation, $messages);
    }

    public function index(){
        $result = $this->unitService->getAllUnit(25, true);
        $data['units_list'] = getPaginate($result);
        return view('admin.unit.index',$data);
    }

    public function create(){

        $provinces = Province::select('id','name')->get();

        $data['provinces']= $provinces;
        return view('admin.unit.create',$data);
    }

    public function store(Request $request){
        // Validation rules
        $validator = $this->validator($request->all(), UnitValidation::rulesForCreate(), UnitValidation::messages());
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $this->unitService->createUnit($request->all());

        Alert::success('Tambah Berhasil', 'Unit berhasil dibuat!');
        return redirect()->route('admin.unit.index');
    }

    public function edit(Unit $unit){
        $data['unit'] = $unit;
        $data['provinces'] = Province::select('id','name')->get();
        $data['cities'] = City::select('id','name')->where('province_id', $unit->province_id)->get();
        return view('admin.unit.edit',$data);
    }

    public function update(Request $request, Unit $unit){
        // Validation rules
        $validator = $this->validator($request->all(), UnitValidation::rulesForUpdate(), UnitValidation::messages());
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $this->unitService->updateUnit($unit, $request->all());

        Alert::success('Update Berhasil', 'Unit berhasil diubah!');
        return redirect()->route('admin.unit.index');
    }
}
