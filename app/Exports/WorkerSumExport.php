<?php
namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;


class WorkerSumExport implements FromView, WithStyles, WithColumnWidths
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function view(): View
    {
        return view('exports.monthly-audit.worker-sum-excel', $this->data);
    }

    public function columnWidths(): array
    {
        return [
            'A' => 3,   // Margin kiri
            'B' => 5,   // NO
            'C' => 25,  // NAMA
            'D' => 25,  // JABATAN
            'E' => 20,  // UNIT KERJA
            'F' => 12,  // PELATIHAN SMP
            'G' => 12,  // AUDITOR SMP
            'H' => 12,  // UTAMA
            'I' => 12,  // INVESTIGASI
            'J' => 12,  // MANSRISK
            'K' => 15,  // STAKEHOLDER
            'L' => 20,  // PENDIDIKAN
            'M' => 20,  // KETERANGAN
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Set Font Global agar tidak error di berbagai OS
        $sheet->getParent()->getDefaultStyle()->getFont()->setName('Arial');
        $sheet->getParent()->getDefaultStyle()->getFont()->setSize(10);

        // Bungkus teks otomatis untuk kolom keterangan
        $sheet->getStyle('M')->getAlignment()->setWrapText(true);
        
        return [
            // Baris header dokumen
            '2:4' => ['font' => ['bold' => true]],
        ];
    }

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setPath(public_path('logo.png'));
        $drawing->setHeight(55);
        $drawing->setCoordinates('C1');
        $drawing->setOffsetX(10);
        return $drawing;
    }
}