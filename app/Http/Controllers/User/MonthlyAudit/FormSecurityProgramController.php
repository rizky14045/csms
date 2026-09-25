<?php

namespace App\Http\Controllers\User\MonthlyAudit;

use Illuminate\Http\Request;
use App\Models\SecurityExternal;
use App\Http\Helper\BlockMonthly;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\MonthlySecurityProgram;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\MainSecurityProgram;
use App\Models\MonthlyMainSecurityProgram;
use App\Models\MonthlyReport;
use App\Models\SecurityProgram;
use App\Services\MainSecurityProgram\MainSecurityProgramService;
use App\Services\MonthlyReport\MasterSyncService;
use App\Services\MonthlyReport\ProgramReportSnapshot;

class FormSecurityProgramController extends Controller
{
    public function index($monthlyId){

        $data['monthlyId'] = $monthlyId;
        $data['programs'] = MonthlySecurityProgram::with('securityProgram','programs.mainProgram')->where('monthly_report_id',$monthlyId)->orderBy('id')->get();
        $data['months'] = \App\Models\MainSecurityProgram::MONTHS;
        $data['weeks'] = \App\Models\MainSecurityProgram::WEEKS;
        return view('user.monthly-audit.form-security-program',$data);

    }

    public function visual($monthlyId){

        $data['monthlyId'] = $monthlyId;
        $data['items'] = MonthlySecurityProgram::with('securityProgram','programs')->where('monthly_report_id',$monthlyId)->get();
        return view('user.monthly-audit.form-security-program-visual',$data);

    }

    // ---------------------------------------------------------------------
    // Edit / hapus program dan detail program di laporan bulanan
    // Baris laporan adalah salinan (source_id = master); master hanya berubah bila dicentang.
    // ---------------------------------------------------------------------

    /** Laporan harus milik unit user dan belum terkirim. */
    protected function ownedReport($monthlyId): MonthlyReport
    {
        $report = MonthlyReport::findOrFail($monthlyId);
        abort_unless($report->unit_id == Auth::user()->unit_id, 404);
        abort_if($report->send_status, 403, 'Laporan sudah dikirim');

        return $report;
    }

    protected function backToIndex($monthlyId)
    {
        return redirect()->route('user.monthly-audit.security-program.index', ['monthlyId' => $monthlyId]);
    }

    protected function programRow($monthlyId, $programId): MonthlySecurityProgram
    {
        return MonthlySecurityProgram::where('monthly_report_id', $monthlyId)->where('id', $programId)->firstOrFail();
    }

    /** Salinan header program milik laporan ini; baris lama yang masih menunjuk ke master dijadikan salinan dulu. */
    protected function programCopy(MonthlySecurityProgram $row): SecurityProgram
    {
        $program = SecurityProgram::withTrashed()->findOrFail($row->program_id);

        if ($program->user_id !== null) {
            $program = ProgramReportSnapshot::copyProgram($program->getAttributes(), $program->id);
            $row->update(['program_id' => $program->id]);
        }

        return $program;
    }

    public function editProgram($monthlyId, $programId)
    {
        $this->ownedReport($monthlyId);
        $row = $this->programRow($monthlyId, $programId);
        $program = SecurityProgram::withTrashed()->findOrFail($row->program_id);

        return view('user.monthly-audit.security-program-edit', [
            'monthlyId' => $monthlyId,
            'title'     => 'Program Keamanan',
            'action'    => route('user.monthly-audit.security-program.program.update', ['monthlyId' => $monthlyId, 'programId' => $programId]),
            'name'      => $program->program_name,
            'cells'     => null,
            'hasMaster' => $program->user_id !== null || ($program->source_id && SecurityProgram::find($program->source_id)),
        ]);
    }

