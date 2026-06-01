<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Models\ResponsiblePerson;
use App\Models\MonthlyResponsiblePerson;
use App\Http\Controllers\Controller;
use App\Http\Validation\ResponsiblePersonValidation;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\DB;
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
        $validator = $this->validator($request->all(), ResponsiblePersonValidation::rulesForCreate(), ResponsiblePersonValidation::messages());
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $monthlyId = $request->monthly_id;

        if ($monthlyId) {
            DB::beginTransaction();
            try {
                $person = ResponsiblePerson::create([
                    'user_id'                => $request->boolean('save_to_master') ? auth()->id() : null,
                    'name'                   => $request->name ?? '',
                    'position'               => $request->position ?? '',
                    'work_unit'              => $request->work_unit ?? '',
                    'training_smp'           => $request->training_smp ?? '',
                    'auditor_smp'            => $request->auditor_smp ?? '',
                    'main'                   => $request->main ?? '',
                    'investigation'          => $request->investigation ?? '',
                    'mansrisk'               => $request->mansrisk ?? '',
                    'stackholder_management' => $request->stackholder_management ?? '',
                    'last_education'         => $request->last_education ?? '',
                    'note'                   => $request->note ?? '',
                    'created_by'             => auth()->id(),
                ]);

                MonthlyResponsiblePerson::create([
                    'monthly_report_id'       => $monthlyId,
                    'user_id'                 => auth()->id(),
                    'responsible_person_id'   => $person->id,
                ]);

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Gagal menyimpan data.')->withInput();
            }

            Alert::success('Tambah Berhasil', 'Data penanggung jawab keamanan berhasil ditambahkan ke laporan bulanan!');
            return redirect()->route('user.monthly-audit.worker-sum.index', ['monthlyId' => $monthlyId]);
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
