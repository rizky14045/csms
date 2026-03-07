<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Models\ResponsiblePerson;
use App\Http\Controllers\Controller;
use App\Http\Validation\ResponsiblePersonValidation;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;
use App\Services\ResponsiblePerson\ResponsiblePersonService;

class ResponsiblePersonController extends Controller
{
    protected $responsiblePersonService;

    public function __construct(ResponsiblePersonService $responsiblePersonService)
    {
        $this->responsiblePersonService = $responsiblePersonService;

        $this->middleware('can:create.responsible.person.unit')->only(['create', 'store']);
        $this->middleware('can:edit.responsible.person.unit')->only(['edit', 'update']);
        $this->middleware('can:delete.responsible.person.unit')->only(['destroy']);
    }

    protected function validator(array $data, $validation, array $messages = [])
    {
        return Validator::make($data, $validation, $messages);
    }

    public function create(){

        return view('user.responsible-person.create');

    }
    public function store(Request $request){
        // Validation rules
        $validator = $this->validator($request->all(), ResponsiblePersonValidation::rulesForCreate(), ResponsiblePersonValidation::messages());
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $this->responsiblePersonService->createResponsiblePerson($request->all());

        Alert::success('Tambah Berhasil', 'Data penanggung jawab keamanan berhasil dibuat!');
        return redirect()->route('user.worker-sum.index');
    }

    public function edit(ResponsiblePerson $person){
        if($person->user_id !== auth()->id()){
            abort(404);
        }
        $data['person'] = $person;
        return view('user.responsible-person.edit',$data);
    }

    public function update(Request $request, ResponsiblePerson $person){
        if($person->user_id !== auth()->id()){
            abort(404);
        }
        // Validation rules
        $validator = $this->validator($request->all(), ResponsiblePersonValidation::rulesForCreate(), ResponsiblePersonValidation::messages());
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $this->responsiblePersonService->updateResponsiblePerson($person, $request->all());
        
        Alert::success('Update Berhasil', 'Data penanggung jawab keamanan berhasil diubah!');
        return redirect()->route('user.worker-sum.index');
    }

    public function destroy(ResponsiblePerson $person){
        if($person->user_id !== auth()->id()){
            abort(404);
        }
        $this->responsiblePersonService->deleteResponsiblePerson($person);
        Alert::success('Delete Berhasil', 'Data penanggung jawab keamanan berhasil dihapus!');
        return redirect()->route('user.worker-sum.index');
    }

}
