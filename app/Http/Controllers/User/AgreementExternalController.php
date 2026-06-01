<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Models\AgreementExternal;
use App\Models\MonthlyAgreementExternal;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Validation\AgreementExternalValidation;
use App\Services\AgreementExternal\AgreementExternalService;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;

class AgreementExternalController extends Controller
{
    protected $agreementExternalService;

    public function __construct(AgreementExternalService $agreementExternalService)
    {
        $this->agreementExternalService = $agreementExternalService;

        $this->middleware('can:create.agreement.external.unit')->only(['create', 'store']);
        $this->middleware('can:edit.agreement.external.unit')->only(['edit', 'update']);
        $this->middleware('can:delete.agreement.external.unit')->only(['destroy']);
    }

    protected function validator(array $data, $validation, array $messages = [])
    {
        return Validator::make($data, $validation, $messages);
    }

    public function create(){

        return view('user.agreement-external.create');

    }
    public function store(Request $request){
        $validator = $this->validator($request->all(), AgreementExternalValidation::rulesForCreate(), AgreementExternalValidation::messages());
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $monthlyId = $request->monthly_id;

        if ($monthlyId) {
            DB::beginTransaction();
            try {
                $agreement = AgreementExternal::create([
                    'user_id'       => $request->boolean('save_to_master') ? auth()->id() : null,
                    'instansi'      => $request->instansi ?? '',
                    'name'          => $request->name ?? '',
                    'regional_unit' => $request->regional_unit ?? '',
                    'pkt_number'    => $request->pkt_number ?? '',
                    'pkt_title'     => $request->pkt_title ?? '',
                    'expired_date'  => $request->expired_date ?? '',
                    'note'          => $request->note ?? '',
                    'created_by'    => auth()->id(),
                ]);

                MonthlyAgreementExternal::create([
                    'monthly_report_id'      => $monthlyId,
                    'user_id'                => auth()->id(),
                    'agreement_external_id'  => $agreement->id,
                ]);

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Gagal menyimpan data.')->withInput();
            }

            Alert::success('Tambah Berhasil', 'Data kerjasama berhasil ditambahkan ke laporan bulanan!');
            return redirect()->route('user.monthly-audit.worker-sum.index', ['monthlyId' => $monthlyId]);
        }

        $this->agreementExternalService->createAgreementExternal($request->all());

        Alert::success('Tambah Berhasil', 'Data kerja sama external berhasil dibuat!');
        return redirect()->route('user.worker-sum.index');
    }
    public function edit(AgreementExternal $agreement){
        if($agreement->user_id !== auth()->id()){
            abort(404);
        }
        $data['agreement'] = $agreement;
        return view('user.agreement-external.edit',$data);
    }

    public function update(Request $request, AgreementExternal $agreement){
        if($agreement->user_id !== auth()->id()){
            abort(404);
        }
        // Validation rules
        $validator = $this->validator($request->all(), AgreementExternalValidation::rulesForUpdate(), AgreementExternalValidation::messages());
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $this->agreementExternalService->updateAgreementExternal($agreement, $request->all());
        
        Alert::success('Update Berhasil', 'Data kerja sama external berhasil diubah!');
        return redirect()->route('user.worker-sum.index');
    }
    public function destroy(AgreementExternal $agreement){
        if($agreement->user_id !== auth()->id()){
            abort(404);
        }
        $this->agreementExternalService->deleteAgreementExternal($agreement);
        Alert::success('Delete Berhasil', 'Data kerja sama external berhasil dihapus!');
        return redirect()->route('user.worker-sum.index');
    }

}
