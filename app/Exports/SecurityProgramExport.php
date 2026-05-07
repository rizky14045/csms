<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SecurityProgramExport implements
    FromView,
    WithStyles,
    WithColumnWidths,
    WithDrawings,WithTitle
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function title(): string
    {
        return 'Program Keamanan';
    }

    public function view(): View
    {
        return view(
            'exports.monthly-audit.security-program-excel',
            $this->data
        );
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getParent()
            ->getDefaultStyle()
            ->getFont()
            ->setName('Arial');

        $sheet->getParent()
            ->getDefaultStyle()
            ->getFont()
            ->setSize(8);

        $sheet->getStyle('A1:BA1000')
            ->getAlignment()
            ->setWrapText(true);

        return [];
    }

    public function columnWidths(): array
    {
        return [

            'A' => 5,
            'B' => 25,
            'C' => 15,

            'D' => 4,
            'E' => 4,
            'F' => 4,
            'G' => 4,
            'H' => 4,
            'I' => 4,
            'J' => 4,
            'K' => 4,
            'L' => 4,
            'M' => 4,
            'N' => 4,
            'O' => 4,
            'P' => 4,
            'Q' => 4,
            'R' => 4,
            'S' => 4,
            'T' => 4,
            'U' => 4,
            'V' => 4,
            'W' => 4,
            'X' => 4,
            'Y' => 4,
            'Z' => 4,

            'AA' => 4,
            'AB' => 4,
            'AC' => 4,
            'AD' => 4,
            'AE' => 4,
            'AF' => 4,
            'AG' => 4,
            'AH' => 4,
            'AI' => 4,
            'AJ' => 4,
            'AK' => 4,
            'AL' => 4,
            'AM' => 4,
            'AN' => 4,
            'AO' => 4,
            'AP' => 4,
            'AQ' => 4,
            'AR' => 4,
            'AS' => 4,
            'AT' => 4,
            'AU' => 4,
            'AV' => 4,
            'AW' => 4,
            'AX' => 4,
            'AY' => 4,

        ];
    }

    public function drawings()
    {
        $drawing = new Drawing();

        $drawing->setName('Logo');
        $drawing->setPath(public_path('logo.png'));
        $drawing->setHeight(60);
        $drawing->setCoordinates('A1');

        return $drawing;
    }
}