<?php

namespace App\Http\Controllers;

use App\Exports\AuditSMPReportExport;
use App\Models\AuditSmpData;
use App\Models\Auditor;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class AuditSMPExportController extends Controller
{
    public function export(AuditSmpData $audit)
    {
        $user = auth()->user();
        $isCentral = in_array($user->type, ['admin', 'pusat'], true);
        $isOwnUnit = $audit->unit_id == $user->unit_id;
        $isAuditor = $audit->hasAuditor($user);

        if (!$isCentral && !$isOwnUnit && !$isAuditor) {
            abort(404);
        }

        if ((int) $audit->status < 3) {
            abort(404);
        }

        $audit->load(
            'unit',
            'childrenHeader.pernyataan.kriteria.evidence',
            'childrenHeader.kriteria.evidence'
        );

        $filename = 'audit-smp-' . Str::slug($audit->unit->name ?? 'unit') . '-' . $audit->id . '.xlsx';

        return Excel::download(new AuditSMPReportExport(['audit' => $audit]), $filename);
    }
}
