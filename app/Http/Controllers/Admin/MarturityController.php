<?php

namespace App\Http\Controllers\Admin;

use App\Models\Marturity;
use Illuminate\Http\Request;
use App\Models\MarturityArea;
use App\Http\Controllers\Controller;
use App\Services\Marturity\MarturityService;

class MarturityController extends Controller
{
    protected $marturityService;

    public function __construct(MarturityService $marturityService)
    {
        $this->marturityService = $marturityService;

        $this->middleware('can:view.marturity.admin')->only(['index', 'show']);
    }

    public function index(){
        $result = $this->marturityService->getAlMarturity(10, true, ['unit'], null, true);
        $data['marturities'] = getPaginate($result);
        return view('admin.marturity.index',$data);
    }

    public function show(Marturity $marturity){
        if(!$marturity->send_status){
            abort(404);
        }

        $result = $this->marturityService->getAlMarturityArea(['subAreas','subAreas.levels','subAreas.levels.notes'], $marturity->id);
        $data['areas'] = getData($result);

        return view('admin.marturity.show',$data);
    }
}
