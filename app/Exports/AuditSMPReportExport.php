<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;

class AuditSMPReportExport implements FromView, WithColumnWidths, WithTitle
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function view(): View
    {
        return view('exports.audit-smp-report', $this->data);
    }

    public function title(): string
    {
        return 'Audit SMP';
    }

    public function columnWidths(): array
    {
        return [
            'A' => 20, 'B' => 10, 'C' => 6, 'D' => 40,
            'E' => 12, 'F' => 12, 'G' => 12, 'H' => 12,
            'I' => 25, 'J' => 25, 'K' => 25, 'L' => 14, 'M' => 16,
        ];
    }
}
