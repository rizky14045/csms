<?php

namespace App\Http\Controllers\User;

use App\Models\Assesment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Validation\AssesmentValidation;
use Illuminate\Support\Facades\Auth;
use App\Models\SignCategoryAssesment;
use App\Models\SignQuestionAssesment;
use App\Services\Assesment\AssesmentService;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class AssesmentController extends Controller
{
    protected $assesmentService;

    public function __construct(AssesmentService $assesmentService)
    {
        $this->assesmentService = $assesmentService;

        $this->middleware('can:view.assesment.bujp.unit')->only(['index']);
        $this->middleware('can:create.assesment.bujp.unit')->only(['create', 'store']);
        $this->middleware('can:edit.assesment.bujp.unit')->only(['edit', 'update']);
        $this->middleware('can:delete.assesment.bujp.unit')->only(['destroy']);
        $this->middleware('can:send.assesment.bujp.unit')->only(['send']);
    }

    protected function validator(array $data, $validation, array $messages = [])
    {
        return Validator::make($data, $validation, $messages);
    }

    public function index(){
        $result = $this->assesmentService->getAllAssesment(25, true, request(), ['vendor', 'bujpProfile', 'getInvalidItemsQuestionByUnit'], ">=", 1, auth()->user()->id);
        $data['assesments'] = getPaginate($result);
        return view('user.assesment.index',$data);
    }

    public function create(){
        return view('user.assesment.create');
    }

    public function edit(){
        return view('user.assesment.edit');
    }
    
    public function show(Assesment $assesment){
        if($assesment->unit_id != auth()->user()->id){
            return abort(404);
        }

        if($assesment->send_status != 1){
            Alert::error('Gagal Dikirim', 'Assesment tidak bisa dibuka!');
            return redirect()->route('user.assesment.index');
        }

        $data['categories'] = SignCategoryAssesment::with([
                'questions',
                'questions.levels'
            ])
            ->where('assesment_id', $assesment->id)
            ->withCount([
                'questions as invalid_questions_count' => function ($q) {
                    $q->where(function ($sub) {
                        $sub->whereNull('evaluation_unit')
                            ->orWhere('evaluation_unit', 0);
                    });
                }
            ])
            ->get();
        $assesment->load('getInvalidItemsQuestionByUnit');
        $data['assesment'] = $assesment;
        return view('user.assesment.show',$data);
    }

    public function preview(Assesment $assesment){
        if($assesment->unit_id != auth()->user()->id){
            return abort(404);
        }

        if($assesment->send_status < 2){
            Alert::error('Gagal Dikirim', 'Assesment belum dikirim!');
            return redirect()->route('user.assesment.index');
        }

        $data['categories'] = SignCategoryAssesment::with('questions','questions.levels')->where('assesment_id',$assesment->id)->get();
        return view('user.assesment.preview',$data);
    }

    public function report(Assesment $assesment)
    {
        $response = $this->assesmentService->getReportAssesment($assesment);

         $status = getStatus($response);

        if ($status == false) {
            return abort(500);
        }

        $result = $response->getData(true);

        return view('user.assesment.report', [
            'assesment' => $result['data']['assesment'],
            'categories' => $result['data']['categories'],
            'chartJson' => json_encode($result['data']['chart']),
        ]);
    }

    public function send(Assesment $assesment){
        if($assesment->unit_id != auth()->user()->id){
            return abort(404);
        }

        $assesment->load('getInvalidItemsQuestionByUnit');

        if(count($assesment->getInvalidItemsQuestionByUnit) > 0){
            Alert::error('Gagal Dikirim', 'Assesment tidak bisa dikirim karena terdapat pertanyaan yang belum diisi!');
            return redirect()->route('user.assesment.index');
        }

        if($assesment->send_status != 1){
            Alert::error('Gagal Dikirim', 'Assesment tidak bisa dikirim!');
            return redirect()->route('user.assesment.index');
        }

        $this->assesmentService->sendAssesmentByUnit($assesment);

        Alert::success('Berhasil Dikirim', 'Assesment berhasil dikirim!');
        return redirect()->route('user.assesment.index');
    }

    public function revision(Assesment $assesment){
        if($assesment->unit_id != auth()->user()->id){
            return abort(404);
        }

        if($assesment->send_status != 1){
            Alert::error('Gagal Dikirim', 'Assesment tidak bisa dikirim!');
            return redirect()->route('user.assesment.index');
        }

        $this->assesmentService->revisionAssesmentByUnit($assesment);

        Alert::success('Berhasil Dikirim', 'Assesment berhasil dikirim!');
        return redirect()->route('user.assesment.index');
    }

    public function updateQuestion(Request $request, SignQuestionAssesment $question){
        $validator = $this->validator($request->all(), AssesmentValidation::rulesForUpdateQuestionUnit($question->id), AssesmentValidation::messages($question->id));
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $this->assesmentService->updateQuestionByUnit($request, $question);

        Alert::success('Update Berhasil', 'Assesment berhasil diupdate!');
        return redirect()->route('user.assesment.show',['assesment'=>$question->assesment_id,'signCategoryId'=>$question->sign_category_id]);
    }

    public function revisionQuestion(Request $request, SignQuestionAssesment $question){
        $validator = $this->validator($request->all(), AssesmentValidation::rulesForRevisionQuestionUnit($question->id), AssesmentValidation::messages($question->id));
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $this->assesmentService->revisionQuestionByUnit($request, $question);

        Alert::success('Update Berhasil', 'Assesment berhasil diupdate!');
        return redirect()->route('user.assesment.show',['assesment'=>$question->assesment_id,'signCategoryId'=>$question->sign_category_id]);
    }
}
