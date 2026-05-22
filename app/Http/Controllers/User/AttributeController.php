<?php

namespace App\Http\Controllers\User;

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

        $this->middleware('can:view.attribute.unit')->only(['index']);
        $this->middleware('can:create.attribute.unit')->only(['create', 'store']);
        $this->middleware('can:edit.attribute.unit')->only(['edit', 'update']);
        $this->middleware('can:delete.attribute.unit')->only(['destroy']);
    }

    protected function validator(array $data, $validation, array $messages = [])
    {
        return Validator::make($data, $validation, $messages);
    }
    
    public function index(){
        $user = auth()->user();
        $result = $this->attributeService->getAllAttribute(25, true, null, $user->id, $user->unit_id);
        $data['attributes'] = getPaginate($result);
        $data['request'] = request();

        return view('user.attribute.index', $data);
    }

    public function create(){
        return view('user.attribute.create');
    }

    public function store(Request $request){

        try {
            // Validation rules
            $validator = $this->validator($request->all(), AttributeValidation::rulesForCreateAttributeUnit(), AttributeValidation::messages());
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $user = auth()->user();
            $request->merge([
                'user_id' => $user->id,
                'unit_id' => $user->unit_id,
            ]);
            $this->attributeService->createAttribute($request->all());

            Alert::success('Tambah Berhasil', 'Atribut berhasil dibuat!');
            return redirect()->route('user.attribute.index');
            
        } catch (\Throwable $th) {
            Alert::error('Tambah Gagal', 'Atribut gagal dibuat!');
            return redirect()->route('user.attribute.index');
        }
    }

    public function edit(Attribute $attribute){
        $result = $this->attributeService->getAttributeById($attribute->id, auth()->user()->id);
        $status = getStatus($result);
        if(!$status){
            return abort(404);
        }
        
        $data['attribute'] = $attribute;
        return view('user.attribute.edit',$data);
    }

    public function update(Request $request, Attribute $attribute){
        try {
            // Validation rules
            $validator = $this->validator($request->all(), AttributeValidation::rulesForUpdateAttributeUnit(), AttributeValidation::messages());
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $result = $this->attributeService->getAttributeById($attribute->id, auth()->user()->id);
            $status = getStatus($result);
            if(!$status){
                return abort(404);
            }
            
            $this->attributeService->updateAttribute($attribute, $request->all());

            Alert::success('Update Berhasil', 'Atribut berhasil diubah!');
            return redirect()->route('user.attribute.index');
            
        } catch (\Throwable $th) {
            Alert::error('Update Gagal', 'Atribut gagal diubah!');
            return redirect()->route('user.attribute.index');
        }
    }

    public function destroy(Attribute $attribute){
        
        try {
            $result = $this->attributeService->getAttributeById($attribute->id, auth()->user()->id);
            $status = getStatus($result);
            if(!$status){
                return abort(404);
            }

             $this->attributeService->deleteAttribute($attribute);

            Alert::success('Delete Berhasil', 'Atribut berhasil dihapus!');
            return redirect()->route('user.attribute.index');
            
        } catch (\Throwable $th) {
            Alert::error('Delete Gagal', 'Atribut gagal dihapus!');
            return redirect()->route('user.attribute.index');
        }
    }
}
