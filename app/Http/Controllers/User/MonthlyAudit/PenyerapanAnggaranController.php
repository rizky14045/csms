<?php

namespace App\Http\Controllers\User\MonthlyAudit;

use App\Http\Controllers\Controller;
use App\Http\Helper\BlockMonthly;
use App\Models\AghtData;
use App\Models\LaporanBulananBiaya;
use App\Models\BudgetMaster;
use App\Models\MonthlyReport;
use App\Services\MonthlyReport\MasterSyncService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class PenyerapanAnggaranController extends Controller
{
    protected function rules()
    {
        return [
            'type' => 'required|in:pemeliharaan,administrasi',
            'kode_aktifitas' => 'required',
            'kode_prk' => 'required',
            'deskripsi_kegiatan' => 'required',
            'jumlah_anggaran' => 'required',
            'penyerapan_anggaran' => 'required',
            'keterangan' => 'required',
        ];
    }

    protected function messages()
    {
        return [
            'type.required' => 'Jenis Anggaran harus diisi!',
            'type.in' => 'Jenis Anggaran tidak sesuai!',
            'kode_aktifitas.required' => 'Kode Aktifitas harus diisi!',
            'kode_prk.required' => 'Kode PRK harus diisi!',
            'deskripsi_kegiatan.required' => 'Deskripsi Kegiatan harus diisi!',
            'jumlah_anggaran.required' => 'Jumlah Anggaran harus diisi!',
            'penyerapan_anggaran.required' => 'Penyerapan Anggaran harus diisi!',
            'keterangan.required' => 'Keterangan harus diisi!',
        ];
    }

    /** Laporan harus milik unit user dan belum terkirim. */
    protected function ownedReport($monthlyId)
    {
        $report = MonthlyReport::findOrFail($monthlyId);
        abort_unless($report->unit_id == Auth::user()->unit_id, 404);
        abort_if($report->send_status, 403, 'Laporan sudah dikirim');

        return $report;
    }

    public function sync($monthlyId)
    {
        $report = $this->ownedReport($monthlyId);
        $added = app(MasterSyncService::class)->syncBudgets($report);

        if ($added > 0) {
            Alert::success('Sinkron Berhasil', "{$added} data baru dari master data ditambahkan ke Penyerapan Anggaran.");
        } else {
            Alert::info('Sudah Terbaru', 'Tidak ada data baru dari master data untuk Penyerapan Anggaran.');
        }

        return redirect()->route('user.monthly-audit.penyerapan-anggaran.index', ['monthlyId' => $monthlyId]);
    }

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
            $report = $this->ownedReport($monthlyId);
            $request->validate($this->rules(), $this->messages());

            DB::beginTransaction();

            $userId = Auth::guard('web')->user()->id;
            $data = $request->only(BudgetMaster::FIELDS);

            $row = LaporanBulananBiaya::create($data + [
                'monthly_report_id' => $report->id,
                'user_id' => $userId,
            ]);

            if ($request->boolean('save_to_master')) {
                $master = BudgetMaster::create($data + [
                    'unit_id' => $report->unit_id,
                    'user_id' => $userId,
                    'created_by' => $userId,
                ]);
                $row->forceFill(['source_id' => $master->id])->save();
            }

            DB::commit();
            Alert::success('Tambah Berhasil', $request->boolean('save_to_master')
                ? 'Data laporan dan master data berhasil dibuat!'
                : 'Data Penyerapan Anggaran berhasil dibuat!');
            return redirect()->route('user.monthly-audit.penyerapan-anggaran.index',['monthlyId'=>$monthlyId]);

        } catch (\Illuminate\Validation\ValidationException | \Symfony\Component\HttpKernel\Exception\HttpException $e) {
            DB::rollback();
            throw $e;
        } catch (\Throwable $th) {

            DB::rollback();
            Alert::error('Tambah Gagal', 'Data Penyerapan Anggaran gagal dibuat!');
            return redirect()->route('user.monthly-audit.penyerapan-anggaran.index',['monthlyId'=>$monthlyId]);
        }
    }

    public function edit($monthlyId,$anggaranId){

        $this->ownedReport($monthlyId);
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
            $report = $this->ownedReport($monthlyId);
            $request->validate($this->rules(), $this->messages());

            $anggaran = LaporanBulananBiaya::where('id',$anggaranId)->where('monthly_report_id', $monthlyId)->first();
            if (!$anggaran) {
                Alert::warning('Warning', 'Data tidak ditemukan!');
                return redirect()->route('user.monthly-audit.penyerapan-anggaran.index',['monthlyId'=>$monthlyId]);
            }

            DB::beginTransaction();

            $data = $request->only(BudgetMaster::FIELDS);
            $anggaran->update($data);

            if ($request->boolean('save_to_master')) {
                $master = $anggaran->source_id ? BudgetMaster::find($anggaran->source_id) : null; // find() mengabaikan yang sudah dihapus
                if ($master) {
                    $master->update($data + ['updated_by' => Auth::id()]);
                } else {
                    $master = BudgetMaster::create($data + [
                        'unit_id' => $report->unit_id,
                        'user_id' => Auth::id(),
                        'created_by' => Auth::id(),
                    ]);
                    $anggaran->forceFill(['source_id' => $master->id])->save();
                }
            }

            DB::commit();
            Alert::success('Update Berhasil', $request->boolean('save_to_master')
                ? 'Data laporan dan master data berhasil diubah!'
                : 'Data Penyerapan Anggaran berhasil diubah!');
            return redirect()->route('user.monthly-audit.penyerapan-anggaran.index',['monthlyId'=>$monthlyId]);

        } catch (\Illuminate\Validation\ValidationException | \Symfony\Component\HttpKernel\Exception\HttpException $e) {
            DB::rollback();
            throw $e;
        } catch (\Throwable $th) {

            DB::rollback();
            Alert::error('Update Gagal', 'Data Penyerapan Anggaran gagal diubah!');
            return redirect()->route('user.monthly-audit.penyerapan-anggaran.index',['monthlyId'=>$monthlyId]);
        }
    }

    public function destroy(Request $request,$monthlyId,$anggaranId){

        try {
            $report = $this->ownedReport($monthlyId);

            $anggaran = LaporanBulananBiaya::where('id',$anggaranId)->where('monthly_report_id', $monthlyId)->first();
            if (!$anggaran) {
                Alert::warning('Warning', 'Data tidak ditemukan!');
                return redirect()->route('user.monthly-audit.penyerapan-anggaran.index',['monthlyId'=>$monthlyId]);
            }

            $deleteMaster = $request->boolean('delete_master');

            DB::beginTransaction();

            $sourceId = $anggaran->source_id;
            $anggaran->delete();

            if ($sourceId) {
                $master = BudgetMaster::find($sourceId);
                if ($deleteMaster && $master) {
                    $master->update(['deleted_by' => Auth::id()]);
                    $master->delete();
                } elseif ($master) {
                    // dihapus hanya dari laporan ini: jangan muncul lagi saat sinkron
                    app(MasterSyncService::class)->exclude($report, 'budget', $sourceId);
                }
            }

            DB::commit();
            Alert::success('Delete Berhasil', $deleteMaster && $sourceId
                ? 'Data dihapus dari laporan dan master data!'
                : 'Data dihapus dari laporan!');
            return redirect()->route('user.monthly-audit.penyerapan-anggaran.index',['monthlyId'=>$monthlyId]);

        } catch (\Symfony\Component\HttpKernel\Exception\HttpException $e) {
            DB::rollback();
            throw $e;
        } catch (\Throwable $th) {

            DB::rollback();
            Alert::error('Delete Gagal', 'Data Penyerapan Anggaran gagal dihapus!');
            return redirect()->route('user.monthly-audit.penyerapan-anggaran.index',['monthlyId'=>$monthlyId]);
        }
    }

}
