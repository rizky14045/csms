<?php

namespace App\Http\Controllers\Admin;

use App\Models\Assesment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SignCategoryAssesment;
use App\Services\Assesment\AssesmentService;

class AssesmentController extends Controller
{
    protected $assesmentService;

    public function __construct(AssesmentService $assesmentService)
    {
        $this->assesmentService = $assesmentService;

        $this->middleware('can:view.assesment.bujp.admin')->only(['index']);
        $this->middleware('can:create.assesment.bujp.admin')->only(['create', 'store']);
        $this->middleware('can:edit.assesment.bujp.admin')->only(['edit', 'update']);
        $this->middleware('can:delete.assesment.bujp.admin')->only(['destroy']);
    }

    public function index(){
        $result = $this->assesmentService->getAllAssesment(25, true, request(), ['vendor', 'unit', 'bujpProfile'], "=", 2, null);
        $data['assesments'] = getPaginate($result);
        return view('admin.assesment.index',$data);
    }

    public function create(){
        return view('admin.assesment.create');
    }

    public function edit(){
        return view('admin.assesment.edit');
    }
    public function show(Assesment $assesment){
        if($assesment->send_status < 2){
            abort(404);
        }
        $data['categories'] = SignCategoryAssesment::with('questions','questions.levels')->where('assesment_id',$assesment->id)->get();
        return view('admin.assesment.show',$data);
    }
    
    public function report(Assesment $assesment)
    {
        $response = $this->assesmentService->getReportAssesment($assesment);

         $status = getStatus($response);

        if ($status == false) {
            return abort(500);
        }

        $result = $response->getData(true);

        return view('admin.assesment.report', [
            'assesment' => $result['data']['assesment'],
            'categories' => $result['data']['categories'],
            'chartJson' => json_encode($result['data']['chart']),
        ]);
    }
}
