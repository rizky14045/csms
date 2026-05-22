<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Models\SecurityExternal;
use App\Models\MonthlySecurityExternal;
use App\Http\Controllers\Controller;
use App\Http\Validation\SecurityExternalValidation;
use App\Services\SecurityExternal\SecurityExternalService;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SecurityExternalController extends Controller
{
    protected $securityExternalService;

    public function __construct(SecurityExternalService $securityExternalService)
    {
        $this->securityExternalService = $securityExternalService;

        $this->middleware('can:create.security.external.unit')->only(['create', 'store']);
        $this->middleware('can:edit.security.external.unit')->only(['edit', 'update']);
        $this->middleware('can:delete.security.external.unit')->only(['destroy']);
    }

    protected function validator(array $data, $validation, array $messages = [])
    {
        return Validator::make($data, $validation, $messages);
    }

    public function create(){

        return view('user.security-external.create');

    }
    public function store(Request $request){
        $validator = $this->validator($request->all(), SecurityExternalValidation::rulesForCreate(), SecurityExternalValidation::messages());
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $monthlyId = $request->monthly_id;

        if ($monthlyId) {
            DB::beginTransaction();
            try {
                $security = SecurityExternal::create([
                    'user_id'       => $request->boolean('save_to_master') ? auth()->id() : null,
                    'name'          => $request->name ?? '',
                    'gender'        => $request->gender ?? '',
                    'instansi'      => $request->instansi ?? '',
                    'regional_unit' => $request->regional_unit ?? '',
                    'warrant_number'=> $request->warrant_number ?? '',
                    'note'          => $request->note ?? '',
                    'created_by'    => auth()->id(),
                ]);

                MonthlySecurityExternal::create([
                    'monthly_report_id'    => $monthlyId,
                    'user_id'              => auth()->id(),
                    'security_external_id' => $security->id,
                ]);

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Gagal menyimpan data.')->withInput();
            }

            Alert::success('Tambah Berhasil', 'Data personil keamanan berhasil ditambahkan ke laporan bulanan!');
            return redirect()->route('user.monthly-audit.worker-sum.index', ['monthlyId' => $monthlyId]);
        }

        $this->securityExternalService->createSecurityExternal($request->all());

        Alert::success('Tambah Berhasil', 'Data Keamanan external berhasil dibuat!');
        return redirect()->route('user.worker-sum.index');
    }
    public function edit(SecurityExternal $security){
        if($security->user_id !== auth()->id()){
            Alert::error('Akses Ditolak', 'Anda tidak memiliki akses untuk mengedit data ini!');
            return redirect()->route('user.worker-sum.index');
        }
        $data['security'] = $security;
        return view('user.security-external.edit',$data);
    }

    public function update(Request $request, SecurityExternal $security){
        // Validation rules
        $validator = $this->validator($request->all(), SecurityExternalValidation::rulesForUpdate(), SecurityExternalValidation::messages());
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $this->securityExternalService->updateSecurityExternal($security, $request->all());

        Alert::success('Update Berhasil', 'Data Keamanan external berhasil diubah!');
        return redirect()->route('user.worker-sum.index');

    }
    public function destroy(SecurityExternal $security){
        $this->securityExternalService->deleteSecurityExternal($security);

        Alert::success('Delete Berhasil', 'Data Keamanan external berhasil dihapus!');
        return redirect()->route('user.worker-sum.index');

    }
}
