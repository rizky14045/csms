<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Attribute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use RealRashid\SweetAlert\Facades\Alert;

class AttributeController extends Controller
{
    public function index(){
        $data['attributes'] = Attribute::paginate(25);
        return view('admin.attribute.index',$data);
    }

    public function create(){
        return view('admin.attribute.create');
    }

    public function store(Request $request){
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

            Attribute::create([
                'name' => $request->name,
                'status_ownership' => $request->status_ownership,
                'unit' => $request->unit,
                'standard_contract' => $request->standard_contract,
                'type_attribute' => 'Administrasi',
            ]);
            
            DB::commit();
            Alert::success('Tambah Berhasil', 'Atribut berhasil dibuat!');
            return redirect()->route('admin.attribute.index');
            
        } catch (\Throwable $th) {

            DB::rollback();
            throw $th;
        }
    }

    public function edit($id){

        $attribute = Attribute::where('id', $id)->first();
        if(!$attribute){
            abort(404);
        }
        $data['attribute'] = $attribute;
        return view('admin.attribute.edit',$data);
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
            
            $attribute = Attribute::where('id',$id)->first();
            $attribute->name = $request->name;
            $attribute->status_ownership = $request->status_ownership;
            $attribute->unit = $request->unit;
            $attribute->standard_contract = $request->standard_contract;
            $attribute->save();
            
            DB::commit();
            Alert::success('Update Berhasil', 'Atribut berhasil diubah!');
            return redirect()->route('admin.attribute.index');
            
        } catch (\Throwable $th) {

            DB::rollback();
            throw $th;
        }
    }

    public function destroy($id){
        
        try {
            DB::beginTransaction();

            $attribute = Attribute::where('id',$id)->first();
            $attribute->delete();
            
            DB::commit();
            Alert::success('Delete Berhasil', 'Atribut berhasil dihapus!');
            return redirect()->route('admin.attribute.index');
            
        } catch (\Throwable $th) {

            DB::rollback();
            throw $th;
        }
    }
}
