<?php

namespace App\Http\Controllers\Admin;

use App\Models\Attribute;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Validation\AttributeValidation;
use App\Services\Attribute\AttributeService;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;

class AttributeController extends Controller
{
    protected $attributeService;

    public function __construct(AttributeService $attributeService)
    {
        $this->attributeService = $attributeService;

        $this->middleware('can:view.attribute')->only(['index']);
        $this->middleware('can:create.attribute')->only(['create', 'store']);
        $this->middleware('can:edit.attribute')->only(['edit', 'update']);
        $this->middleware('can:delete.attribute')->only(['destroy']);
    }

    protected function validator(array $data, $validation, array $messages = [])
    {
        return Validator::make($data, $validation, $messages);
    }

    public function index(){
        $result = $this->attributeService->getAllAttribute(25, true, 'Administrasi');
        $data['attributes'] = getPaginate($result);
        $data['request'] = request();
        return view('admin.attribute.index',$data);
    }

    public function create(){
        return view('admin.attribute.create');
    }

    public function store(Request $request){
        try {
            // Validation rules
            $validator = $this->validator($request->all(), AttributeValidation::rulesForCreate(), AttributeValidation::messages());
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $type_attribute = 'Administrasi';
            $request->merge(['type_attribute' => $type_attribute]);

            $this->attributeService->createAttribute($request->all());

            Alert::success('Tambah Berhasil', 'Atribut berhasil dibuat!');
            return redirect()->route('admin.attribute.index');
            
        } catch (\Throwable $th) {
            Alert::error('Tambah Gagal', 'Atribut gagal dibuat!');
            return redirect()->route('admin.attribute.index');
        }
    }

    public function edit(Attribute $attribute){
        $data['attribute'] = $attribute;
        return view('admin.attribute.edit',$data);
    }

    public function update(Request $request, Attribute $attribute){

        try {
            // Validation rules
            $validator = $this->validator($request->all(), AttributeValidation::rulesForUpdate(), AttributeValidation::messages());
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $type_attribute = 'Administrasi';
            $request->merge(['type_attribute' => $type_attribute]);
            
            $this->attributeService->updateAttribute($attribute, $request->all());

            Alert::success('Update Berhasil', 'Atribut berhasil diubah!');
            return redirect()->route('admin.attribute.index');
            
        } catch (\Throwable $th) {
            Alert::error('Update Gagal', 'Atribut gagal diubah!');
            return redirect()->route('admin.attribute.index');
        }
    }

    public function destroy(Attribute $attribute){
        
        try {
             $this->attributeService->deleteAttribute($attribute);

            Alert::success('Delete Berhasil', 'Atribut berhasil dihapus!');
            return redirect()->route('admin.attribute.index');
            
        } catch (\Throwable $th) {
            Alert::error('Delete Gagal', 'Atribut gagal dihapus!');
            return redirect()->route('admin.attribute.index');
        }
    }
}
