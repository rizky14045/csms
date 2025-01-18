<?php

namespace App\Http\Controllers\User\MonthlyAudit;

use Illuminate\Http\Request;
use App\Models\ForeignWorker;
use App\Http\Helper\BlockMonthly;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class FormForeignWorkerController extends Controller
{
    public function index($monthlyId){

        $data['monthlyId'] = $monthlyId;
        $data['foreigns'] = ForeignWorker::where('monthly_report_id', $monthlyId)->get();
        return view('user.monthly-audit.form-foreign-worker',$data);
    }

    public function create($monthlyId){

        $data['monthlyId'] = $monthlyId;
        return view('user.monthly-audit.foreign-worker.create',$data);
    }

    public function store(Request $request,$monthlyId){

        try {
            DB::beginTransaction();

            $userId = Auth::guard('web')->user()->id;

            $request->validate([
                'name' => 'required',
                'nationality' => 'required',
                'company' => 'required',
                'position' => 'required',
                'category' => 'required',
                'arrived_date' => 'required',
                'return_date' => 'required',
                'attachment_file' => 'mimes:pdf'
            ],[
                'name.required' => 'Nama harus diisi!',
                'nationality.required' => 'Kebangsaan harus diisi!',
                'company.required' => 'Perusahaan harus diisi!',
                'position.required' => 'Jabatan harus diisi!',
                'category.required' => 'Kategori Tamu harus diisi!',
                'arrived_date.required' => 'Tanggal Datang harus diisi!',
                'return_date.required' => 'Tanggal Kembali harus diisi!',
                'attachment_file.mimes' => 'File harus beresktensi .pdf!',
            ]);

            $attachmentFile = '';

            if($request->hasFile('attachment_file'))
            {     
                $file= $request->file('attachment_file');
                $file_name = 'foreign-worker-' . time() .'.'. $file->getClientOriginalExtension();
                $file->move(public_path('uploads/attachment_file_foreign_worker/'),$file_name);   
                $attachmentFile = $file_name;
            }
            ForeignWorker::create([
                'monthly_report_id' => $monthlyId,
                'user_id' => $userId,
                'name'=> $request->name,
                'nationality'=> $request->nationality,
                'company'=> $request->company,
                'position'=> $request->position,
                'category'=> $request->category,
                'paspor'=> $request->paspor ?? false,
                'visa'=> $request->visa ?? false,
                'vitas'=> $request->vitas ?? false,
                'kitas'=> $request->kitas ?? false,
                'rptka'=> $request->rptka ?? false,
                'arrived_date'=> $request->arrived_date,
                'return_date'=> $request->return_date,
                'note'=> $request->note,
                'attachment_file'=> $attachmentFile,
            ]);
            
            DB::commit();
            Alert::success('Tambah Berhasil', 'Data TKA berhasil dibuat!');
            return redirect()->route('user.monthly-audit.form-foreign-worker.index',['monthlyId'=>$monthlyId]);
            
        } catch (\Throwable $th) {

            DB::rollback();
            Alert::success('Tambah Gagal', 'Data TKA gagal dibuat!');
            return redirect()->route('user.monthly-audit.form-foreign-worker.index',['monthlyId'=>$monthlyId]);
        }
    }

    public function edit($monthlyId,$foreignId){

        $foreign = ForeignWorker::where('id',$foreignId)->where('monthly_report_id', $monthlyId)->first();
        if (!$foreign) {
            Alert::warning('Warning', 'Data tidak ditemukan!');
            return redirect()->route('user.monthly-audit.form-foreign-worker.index',['monthlyId'=>$monthlyId]);
        }
        $data['monthlyId'] = $monthlyId;
        $data['foreign'] = $foreign;
        return view('user.monthly-audit.foreign-worker.edit',$data);
    }

    public function update(Request $request,$monthlyId,$foreignId){

        try {
            DB::beginTransaction();

            $userId = Auth::guard('web')->user()->id;

            $request->validate([
                'name' => 'required',
                'nationality' => 'required',
                'company' => 'required',
                'position' => 'required',
                'category' => 'required',
                'arrived_date' => 'required',
                'return_date' => 'required',
                'attachment_file' => 'mimes:pdf'
            ],[
                'name.required' => 'Nama harus diisi!',
                'nationality.required' => 'Kebangsaan harus diisi!',
                'company.required' => 'Perusahaan harus diisi!',
                'position.required' => 'Jabatan harus diisi!',
                'category.required' => 'Kategori Tamu harus diisi!',
                'arrived_date.required' => 'Tanggal Datang harus diisi!',
                'return_date.required' => 'Tanggal Kembali harus diisi!',
                'attachment_file.mimes' => 'File harus beresktensi .pdf!',
            ]);
            
            $foreign = ForeignWorker::where('id',$foreignId)->where('monthly_report_id', $monthlyId)->first();
            if (!$foreign) {
                Alert::warning('Warning', 'Data tidak ditemukan!');
                return redirect()->route('user.monthly-audit.form-foreign-worker.index',['monthlyId'=>$monthlyId]);
            }

            $attachmentFile = null;

            if($request->hasFile('attachment_file'))
            {    
                if ($foreign->attachment_file) {
                    unlink(public_path('uploads/attachment_file_foreign_worker/'.$foreign->attachment_file));
                }
                $file= $request->file('attachment_file');
                $file_name = 'foreign-worker-' . time() .'.'. $file->getClientOriginalExtension();
                $file->move(public_path('uploads/attachment_file_foreign_worker/'),$file_name);   
                $attachmentFile = $file_name;
            }

            $foreign->monthly_report_id =  $monthlyId;
            $foreign->user_id = $userId;
            $foreign->name  = $request->name;
            $foreign->nationality = $request->nationality;
            $foreign->company = $request->company;
            $foreign->position = $request->position;
            $foreign->category = $request->category;
            $foreign->paspor = $request->paspor ?? false;
            $foreign->visa = $request->visa ?? false;
            $foreign->vitas = $request->vitas ?? false;
            $foreign->kitas = $request->kitas ?? false;
            $foreign->rptka = $request->rptka ?? false;
            $foreign->arrived_date = $request->arrived_date;
            $foreign->return_date = $request->return_date;
            $foreign->note = $request->note;
            $foreign->attachment_file = $attachmentFile ?? $foreign->attachment_file;
            $foreign->save();
            
            DB::commit();
            Alert::success('Update Berhasil', 'Data TKA berhasil diubah!');
            return redirect()->route('user.monthly-audit.form-foreign-worker.index',['monthlyId'=>$monthlyId]);
            
        } catch (\Throwable $th) {

            DB::rollback();
            Alert::success('Update Gagal', 'Data TKA gagal diubah!');
            return redirect()->route('user.monthly-audit.form-foreign-worker.index',['monthlyId'=>$monthlyId]);
        }
    }
    public function destroy($monthlyId,$foreignId){

        try {
            DB::beginTransaction();
            
            $foreign = ForeignWorker::where('id',$foreignId)->where('monthly_report_id', $monthlyId)->first();
            if (!$foreign) {
                Alert::warning('Warning', 'Data tidak ditemukan!');
                return redirect()->route('user.monthly-audit.form-foreign-worker.index',['monthlyId'=>$monthlyId]);
            }
   
            if ($foreign->attachment_file) {
                unlink(public_path('uploads/attachment_file_foreign_worker/'.$foreign->attachment_file));
            }

            $foreign->delete();
            
            DB::commit();
            Alert::success('Delete Berhasil', 'Data TKA berhasil dihapus!');
            return redirect()->route('user.monthly-audit.form-foreign-worker.index',['monthlyId'=>$monthlyId]);
            
        } catch (\Throwable $th) {

            DB::rollback();
            Alert::success('Delete Gagal', 'Data TKA gagal dihapus!');
            return redirect()->route('user.monthly-audit.form-foreign-worker.index',['monthlyId'=>$monthlyId]);
        }
    }

}
