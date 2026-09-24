<?php

namespace App\Http\Controllers;

use App\Exports\AssessmentViewExport;
use App\Models\Kpi;
use App\Models\Marturity;
use App\Services\Kpi\KpiService;
use App\Services\Marturity\MarturityService;
use App\Services\Score\MlActualCalculator;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class MaturityKpiExportController extends Controller
{
    /** Akses: pusat/admin bebas; user lain (unit, MMRK) hanya untuk unitnya sendiri. */
    protected function authorizeExport($record, string $type)
    {
        $user = auth()->user();
        $isCentral = in_array($user->type, ['admin', 'pusat'], true);

        if (!$isCentral && $record->unit_id != $user->unit_id) {
            abort(404);
        }

        $status = (int) $record->status;

        // SA: sudah dikirim ke MMRK (status >= 1). FA: validasi Pusat selesai (status 3).
        if (($type === 'sa' && $status < 1) || ($type === 'fa' && $status !== 3)) {
            abort(404);
        }
    }

    public function marturity(Marturity $marturity, $type, MarturityService $service)
    {
        $this->authorizeExport($marturity, $type);

        $areas = getData($service->getAlMarturityArea(['subAreas', 'subAreas.levels', 'subAreas.levels.notes'], $marturity->id));
        $checked = $service->getCheckedMap($marturity);
        $marturity->loadMissing('unit');

        $filename = ($type === 'fa' ? 'FA' : 'SA') . '-maturity-' . Str::slug($marturity->unit->name ?? 'unit')
            . '-' . $marturity->year . '-' . $marturity->period_slug . '.xlsx';

        return Excel::download(new AssessmentViewExport('exports.assessment-marturity', [
            'mode'      => $type,
            'marturity' => $marturity,
            'areas'     => $areas,
            'actual'    => $type === 'fa' ? MlActualCalculator::marturity($areas, $checked) : null,
            'checked'   => $checked,
        ], strtoupper($type) . ' Maturity'), $filename);
    }

    public function kpi(Kpi $kpi, $type, KpiService $service)
    {
        $this->authorizeExport($kpi, $type);

        $areas = getData($service->getAllKpiArea(0, false, $kpi->id, ['subAreas', 'subAreas.levels']));
        $checked = $service->getCheckedMap($kpi);
        $kpi->loadMissing('unit');

        $filename = ($type === 'fa' ? 'FA' : 'SA') . '-kpi-' . Str::slug($kpi->unit->name ?? 'unit')
            . '-' . $kpi->year . '-' . $kpi->period_slug . '.xlsx';

        return Excel::download(new AssessmentViewExport('exports.assessment-kpi', [
            'mode'    => $type,
            'kpi'     => $kpi,
            'areas'   => $areas,
            'actual'  => $type === 'fa' ? MlActualCalculator::kpi($areas, $checked) : null,
            'checked' => $checked,
        ], strtoupper($type) . ' KPI'), $filename);
    }
}
