<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Validation\VendorValidation;
use App\Services\User\UserService;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;

class VendorController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;

        $this->middleware('can:view.user.vendor')->only(['index']);
        $this->middleware('can:create.user.vendor')->only(['create', 'store']);
        // $this->middleware('can:edit.category.assesment')->only(['edit', 'update']);
        // $this->middleware('can:delete.category.assesment')->only(['destroy']);
    }

    protected function validator(array $data, $validation, array $messages = [])
    {
        return Validator::make($data, $validation, $messages);
    }

    public function index(){

        $result = $this->userService->getAllUser(10, true, 'bujp', true);
        $data['vendors'] = getPaginate($result);
        return view('user.vendor.index',$data);

    }

    public function create(){
        $result = $this->userService->getAllUser(0, false, 'bujp');
        $data['vendors'] = getData($result);
        return view('user.vendor.create', $data);
    }

    public function store(Request $request){

        try {
            // Validation rules
            $validator = $this->validator($request->all(), VendorValidation::rulesForCreate(), VendorValidation::messages());
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $request->merge([
                    'role' => 4,
                    'password' => "DefaultP@ssword"
                ]);

            if($request->vendor_exists == 1){
                $this->userService->updateBujpProfile($request->all(), $request->vendor_id);
            }else{
            $result = $this->userService->createUser($request->all(), true);
            }

            Alert::success('Tambah Berhasil', 'BUJP / Vendor berhasil dibuat!');
            return redirect()->route('user.vendor.index');
            
        } catch (\Throwable $th) {
            Alert::error('Tambah Gagal', 'BUJP / Vendor gagal dibuat!');
            return redirect()->route('user.vendor.index');
        }
    }
}
