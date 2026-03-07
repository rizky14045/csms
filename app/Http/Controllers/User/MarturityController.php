<?php

namespace App\Http\Controllers\User;

use App\Models\Marturity;
use Illuminate\Http\Request;
use App\Models\MarturityNote;
use App\Http\Controllers\Controller;
use App\Http\Validation\MarturityValidation;
use App\Services\Marturity\MarturityService;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;

class MarturityController extends Controller
{
    protected $marturityService;

    public function __construct(MarturityService $marturityService)
    {
        $this->marturityService = $marturityService;

        $this->middleware('can:view.marturity.unit')->only(['index']);
        $this->middleware('can:create.marturity.unit')->only(['create', 'store']);
        $this->middleware('can:edit.marturity.unit')->only(['edit', 'update']);
        $this->middleware('can:delete.marturity.unit')->only(['destroy']);
    }

    protected function validator(array $data, $validation, array $messages = [])
    {
        return Validator::make($data, $validation, $messages);
    }

    public function index(Request $request){
        $user = Auth::guard('web')->user();
        $result = $this->marturityService->getAlMarturity(10, true, ['unit'], $user->id);
        $data['marturities'] = getPaginate($result);
        $data['request'] = $request->all();
        return view('user.marturity.index',$data);
    }

    public function create(){
        return view('user.marturity.create');
    }

    public function store(Request $request){
        // Validation rules
        $validator = $this->validator($request->all(), MarturityValidation::rulesForCreate(), MarturityValidation::messages());
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $this->marturityService->createMarturity($request->all());
        
        Alert::success('Tambah Berhasil', 'Marturity berhasil ditambah!');
        return redirect()->route('user.marturity.index');
    }

    public function edit(Marturity $marturity){
        $user = Auth::guard('web')->user();
        if($marturity->unit_id !== $user->id){
            abort(404);
        }
        if($marturity->send_status == true){
            Alert::warning('Warning', 'Marturity sudah dikirm!');
            return redirect()->route('user.marturity.index');
        }
        $data['marturity'] = $marturity;
        return view('user.marturity.edit',$data);
    }
    public function update(Request $request ,Marturity $marturity){
        // Validation rules
        $validator = $this->validator($request->all(), MarturityValidation::rulesForUpdate(), MarturityValidation::messages());
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if($marturity->send_status == true){
            Alert::warning('Warning', 'Marturity sudah dikirm!');
            return redirect()->route('user.marturity.index');
        }

        $this->marturityService->updateMarturity($marturity, $request->all());
        
        Alert::success('Update Berhasil', 'Marturity berhasil diubah!');
        return redirect()->route('user.marturity.index');
    }

    public function show(Marturity $marturity){
        $user = Auth::guard('web')->user();
        if($marturity->send_status == true){
            Alert::warning('Warning', 'Marturity sudah dikirm!');
            return redirect()->route('user.marturity.index');
        }

        if($marturity->unit_id !== $user->id){
            abort(404);
        }

        $result = $this->marturityService->getAlMarturityArea(['subAreas','subAreas.levels','subAreas.levels.notes'], $marturity->id);
        $data['areas'] = getData($result);
        return view('user.marturity.show',$data);
    }

    public function preview(Marturity $marturity){
         $user = Auth::guard('web')->user();
        if($marturity->unit_id !== $user->id){
            abort(404);
        }
        if($marturity->send_status != true){
            Alert::warning('Warning', 'Marturity belum dikirm!');
            return redirect()->route('user.marturity.index');
        }
        $result = $this->marturityService->getAlMarturityArea(['subAreas','subAreas.levels','subAreas.levels.notes'], $marturity->id);
        $data['areas'] = getData($result);

        return view('user.marturity.preview',$data);
    }

    public function send(Marturity $marturity){
        if($marturity->send_status == true){
            Alert::warning('Warning', 'Marturity sudah dikirm!');
            return redirect()->route('user.marturity.index');
        }

        $this->marturityService->sendMarturity($marturity);
        
        Alert::success('Berhasil Dikirim', 'Marturity berhasil dikirim!');
        return redirect()->route('user.marturity.index');
    }

    public function destroy(Marturity $marturity){
        if($marturity->send_status == true){
            Alert::warning('Warning', 'Marturity sudah dikirm!');
            return redirect()->route('user.marturity.index');
        }
        $user = Auth::guard('web')->user();
        
        $marturity = Marturity::where('unit_id',$user->id)->where('id',$marturity->id)->first();

        if(!$marturity){
            abort(404);
        }

        $this->marturityService->deleteMarturity($marturity);

        Alert::success('Delete Berhasil', 'Marturity berhasil dihapus!');
        return redirect()->route('user.marturity.index');
    }
    public function uploadNote(Request $request, Marturity $marturity, $areaId, MarturityNote $note){
        // Validation rules
        $validator = $this->validator($request->all(), MarturityValidation::rulesForUploadNote($note->id), MarturityValidation::messages($note->id));
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $this->marturityService->uploadNote($request, $marturity, $areaId, $note);

        Alert::success('Update Berhasil', 'Marturity berhasil diupdate!');
        return redirect()->route('user.marturity.show',['marturity'=>$note->marturity_id,'area'=>$areaId]);
    }
}