    public function updateProgram(Request $request, $monthlyId, $programId)
    {
        $report = $this->ownedReport($monthlyId);
        $row = $this->programRow($monthlyId, $programId);
        $data = $request->validate(['program_name' => 'required|string|max:255'], ['program_name.required' => 'Nama program harus diisi!']);

        DB::transaction(function () use ($request, $report, $row, $data) {
            $copy = $this->programCopy($row);
            $copy->forceFill(['program_name' => $data['program_name']])->save();

            if ($request->boolean('save_to_master')) {
                $master = $copy->source_id ? SecurityProgram::find($copy->source_id) : null;
                if ($master) {
                    $master->update(['program_name' => $data['program_name'], 'updated_by' => Auth::id()]);
                } else {
                    $master = new SecurityProgram();
                    $master->forceFill([
                        'user_id'     => Auth::id(),
                        'unit_id'     => $report->unit_id,
                        'program_name' => $data['program_name'],
                        'description' => $copy->description,
                        'year'        => $copy->year,
                        'created_by'  => Auth::id(),
                    ])->save();
                    $copy->forceFill(['source_id' => $master->id])->save();
                }
            }
        });

        Alert::success('Berhasil', $request->boolean('save_to_master')
            ? 'Program di laporan dan master data berhasil diperbarui!'
            : 'Program di laporan berhasil diperbarui!');

        return $this->backToIndex($monthlyId);
    }

    public function destroyProgram(Request $request, $monthlyId, $programId)
    {
        $report = $this->ownedReport($monthlyId);
        $row = $this->programRow($monthlyId, $programId);
        $deleteMaster = $request->boolean('delete_master');

        DB::transaction(function () use ($request, $report, $row, $deleteMaster) {
            $program = SecurityProgram::withTrashed()->find($row->program_id);
            $masterId = $program ? ($program->user_id !== null ? $program->id : $program->source_id) : null;

            // detail program milik program ini ikut dihapus dari laporan
            MonthlyMainSecurityProgram::where('monthly_report_id', $report->id)->where('monthly_program_id', $row->id)->get()->each(function ($main) {
                $copy = MainSecurityProgram::find($main->main_program_id);
                $main->delete();
                if ($copy && $copy->user_id === null) {
                    $copy->update(['deleted_by' => Auth::id()]);
                    $copy->delete();
                }
            });

            $row->delete();
            if ($program && $program->user_id === null) {
                $program->update(['deleted_by' => Auth::id()]);
                $program->delete();
            }

            if ($masterId) {
                $master = SecurityProgram::find($masterId);
                if ($deleteMaster && $master) {
                    // detail program master ikut dihapus (laporan lain dibekukan lebih dulu oleh model)
                    MainSecurityProgram::where('program_id', $master->id)->get()->each(function ($m) {
                        $m->update(['deleted_by' => Auth::id()]);
                        $m->delete();
                    });
                    $master->update(['deleted_by' => Auth::id()]);
                    $master->delete();
                } elseif ($master) {
                    app(MasterSyncService::class)->exclude($report, 'program', $masterId);
                }
            }
        });

        Alert::success('Berhasil', $deleteMaster ? 'Program dihapus dari laporan dan master data!' : 'Program dihapus dari laporan!');

        return $this->backToIndex($monthlyId);
    }

    protected function detailRow($monthlyId, $rowId): MonthlyMainSecurityProgram
    {
        return MonthlyMainSecurityProgram::where('monthly_report_id', $monthlyId)->where('id', $rowId)->firstOrFail();
    }

    /** Salinan detail program milik laporan ini; baris lama yang masih menunjuk ke master dijadikan salinan dulu. */
    protected function detailCopy(MonthlyMainSecurityProgram $row): MainSecurityProgram
    {
        $main = MainSecurityProgram::withTrashed()->findOrFail($row->main_program_id);

        if ($main->user_id !== null) {
            $parent = MonthlySecurityProgram::find($row->monthly_program_id);
            $main = ProgramReportSnapshot::copyMain($main->getAttributes(), $main->id, $parent->program_id ?? $main->program_id);
            $row->update(['main_program_id' => $main->id]);
        }

        return $main;
    }

