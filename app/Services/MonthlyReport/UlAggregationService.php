<?php

namespace App\Services\MonthlyReport;

use App\Models\ForeignWorker;
use App\Models\MonthlyGangguan;
use App\Models\MonthlyReport;
use App\Models\MonthlySecurityExternal;
use App\Models\OutsourceEmployee;
use App\Models\ReportEmployee;
use App\Models\SecurityForm;
use App\Models\Unit;

/**
 * Menggabungkan data laporan bulanan Unit induk dengan UL-UL di bawahnya.
 * UL mengirim laporannya sendiri ke unit induk (sent_to_parent), lalu unit induk
 * menggabungkan totalnya sebelum mengirim ke Pusat/MMRK lewat alur yang sudah ada.
 */
class UlAggregationService
{
    public function activeUls(Unit $unit)
    {
        return Unit::where('parent_unit_id', $unit->id)->where('type', 'UL')->orderBy('name')->get();
    }

    public function hasUls(Unit $unit)
    {
        return $this->activeUls($unit)->isNotEmpty();
    }

    /** Status tiap UL untuk bulan tertentu: laporan (jika ada) + apakah sudah kirim ke induk. */
    public function ulStatuses(Unit $unit, string $reportDate)
    {
        return $this->activeUls($unit)->map(function ($ul) use ($reportDate) {
            $report = MonthlyReport::where('unit_id', $ul->id)->where('report_date', $reportDate)->first();

            return [
                'unit'   => $ul,
                'report' => $report,
                'sent'   => (bool) ($report->sent_to_parent ?? false),
            ];
        });
    }

    /** Daftar UL yang laporannya untuk bulan itu BELUM dikirim ke induk (dipakai untuk menahan tombol Kirim induk). */
    public function pendingUls(Unit $unit, string $reportDate)
    {
        return $this->ulStatuses($unit, $reportDate)->reject(fn($s) => $s['sent']);
    }

    /** MonthlyReport milik UL-UL yang SUDAH mengirim ke induk untuk bulan itu. */
    public function sentUlReports(Unit $unit, string $reportDate)
    {
        return $this->ulStatuses($unit, $reportDate)
            ->filter(fn($s) => $s['sent'])
            ->map(fn($s) => $s['report']);
    }

    /** Id laporan induk + seluruh UL yang sudah kirim untuk bulan yang sama. Dipakai untuk menggabungkan total di semua tab. */
    public function reportIdsFor(MonthlyReport $ownReport)
    {
        $unit = Unit::find($ownReport->unit_id);
        $ulReports = $this->sentUlReports($unit, $ownReport->report_date);

        return collect([$ownReport->id])->merge($ulReports->pluck('id'));
    }

    /** Total gabungan (induk + seluruh UL yang sudah kirim) untuk tab Form Formulir. */
    public function aggregateFormFormulir(MonthlyReport $ownReport)
    {
        $unit = Unit::find($ownReport->unit_id);
        $ulReports = $this->sentUlReports($unit, $ownReport->report_date);
        $reportIds = $this->reportIdsFor($ownReport);

        $employees = ReportEmployee::whereIn('monthly_report_id', $reportIds)->get();
        $outsources = OutsourceEmployee::whereIn('monthly_report_id', $reportIds)->get();
        $securities = SecurityForm::join('securities', 'securities.id', '=', 'security_forms.security_id')
            ->whereIn('security_forms.monthly_report_id', $reportIds)
            ->select('securities.*')->get();
        $securityExternal = MonthlySecurityExternal::join('security_externals', 'security_externals.id', '=', 'monthly_security_externals.security_external_id')
            ->whereIn('monthly_security_externals.monthly_report_id', $reportIds)
            ->select('security_externals.*');
        $foreign = ForeignWorker::whereIn('monthly_report_id', $reportIds);

        $employeeMan = $employees->sum('employee_man');
        $employeeWoman = $employees->sum('employee_woman');
        $studentMan = $employees->sum('student_man');
        $studentWoman = $employees->sum('student_woman');

        $securityPolri = (clone $securityExternal)->where('note', 'Polri')->count();
        $securityTNI = (clone $securityExternal)->where('note', 'TNI')->count();
        $securityExternalTotal = (clone $securityExternal)->count();

        $foreignAhli = (clone $foreign)->where('position', 'Tenaga Ahli')->count();
        $foreignStaff = (clone $foreign)->where('position', 'staff')->count();
        $foreignTotal = (clone $foreign)->count();

        $totalAll = $employeeMan + $employeeWoman + $studentMan + $studentWoman
            + $outsources->sum('total') + $securityExternalTotal + $foreignTotal;

        $totalMan = $employeeMan + $studentMan + $outsources->sum('man')
            + (clone $securityExternal)->where('note', 'TNI')->where('gender', 'Pria')->count()
            + (clone $securityExternal)->where('note', 'Polri')->where('gender', 'Pria')->count()
            + $securities->where('gender', 'Pria')->count();

        $totalWoman = $employeeWoman + $studentWoman + $outsources->sum('woman')
            + (clone $securityExternal)->where('note', 'TNI')->where('gender', 'Wanita')->count()
            + (clone $securityExternal)->where('note', 'Polri')->where('gender', 'Wanita')->count()
            + $securities->where('gender', 'Wanita')->count();

        $gangguanRows = MonthlyGangguan::whereIn('monthly_report_id', $reportIds)->get();

        return [
            'employee' => (object) [
                'employee_man' => $employeeMan, 'employee_woman' => $employeeWoman,
                'student_man' => $studentMan, 'student_woman' => $studentWoman,
            ],
            'outsources' => $outsources,
            'securityPolri' => $securityPolri,
            'securityTNI' => $securityTNI,
            'securityExternal' => $securityExternalTotal,
            'foreignAhli' => $foreignAhli,
            'foreignStaff' => $foreignStaff,
            'foreign' => $foreignTotal,
            'totalAll' => $totalAll,
            'totalAllMan' => $totalMan,
            'totalAllWoman' => $totalWoman,
            'securities' => $securities,
            'gangguan' => (object) [
                'kriminal' => $gangguanRows->sum('kriminal'),
                'politis' => $gangguanRows->sum('politis'),
                'kebakaran' => $gangguanRows->sum('kebakaran'),
                'bencana_alam' => $gangguanRows->sum('bencana_alam'),
                'other' => $gangguanRows->sum('other'),
            ],
            'ulCount' => $ulReports->count(),
            'ulTotal' => $this->activeUls($unit)->count(),
        ];
    }
}
