<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Validation\EmailSettingValidation;
use App\Services\EmailSetting\EmailSettingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class EmailSettingController extends Controller
{
    protected $emailSettingService;

    public function __construct(EmailSettingService $emailSettingService)
    {
        $this->emailSettingService = $emailSettingService;

        $this->middleware('can:view.email.setting')->only('index');
        $this->middleware('can:edit.email.setting')->only(['update', 'test']);
    }

    protected function validator(array $data, $validation, array $messages = [])
    {
        return Validator::make($data, $validation, $messages);
    }

    public function index(){
        $result = $this->emailSettingService->getEmailSettings();
        $data['emailSetting'] = getData($result);
        return view('admin.email-setting.index',$data);
    }

    public function update(Request $request){
        // Validation rules
        $validator = $this->validator($request->all(), EmailSettingValidation::rulesForUpdate(), EmailSettingValidation::messages());
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $this->emailSettingService->updateEmailSetting($request->all());

        Alert::success('Update Berhasil', 'Pengaturan email berhasil diubah!');
        return redirect()->route('admin.email-setting.index');
    }

    public function test(Request $request){
        $validator = $this->validator($request->all(), EmailSettingValidation::rulesForTest(), EmailSettingValidation::messages());
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Data pengaturan email tidak valid',
                'errors' => $validator->errors(),
            ], 422);
        }

        return $this->emailSettingService->sendTestEmail($request->all());
    }
}
