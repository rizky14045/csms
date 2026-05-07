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

class ForeignWorkerExport implements
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

    public function view(): View
    {
        return view(
            'exports.monthly-audit.foreign-worker-excel',
            $this->data
        );
    }

    public function title(): string
    {
        return 'TKA';
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
            ->setSize(9);

        $sheet->getStyle('A1:V500')
            ->getAlignment()
            ->setWrapText(true);

        return [

            'A1:V4' => [
                'font' => [
                    'bold' => true
                ]
            ],

            'A7:V10' => [
                'font' => [
                    'bold' => true
                ],

                'alignment' => [
                    'horizontal' => 'center',
                    'vertical' => 'center'
                ]
            ]
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 25,
            'C' => 15,
            'D' => 20,
            'E' => 20,
            'F' => 10,
            'G' => 15,
            'H' => 12,
            'I' => 15,
            'J' => 10,
            'K' => 15,
            'L' => 10,
            'M' => 15,
            'N' => 10,
            'O' => 15,
            'P' => 10,
            'Q' => 15,
            'R' => 15,
            'S' => 15,
            'T' => 20,
        ];
    }

    public function drawings()
    {
        $drawing = new Drawing();

        $drawing->setName('Logo');
        $drawing->setDescription('Logo PLN');

        $drawing->setPath(public_path('logo.png'));

        $drawing->setHeight(60);

        $drawing->setCoordinates('A1');

        return $drawing;
    }
}