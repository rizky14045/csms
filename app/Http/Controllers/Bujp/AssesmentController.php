<?php

namespace App\Http\Controllers\Bujp;

use App\Models\Assesment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\SignCategoryAssesment;
use App\Models\SignQuestionAssesment;
use App\Services\Assesment\AssesmentService;
use App\Services\User\UserService;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;
use App\Http\Validation\AssesmentValidation;
use Illuminate\Support\Facades\Crypt;

class AssesmentController extends Controller
{
    protected $assesmentService, $userService;

    public function __construct(AssesmentService $assesmentService, UserService $userService)
    {
        $this->assesmentService = $assesmentService;
        $this->userService = $userService;

        $this->middleware('can:view.assesment.bujp')->only(['index']);
        $this->middleware('can:create.assesment.bujp')->only(['create', 'store']);
        $this->middleware('can:edit.assesment.bujp')->only(['edit', 'update']);
        $this->middleware('can:delete.assesment.bujp')->only(['destroy']);
        $this->middleware('can:send.assesment.bujp')->only(['send']);
    }

    protected function validator(array $data, $validation, array $messages = [])
    {
        return Validator::make($data, $validation, $messages);
    }

    public function index(Request $request){

        $validateVendor = $this->userService->validateVendorAccess($request->query('unit'));
        if (!$validateVendor) {
            abort(404);
        }

        $unitId = Crypt::decryptString($request->query('unit'));

        $results = $this->userService->getAllUnitByVendorID(0, false, auth()->user()->id);
        $data['units'] = getData($results);
        $result = $this->assesmentService->getAllAssesment(10, true, $request, ['vendor', 'bujpProfile', 'getInvalidItemsQuestionByBujp']);
        $data['assesments'] = getPaginate($result);
        return view('bujp.assesment.index',$data);

    }

    public function create(Request $request){
        $validateVendor = $this->userService->validateVendorAccess($request->query('unit'));
        if (!$validateVendor) {
            abort(404);
        }
        $results = $this->userService->getAllUnitByVendorID(0, false, auth()->user()->id);
        $data['units'] = getData($results);

        return view('bujp.assesment.create', $data);
    }

    public function store(Request $request){
        $validateVendor = $this->userService->validateVendorAccess($request->query('unit'));
        if (!$validateVendor) {
            abort(404);
        }
        
        // Validation rules
        $validator = $this->validator($request->all(), AssesmentValidation::rulesForCreate(), AssesmentValidation::messages());
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $year = (int)$request->year;
        $triwulan = (int)$request->triwulan;
        $userId = auth()->id();

        // ===============================
        // ✅ 1. CEK DUPLICATE
        // ===============================
        $exists = Assesment::where('year', $year)
            ->where('triwulan', $triwulan)
            ->where('created_by', $userId)
            ->exists();

        if ($exists) {
            Alert::error('Gagal', 'Triwulan tersebut sudah diisi untuk tahun ini!');
            return back()->withErrors([
                'triwulan' => 'Triwulan sudah ada'
            ])->withInput();
        }

        // ===============================
        // ✅ 2. CEK BELUM WAKTUNYA
        // ===============================
        $currentMonth = now()->month;

        if ($currentMonth <= 3) {
            $currentTriwulan = 1;
        } elseif ($currentMonth <= 6) {
            $currentTriwulan = 2;
        } elseif ($currentMonth <= 9) {
            $currentTriwulan = 3;
        } else {
            $currentTriwulan = 4;
        }

        if ($year == now()->year && $triwulan > $currentTriwulan) {
            Alert::error('Gagal', 'Belum waktunya mengisi triwulan tersebut!');
            return back()->withErrors([
                'triwulan' => 'Belum waktunya'
            ])->withInput();
        }

        // ===============================
        // ✅ 3. CEK LONCAT TRIWULAN
        // ===============================

        // Ambil triwulan terakhir di tahun tersebut
        $lastTriwulan = Assesment::where('year', $year)
            ->where('created_by', $userId)
            ->orderBy('triwulan', 'desc')
            ->value('triwulan');

        if ($lastTriwulan) {
            $expectedTriwulan = $lastTriwulan + 1;

            if ($triwulan != $expectedTriwulan) {
                Alert::error('Gagal', 'Harus mengisi triwulan sebelumnya terlebih dahulu!');
                return back()->withErrors([
                    'triwulan' => "Harus mengisi triwulan {$expectedTriwulan} terlebih dahulu"
                ])->withInput();
            }
        } else {
            if ($triwulan != 1) {
                Alert::error('Gagal', 'Harus mulai dari triwulan 1!');
                return back()->withErrors([
                    'triwulan' => 'Harus mulai dari triwulan 1'
                ])->withInput();
            }
        }

        $this->assesmentService->createAssesment($request->all(), $request->query('unit'));

        Alert::success('Tambah Berhasil', 'Assesment berhasil ditambah!');
        return redirect()->route('bujp.assesment.index', ['unit' => $request->query('unit')]);
    }


    public function edit(Request $request, Assesment $assesment){
        return redirect()->back();
        $validateVendor = $this->userService->validateVendorAccess($request->query('unit'));
        if (!$validateVendor) {
            abort(404);
        }

        if($assesment->send_status != 0){
            Alert::warning('Warning', 'Assesment sudah dikirm!');
            return redirect()->route('bujp.assesment.index');
        }

        if($assesment->created_by != Auth::user()->id){
            abort(404);
        }

        $results = $this->userService->getAllUnitByVendorID(0, false, auth()->user()->id);
        $data['units'] = getData($results);

        $data['assesment'] = $assesment;
        return view('bujp.assesment.edit',$data);
    }
    