    public function editDetail($monthlyId, $rowId)
    {
        $this->ownedReport($monthlyId);
        $row = $this->detailRow($monthlyId, $rowId);
        $main = MainSecurityProgram::withTrashed()->findOrFail($row->main_program_id);

        return view('user.monthly-audit.security-program-edit', [
            'monthlyId' => $monthlyId,
            'title'     => 'Detail Program',
            'action'    => route('user.monthly-audit.security-program.detail.update', ['monthlyId' => $monthlyId, 'rowId' => $rowId]),
            'name'      => $main->program_name,
            'cells'     => MainSecurityProgram::cellsFor($main),
            'months'    => MainSecurityProgram::MONTHS,
            'weeks'     => MainSecurityProgram::WEEKS,
            'hasMaster' => $main->user_id !== null || ($main->source_id && MainSecurityProgram::find($main->source_id)),
        ]);
    }

    public function updateDetail(Request $request, $monthlyId, $rowId)
    {
        $this->ownedReport($monthlyId);
        $row = $this->detailRow($monthlyId, $rowId);

        $cells = json_decode((string) $request->input('cells'), true);
        $request->merge(['cells' => is_array($cells) ? $cells : []]);
        $data = $request->validate([
            'program_name' => 'required|string|max:255',
            'cells'        => 'required|array|min:1',
            'cells.*'      => 'array|size:2',
            'cells.*.0'    => 'integer|between:1,12',
            'cells.*.1'    => 'integer|between:1,4',
        ], [
            'program_name.required' => 'Nama program harus diisi!',
            'cells.required'        => 'Pilih minimal satu minggu pada jadwal rencana!',
            'cells.min'             => 'Pilih minimal satu minggu pada jadwal rencana!',
        ]);

        $cells = MainSecurityProgram::normalizeCells($data['cells']);

        DB::transaction(function () use ($request, $row, $data, $cells) {
            $copy = $this->detailCopy($row);
            $copy->forceFill(['program_name' => $data['program_name'], 'schedule' => json_encode($cells)] + MainSecurityProgram::rangeFromCells($cells))->save();

            if ($request->boolean('save_to_master')) {
                $service = app(MainSecurityProgramService::class);
                $master = $copy->source_id ? MainSecurityProgram::find($copy->source_id) : null;
                $payload = ['program_name' => $data['program_name'], 'cells' => $cells];

                if ($master) {
                    $service->updateMainSecurityProgram($master, $payload);
                } else {
                    $parent = MonthlySecurityProgram::find($row->monthly_program_id);
                    $parentCopy = $parent ? SecurityProgram::withTrashed()->find($parent->program_id) : null;
                    $masterProgramId = $parentCopy ? ($parentCopy->user_id !== null ? $parentCopy->id : $parentCopy->source_id) : null;

                    if ($masterProgramId && SecurityProgram::find($masterProgramId)) {
                        $created = getData($service->createMainSecurityProgram($payload, $masterProgramId));
                        $copy->forceFill(['source_id' => $created['id']])->save();
                    }
                }
            }
        });

        Alert::success('Berhasil', $request->boolean('save_to_master')
            ? 'Detail program di laporan dan master data berhasil diperbarui!'
            : 'Detail program di laporan berhasil diperbarui!');

        return $this->backToIndex($monthlyId);
    }

    public function destroyDetail(Request $request, $monthlyId, $rowId)
    {
        $report = $this->ownedReport($monthlyId);
        $row = $this->detailRow($monthlyId, $rowId);
        $deleteMaster = $request->boolean('delete_master');

        DB::transaction(function () use ($report, $row, $deleteMaster) {
            $main = MainSecurityProgram::withTrashed()->find($row->main_program_id);
            $masterId = $main ? ($main->user_id !== null ? $main->id : $main->source_id) : null;

            $row->delete();
            if ($main && $main->user_id === null) {
                $main->update(['deleted_by' => Auth::id()]);
                $main->delete();
            }

            if ($masterId) {
                $master = MainSecurityProgram::find($masterId);
                if ($deleteMaster && $master) {
                    $master->update(['deleted_by' => Auth::id()]);
                    $master->delete();
                } elseif ($master) {
                    app(MasterSyncService::class)->exclude($report, 'main_program', $masterId);
                }
            }
        });

        Alert::success('Berhasil', $deleteMaster ? 'Detail program dihapus dari laporan dan master data!' : 'Detail program dihapus dari laporan!');

        return $this->backToIndex($monthlyId);
    }
}
