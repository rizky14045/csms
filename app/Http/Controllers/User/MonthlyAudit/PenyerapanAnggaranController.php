<?php

namespace App\Http\Controllers\User\MonthlyAudit;

use App\Http\Controllers\Controller;
use App\Http\Helper\BlockMonthly;
use App\Models\AghtData;
use App\Models\LaporanBulananBiaya;
use App\Models\MonthlyReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class PenyerapanAnggaranController extends Controller
{
    public function index($monthlyId){

        $data['monthlyId'] = $monthlyId;
        $dataBiaya = LaporanBulananBiaya::where('monthly_report_id', $monthlyId)
            ->whereIn('type', ['administrasi', 'pemeliharaan'])
            ->get()
            ->map(function ($item) {
               $item->prosentase_penyerapan = $item->jumlah_anggaran != 0
                    ? ($item->penyerapan_anggaran / $item->jumlah_anggaran) * 100
                    : 0;
                return $item;
            })
            ->groupBy('type');

        $data['administrasi'] = $dataBiaya->get('administrasi', collect());
        $data['pemeliharaan'] = $dataBiaya->get('pemeliharaan', collect());
        return view('user.monthly-audit.penyerapan-anggaran',$data);
    }

    public function create($monthlyId){

        $data['monthlyId'] = $monthlyId;
        return view('user.monthly-audit.penyerapan-anggaran.create',$data);
    }
    public function store(Request $request,$monthlyId){

        try {
            DB::beginTransaction();

            $userId = Auth::guard('web')->user()->id;

            $request->validate([
                'type' => 'required|in:pemeliharaan,administrasi',
                'kode_aktifitas' => 'required',
                'kode_prk' => 'required',
                'deskripsi_kegiatan' => 'required',
                'jumlah_anggaran' => 'required',
                'penyerapan_anggaran' => 'required',
                'keterangan' => 'required',
            ],[
                'type.required' => 'Jenis Anggaran harus diisi!',
                'type.in' => 'Jenis Anggaran tidak sesuai!',
                'kode_aktifitas.required' => 'Kode Aktifitas harus diisi!',
                'kode_prk.required' => 'Kode PRK harus diisi!',
                'deskripsi_kegiatan.required' => 'Deskripsi Kegiatan harus diisi!',
                'jumlah_anggaran.required' => 'Jumlah Anggaran harus diisi!',
                'penyerapan_anggaran.required' => 'Penyerapan Anggaran harus diisi!',
                'keterangan.required' => 'Keterangan harus diisi!',
            ]);

            LaporanBulananBiaya::create([
                'monthly_report_id' => $monthlyId,
                'user_id' => $userId,
                'type' => $request->type,
                'kode_aktifitas' => $request->kode_aktifitas,
                'kode_prk' => $request->kode_prk,
                'deskripsi_kegiatan' => $request->deskripsi_kegiatan,
                'jumlah_anggaran' => $request->jumlah_anggaran,
                'penyerapan_anggaran' => $request->penyerapan_anggaran,
                'keterangan' => $request->keterangan,
            ]);
            
            DB::commit();
            Alert::success('Tambah Berhasil', 'Data Penyerapan Anggaran berhasil dibuat!');
            return redirect()->route('user.monthly-audit.penyerapan-anggaran.index',['monthlyId'=>$monthlyId]);
            
        } catch (\Throwable $th) {

            DB::rollback();
            Alert::error('Tambah Gagal', 'Data Penyerapan Anggaran gagal dibuat!');
            return redirect()->route('user.monthly-audit.penyerapan-anggaran.index',['monthlyId'=>$monthlyId]);
        }
    }

    public function edit($monthlyId,$anggaranId){

        $anggaran = LaporanBulananBiaya::where('id',$anggaranId)->where('monthly_report_id', $monthlyId)->first();
        if (!$anggaran) {
            Alert::warning('Warning', 'Data tidak ditemukan!');
            return redirect()->route('user.monthly-audit.penyerapan-anggaran.index',['monthlyId'=>$monthlyId]);
        }
        $data['monthlyId'] = $monthlyId;
        $data['anggaran'] = $anggaran;
        return view('user.monthly-audit.penyerapan-anggaran.edit',$data);
    }

    public function update(Request $request,$monthlyId,$anggaranId){

        try {

            $request->validate([
                'type' => 'required|in:pemeliharaan,administrasi',
                'kode_aktifitas' => 'required',
                'kode_prk' => 'required',
                'deskripsi_kegiatan' => 'required',
                'jumlah_anggaran' => 'required',
                'penyerapan_anggaran' => 'required',
                'keterangan' => 'required',
            ],[
                'type.required' => 'Jenis Anggaran harus diisi!',
                'type.in' => 'Jenis Anggaran tidak sesuai!',
                'kode_aktifitas.required' => 'Kode Aktifitas harus diisi!',
                'kode_prk.required' => 'Kode PRK harus diisi!',
                'deskripsi_kegiatan.required' => 'Deskripsi Kegiatan harus diisi!',
                'jumlah_anggaran.required' => 'Jumlah Anggaran harus diisi!',
                'penyerapan_anggaran.required' => 'Penyerapan Anggaran harus diisi!',
                'keterangan.required' => 'Keterangan harus diisi!',
            ]);

            $anggaran = LaporanBulananBiaya::where('id',$anggaranId)->where('monthly_report_id', $monthlyId)->first();
            if (!$anggaran) {
                Alert::warning('Warning', 'Data tidak ditemukan!');
                return redirect()->route('user.monthly-audit.penyerapan-anggaran.index',['monthlyId'=>$monthlyId]);
            }

            DB::beginTransaction();

            $anggaran->type = $request->type;
            $anggaran->kode_aktifitas = $request->kode_aktifitas;
            $anggaran->kode_prk = $request->kode_prk;
            $anggaran->deskripsi_kegiatan = $request->deskripsi_kegiatan;
            $anggaran->jumlah_anggaran = $request->jumlah_anggaran;
            $anggaran->penyerapan_anggaran = $request->penyerapan_anggaran;
            $anggaran->keterangan = $request->keterangan;
            $anggaran->save();
         
        
            DB::commit();
            Alert::success('Update Berhasil', 'Data Penyerapan Anggaran berhasil diubah!');
            return redirect()->route('user.monthly-audit.penyerapan-anggaran.index',['monthlyId'=>$monthlyId]);
            
        } catch (\Throwable $th) {

            DB::rollback();
            Alert::error('Update Gagal', 'Data Penyerapan Anggaran gagal diubah!');
            return redirect()->route('user.monthly-audit.penyerapan-anggaran.index',['monthlyId'=>$monthlyId]);
        }
    }

    public function destroy($monthlyId,$anggaranId){

        try {

            $anggaran = LaporanBulananBiaya::where('id',$anggaranId)->where('monthly_report_id', $monthlyId)->first();
            if (!$anggaran) {
                Alert::warning('Warning', 'Data tidak ditemukan!');
                return redirect()->route('user.monthly-audit.penyerapan-anggaran.index',['monthlyId'=>$monthlyId]);
            }
            DB::beginTransaction();
            $anggaran->delete();
            DB::commit();
            Alert::success('Delete Berhasil', 'Data Penyerapan Anggaran berhasil dihapus!');
            return redirect()->route('user.monthly-audit.penyerapan-anggaran.index',['monthlyId'=>$monthlyId]);
            
        } catch (\Throwable $th) {

            DB::rollback();
            Alert::error('Delete Gagal', 'Data Penyerapan Anggaran gagal dihapus!');
            return redirect()->route('user.monthly-audit.penyerapan-anggaran.index',['monthlyId'=>$monthlyId]);
        }
    }

}
