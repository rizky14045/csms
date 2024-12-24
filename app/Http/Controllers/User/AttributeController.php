<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use App\Models\Attribute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class AttributeController extends Controller
{
    public function index(){

        $userId = Auth::user()->id;
        $data['attributes'] = Attribute::where('user_id',$userId)->paginate(25);
        return view('user.attribute.index',$data);

    }

    public function create(){
        return view('user.attribute.create');
    }

    public function store(Request $request){

        try {
            DB::beginTransaction();

            $userId = Auth::user()->id;
            $request->validate([
                'name' => 'required',
                'status_ownership' => 'required',
                'unit' => 'required',
                'standard_contract' => 'required',
                'type_attribute' => 'required',
            ],[
                'name.required' => 'Nama harus diisi!',
                'status_ownership.required' => 'Status kepemilikan harus diisi!',
                'unit.required' => 'Satuan harus diisi!',
                'standard_contract.required' => 'Jumlah standard kontrak harus diisi!',
                'type_attribute.required' => 'Tipe atribut harus diisi!',
            ]);

            Attribute::create([
                'user_id' => $userId,
                'name' => $request->name,
                'status_ownership' => $request->status_ownership,
                'unit' => $request->unit,
                'standard_contract' => $request->standard_contract,
                'type_attribute' => $request->type_attribute,
            ]);
            
            DB::commit();
            Alert::success('Tambah Berhasil', 'Atribut berhasil dibuat!');
            return redirect()->route('user.attribute.index');
            
        } catch (\Throwable $th) {

            DB::rollback();
            throw $th;
        }
    }

    public function edit($id){

        $userId = Auth::user()->id;
        $attribute = Attribute::where('id', $id)->where('user_id',$userId)->first();
        if(!$attribute){
            abort(404);
        }
        $data['attribute'] = $attribute;
        return view('user.attribute.edit',$data);
    }

    public function update(Request $request,$id){

        try {

            DB::beginTransaction();

            $request->validate([
                'name' => 'required',
                'status_ownership' => 'required',
                'unit' => 'required',
                'standard_contract' => 'required',
            ],[
                'name.required' => 'Nama harus diisi!',
                'status_ownership.required' => 'Status kepemilikan harus diisi!',
                'unit.required' => 'Satuan harus diisi!',
                'standard_contract.required' => 'Jumlah standard kontrak harus diisi!',
            ]);

            $userId = Auth::user()->id;

            $attribute = Attribute::where('id',$id)->where('user_id',$userId)->first();
            $attribute->name = $request->name;
            $attribute->status_ownership = $request->status_ownership;
            $attribute->unit = $request->unit;
            $attribute->standard_contract = $request->standard_contract;
            $attribute->type_attribute = $request->type_attribute;
            $attribute->save();
            
            DB::commit();
            Alert::success('Update Berhasil', 'Atribut berhasil diubah!');
            return redirect()->route('user.attribute.index');
            
        } catch (\Throwable $th) {

            DB::rollback();
            throw $th;
        }
    }

    public function destroy($id){
        
        try {
            DB::beginTransaction();
            
            $userId = Auth::user()->id;
            $attribute = Attribute::where('id',$id)->where('user_id',$id)->first();
            $attribute->delete();
            
            DB::commit();
            Alert::success('Delete Berhasil', 'Atribut berhasil dihapus!');
            return redirect()->route('user.attribute.index');
            
        } catch (\Throwable $th) {

            DB::rollback();
            throw $th;
        }
    }
}