    public function update(Request $request, Assesment $assesment){
        return redirect()->back();
         $validateVendor = $this->userService->validateVendorAccess($request->query('unit'));
        if (!$validateVendor) {
            abort(404);
        }

        if($assesment->send_status != 0){
            Alert::warning('Warning', 'Assesment sudah dikirm!');
            return redirect()->route('bujp.assesment.index');
        }

        if($assesment->created_by != Auth::user()->id){
            abort(404);
        }

        $this->assesmentService->updateAssesment($assesment, $request->all());

        // Validation rules
        $validator = $this->validator($request->all(), AssesmentValidation::rulesForUpdate(), AssesmentValidation::messages());
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }


    
        Alert::success('Update Berhasil', 'Assesment berhasil diubah!');
        return redirect()->route('bujp.assesment.index', ['unit' => $request->query('unit')]);
    }

    public function destroy(Request $request, Assesment $assesment){
        $validateVendor = $this->userService->validateVendorAccess($request->query('unit'));
        if (!$validateVendor) {
            abort(404);
        }

        if($assesment->send_status != 0){
            Alert::warning('Warning', 'Assesment sudah dikirm!');
            return redirect()->route('bujp.assesment.index');
        }

        if($assesment->created_by != Auth::user()->id){
            abort(404);
        }

        $this->assesmentService->deleteAssesment($assesment);

        Alert::success('Delete Berhasil', 'Assesment berhasil dihapus!');
        return redirect()->route('bujp.assesment.index', ['unit' => $request->query('unit')]);
    }

    public function send(Request $request, Assesment $assesment){
        $validateVendor = $this->userService->validateVendorAccess($request->query('unit'));
        if (!$validateVendor) {
            abort(404);
        }

        if($assesment->send_status != 0){
            Alert::warning('Warning', 'Assesment sudah dikirm!');
            return redirect()->route('bujp.assesment.index');
        }

        if($assesment->created_by != Auth::user()->id){
            abort(404);
        }

        $invalidData = SignQuestionAssesment::where('assesment_id', $assesment->id)
                        ->where(function ($q) {
                            $q->whereNull('level')
                            ->orWhere('level', 0)
                            ->orWhereNull('attachment_file')
                            ->orWhereNull('note')
                            ->orWhere('note', '');
                        })
                        ->exists();

                    if ($invalidData) {
                        Alert::error(
                            'Gagal Kirim',
                            'Masih ada data yang belum lengkap! Pastikan level, attachment, dan catatan sudah terisi.'
                        );

                        return redirect()->back()->withInput();
                    }

        $this->assesmentService->sendAssesment($assesment);
        Alert::success('Berhasil Dikirim', 'Assesment berhasil dikirim!');
        return redirect()->route('bujp.assesment.index', ['unit' => $request->query('unit')]);
    }

    public function show(Request $request, Assesment $assesment){
        $validateVendor = $this->userService->validateVendorAccess($request->query('unit'));
        if (!$validateVendor) {
            abort(404);
        }

        if($assesment->send_status != 0){
            Alert::warning('Warning', 'Assesment sudah dikirm!');
            return redirect()->route('bujp.assesment.index');
        }

        if($assesment->created_by != Auth::user()->id){
            abort(404);
        }

        $data['categories'] = SignCategoryAssesment::with('questions','questions.levels')->where('assesment_id',$assesment->id)->get();
        $assesment->load('getInvalidItemsQuestionByBujp');
        $data['assesment'] = $assesment;
        return view('bujp.assesment.show',$data);
    }

    public function preview(Request $request, Assesment $assesment){
        $validateVendor = $this->userService->validateVendorAccess($request->query('unit'));
        if (!$validateVendor) {
            abort(404);
        }

        if($assesment->send_status == 0){
            Alert::warning('Warning', 'Assesment belum dikirm!');
            return redirect()->route('bujp.assesment.index');
        }

        if($assesment->created_by != Auth::user()->id){
            abort(404);
        }

        $data['categories'] = SignCategoryAssesment::with('questions','questions.levels')->where('assesment_id',$assesment->id)->get();
        return view('bujp.assesment.preview',$data);
    }
    
    public function report(Assesment $assesment)
    {
        $results = $this->userService->getAllUnitByVendorID(0, false, auth()->user()->id);
        $response = $this->assesmentService->getReportAssesment($assesment, 'level');

         $status = getStatus($response);

        if ($status == false) {
            return abort(500);
        }

        $result = $response->getData(true);

        return view('bujp.assesment.report', [
            'assesment' => $result['data']['assesment'],
            'categories' => $result['data']['categories'],
            'chartJson' => json_encode($result['data']['chart']),
            'units' => getData($results)
        ]);
    }

    public function updateQuestion(Request $request, $signQuestionID){
        $signQuestion = SignQuestionAssesment::find($signQuestionID);
         $validateVendor = $this->userService->validateVendorAccess($request->query('unit'));
        // Validation rules
        $validator = $this->validator($request->all(), AssesmentValidation::rulesForUpdateQuestion($signQuestion->id), AssesmentValidation::messages($signQuestion->id));
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $this->assesmentService->updateQuestion($request, $signQuestion);
            
        Alert::success('Update Berhasil', 'Assesment berhasil diupdate!');
        return redirect()->route('bujp.assesment.show',['assesment'=>$signQuestion->assesment_id,'signCategoryId'=>$signQuestion->sign_category_id,'unit' => $request->query('unit')]);
    }
}
