<?php

namespace App\Http\Controllers\User\MonthlyAudit;

use App\Models\SecurityForm;
use Illuminate\Http\Request;
use App\Http\Helper\BlockMonthly;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class SecurityFormController extends Controller
{
    public function index($monthlyId){

        $data['monthlyId'] = $monthlyId;
        $data['forms'] = SecurityForm::with('security')->where('monthly_report_id', $monthlyId)->get();
        return view('user.monthly-audit.security-form',$data);
    }

    public function upload(Request $request, $monthlyId,$formId){
        
        try {
            DB::beginTransaction();

            $request->validate([
                'attachment_file_'.$formId => 'mimes:pdf'
            ],[
                'attachment_file_'.$formId.'.mimes' => 'File harus beresktensi .pdf!',
            ]);

            $securityFile = '';

            if($request->hasFile('attachment_file_'.$formId))
            {      
                $file= $request->file('attachment_file_'.$formId);
                $file_name = 'security-form-' . time() .'.'. $file->getClientOriginalExtension();
                $file->move(public_path('uploads/attachment_file_security_form/'),$file_name);   
                $securityFile = $file_name;
            }
            $form = SecurityForm::where('id',$formId)->first();
            $form->attachment_file = $securityFile;
            $form->save();

            DB::commit();
            Alert::success('Upload file berhasil', 'File berhasil diupload!');
            return redirect()->back();
        } catch (\Throwable $th) {
            DB::rollback();
            Alert::error('Upload file gagal', 'File gagal diupload!');
            return redirect()->back();
        }
    }
}
