<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Validation\FasumTypeValidation;
use App\Models\FasumType;
use App\Services\FasumType\FasumTypeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class FasumTypeController extends Controller
{
    protected $fasumTypeService;

    public function __construct(FasumTypeService $fasumTypeService)
    {
        $this->fasumTypeService = $fasumTypeService;
    }

    protected function validator(array $data, $validation, array $messages = [])
    {
        return Validator::make($data, $validation, $messages);
    }

    public function index(){
        $result = $this->fasumTypeService->getAllFasumTypes(25, true);
        $data['types'] = getPaginate($result);
        return view('admin.fasum-type.index',$data);
    }

    public function create(){

        return view('admin.fasum-type.create');
    }

    public function store(Request $request){
        // Validation rules
        $validator = $this->validator($request->all(), FasumTypeValidation::rulesForCreate(), FasumTypeValidation::messages());
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $this->fasumTypeService->createFasumType($request->all());

        Alert::success('Tambah Berhasil', 'Tipe fasilitas umum berhasil dibuat!');
        return redirect()->route('admin.fasum-type.index');
    }

    public function edit(FasumType $fasumType){
        $data['type'] = $fasumType;
        return view('admin.fasum-type.edit',$data);
    }

    public function update(Request $request, FasumType $fasumType){
        // Validation rules
        $validator = $this->validator($request->all(), FasumTypeValidation::rulesForUpdate(), FasumTypeValidation::messages());
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $this->fasumTypeService->updateFasumType($fasumType, $request->all());

        Alert::success('Update Berhasil', 'Tipe fasilitas umum berhasil diubah!');
        return redirect()->route('admin.fasum-type.index');
    }

    public function destroy(FasumType $fasumType){
        $this->fasumTypeService->deleteFasumType($fasumType);

        Alert::success('Hapus Berhasil', 'Tipe fasilitas umum berhasil dihapus!');
        return redirect()->route('admin.fasum-type.index');
    }
}
