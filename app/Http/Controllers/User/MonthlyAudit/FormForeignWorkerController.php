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
                'paspor_file' => 'mimes:pdf,jpg,png,jpeg',
                'visa_file' => 'mimes:pdf,jpg,png,jpeg',
                'vitas_file' => 'mimes:pdf,jpg,png,jpeg',
                'kitas_file' => 'mimes:pdf,jpg,png,jpeg',
                'rptka_file' => 'mimes:pdf,jpg,png,jpeg',

            ],[
                'name.required' => 'Nama harus diisi!',
                'nationality.required' => 'Kebangsaan harus diisi!',
                'company.required' => 'Perusahaan harus diisi!',
                'position.required' => 'Jabatan harus diisi!',
                'category.required' => 'Kategori Tamu harus diisi!',
                'arrived_date.required' => 'Tanggal Datang harus diisi!',
                'return_date.required' => 'Tanggal Kembali harus diisi!',
                'paspor_file.mimes' => 'Esktensi file salah!',
                'visa_file.mimes' => 'Esktensi file salah!',
                'vitas_file.mimes' => 'Esktensi file salah!',
                'kitas_file.mimes' => 'Esktensi file salah!',
                'rptka_file.mimes' => 'Esktensi file salah!',
            ]);

            $pasporFile = null;
            $visaFile = null;
            $vitasFile = null;
            $kitasFile = null;
            $rptkaFile = null;

            if($request->hasFile('paspor_file'))
            {     
                $file= $request->file('paspor_file');
                $paspor_name = 'paspor-' . time() .'.'. $file->getClientOriginalExtension();
                $file->move(public_path('uploads/attachment_file_foreign_worker/'),$paspor_name);   
                $pasporFile = $paspor_name;
            }
            if($request->hasFile('visa_file'))
            {     
                $file= $request->file('visa_file');
                $visa_name = 'visa-' . time() .'.'. $file->getClientOriginalExtension();
                $file->move(public_path('uploads/attachment_file_foreign_worker/'),$visa_name);   
                $visaFile = $visa_name;
            }
            if($request->hasFile('vitas_file'))
            {     
                $file= $request->file('vitas_file');
                $vitas_name = 'vitas-' . time() .'.'. $file->getClientOriginalExtension();
                $file->move(public_path('uploads/attachment_file_foreign_worker/'),$vitas_name);   
                $vitasFile = $vitas_name;
            }
            if($request->hasFile('kitas_file'))
            {     
                $file= $request->file('kitas_file');
                $kitas_name = 'kitas-' . time() .'.'. $file->getClientOriginalExtension();
                $file->move(public_path('uploads/attachment_file_foreign_worker/'),$kitas_name);   
                $kitasFile = $kitas_name;
            }
            if($request->hasFile('rptka_file'))
            {     
                $file= $request->file('rptka_file');
                $rptka_name = 'rptka-' . time() .'.'. $file->getClientOriginalExtension();
                $file->move(public_path('uploads/attachment_file_foreign_worker/'),$rptka_name);   
                $rptkaFile = $rptka_name;
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
                'paspor_file'=> $pasporFile,
                'visa_file'=> $visaFile,
                'vitas_file'=> $vitasFile,
                'kitas_file'=> $kitasFile,
                'rptka_file'=> $rptkaFile,
            ]);
            
            DB::commit();
            Alert::success('Tambah Berhasil', 'Data TKA berhasil dibuat!');
            return redirect()->route('user.monthly-audit.form-foreign-worker.index',['monthlyId'=>$monthlyId]);
            
        } catch (\Throwable $th) {

            DB::rollback();
            Alert::error('Tambah Gagal', 'Data TKA gagal dibuat!');
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
                'paspor_file' => 'mimes:pdf,jpg,png,jpeg',
                'visa_file' => 'mimes:pdf,jpg,png,jpeg',
                'vitas_file' => 'mimes:pdf,jpg,png,jpeg',
                'kitas_file' => 'mimes:pdf,jpg,png,jpeg',
                'rptka_file' => 'mimes:pdf,jpg,png,jpeg',

            ],[
                'name.required' => 'Nama harus diisi!',
                'nationality.required' => 'Kebangsaan harus diisi!',
                'company.required' => 'Perusahaan harus diisi!',
                'position.required' => 'Jabatan harus diisi!',
                'category.required' => 'Kategori Tamu harus diisi!',
                'arrived_date.required' => 'Tanggal Datang harus diisi!',
                'return_date.required' => 'Tanggal Kembali harus diisi!',
                'paspor_file.mimes' => 'Esktensi file salah!',
                'visa_file.mimes' => 'Esktensi file salah!',
                'vitas_file.mimes' => 'Esktensi file salah!',
                'kitas_file.mimes' => 'Esktensi file salah!',
                'rptka_file.mimes' => 'Esktensi file salah!',
            ]);
            
            $foreign = ForeignWorker::where('id',$foreignId)->where('monthly_report_id', $monthlyId)->first();
            if (!$foreign) {
                Alert::warning('Warning', 'Data tidak ditemukan!');
                return redirect()->route('user.monthly-audit.form-foreign-worker.index',['monthlyId'=>$monthlyId]);
            }

            
            $pasporFile = null;
            $visaFile = null;
            $vitasFile = null;
            $kitasFile = null;
            $rptkaFile = null;

            if($request->hasFile('paspor_file'))
            {    
                if ($foreign->paspor_file) {
                    unlink(public_path('uploads/attachment_file_foreign_worker/'.$foreign->paspor_file));
                }
                $file= $request->file('paspor_file');
                $paspor_name = 'paspor-' . time() .'.'. $file->getClientOriginalExtension();
                $file->move(public_path('uploads/attachment_file_foreign_worker/'),$paspor_name);   
                $pasporFile = $paspor_name;
            }
            if($request->hasFile('visa_file'))
            {    
                if ($foreign->visa_file) {
                    unlink(public_path('uploads/attachment_file_foreign_worker/'.$foreign->visa_file));
                }
                $file= $request->file('visa_file');
                $visa_name = 'visa-' . time() .'.'. $file->getClientOriginalExtension();
                $file->move(public_path('uploads/attachment_file_foreign_worker/'),$visa_name);   
                $visaFile = $visa_name;
            }
            if($request->hasFile('vitas_file'))
            {    
                if ($foreign->vitas_file) {
                    unlink(public_path('uploads/attachment_file_foreign_worker/'.$foreign->vitas_file));
                }
                $file= $request->file('vitas_file');
                $vitas_name = 'vitas-' . time() .'.'. $file->getClientOriginalExtension();
                $file->move(public_path('uploads/attachment_file_foreign_worker/'),$vitas_name);   
                $vitasFile = $vitas_name;
            }
            if($request->hasFile('kitas_file'))
            {    
                if ($foreign->kitas_file) {
                    unlink(public_path('uploads/attachment_file_foreign_worker/'.$foreign->kitas_file));
                }
                $file= $request->file('kitas_file');
                $kitas_name = 'kitas-' . time() .'.'. $file->getClientOriginalExtension();
                $file->move(public_path('uploads/attachment_file_foreign_worker/'),$kitas_name);   
                $kitasFile = $kitas_name;
            }
            if($request->hasFile('rptka_file'))
            {    
                if ($foreign->rptka_file) {
                    unlink(public_path('uploads/attachment_file_foreign_worker/'.$foreign->rptka_file));
                }
                $file= $request->file('rptka_file');
                $rptka_name = 'rptka-' . time() .'.'. $file->getClientOriginalExtension();
                $file->move(public_path('uploads/attachment_file_foreign_worker/'),$rptka_name);   
                $rptkaFile = $rptka_name;
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
            $foreign->paspor_file = $pasporFile ?? $foreign->paspor_file;
            $foreign->visa_file = $visaFile ?? $foreign->visa_file;
            $foreign->vitas_file = $vitasFile ?? $foreign->vitas_file;
            $foreign->kitas_file = $kitasFile ?? $foreign->kitas_file;
            $foreign->rptka_file = $rptkaFile ?? $foreign->rptka_file;
            $foreign->save();
            
            DB::commit();
            Alert::success('Update Berhasil', 'Data TKA berhasil diubah!');
            return redirect()->route('user.monthly-audit.form-foreign-worker.index',['monthlyId'=>$monthlyId]);
            
        } catch (\Throwable $th) {

            DB::rollback();
            Alert::error('Update Gagal', 'Data TKA gagal diubah!');
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
