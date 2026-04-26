<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Validation\FasumValidation;
use App\Models\City;
use App\Models\Fasum;
use App\Models\FasumType;
use App\Models\Province;
use App\Services\Fasum\FasumService;
use App\Services\FasumType\FasumTypeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class FasumController extends Controller
{
    protected $fasumService;

    public function __construct(FasumService $fasumService)
    {
        $this->fasumService = $fasumService;
    }

    protected function validator(array $data, $validation, array $messages = [])
    {
        return Validator::make($data, $validation, $messages);
    }

    public function index(){
        $result = $this->fasumService->getAllFasum(25, true);
        $data['fasums'] = getPaginate($result);
        return view('user.fasum.index',$data);
    }

    public function create(){

        $provinces = Province::select('id','name')->get();
        $data['types'] = FasumType::select('id','name')->get();
        $data['provinces']= $provinces;
        return view('user.fasum.create',$data);
    }

    public function store(Request $request){
        // Validation rules
        $validator = $this->validator($request->all(), FasumValidation::rulesForCreate(), FasumValidation::messages());
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $this->fasumService->createFasum($request->all());

        Alert::success('Tambah Berhasil', 'Fasum berhasil dibuat!');
        return redirect()->route('user.fasum.index');
    }

    public function edit(Fasum $fasum){
        $data['fasum'] = $fasum;
        $data['types'] = FasumType::select('id','name')->get();
        $data['provinces'] = Province::select('id','name')->get();
        $data['cities'] = City::select('id','name')->where('province_id', $fasum->province_id)->get();
        return view('user.fasum.edit',$data);
    }

    public function update(Request $request, Fasum $fasum){
        // Validation rules
        $validator = $this->validator($request->all(), FasumValidation::rulesForUpdate(), FasumValidation::messages());
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $this->fasumService->updateFasum($fasum, $request->all());

        Alert::success('Update Berhasil', 'Fasum berhasil diubah!');
        return redirect()->route('user.fasum.index');
    }

    public function destroy(Fasum $fasum){
        $this->fasumService->deleteFasum($fasum);

        Alert::success('Hapus Berhasil', 'Fasum berhasil dihapus!');
        return redirect()->route('user.fasum.index');
    }
}
