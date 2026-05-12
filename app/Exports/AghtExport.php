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

class AghtExport implements
    FromView,
    WithStyles,
    WithColumnWidths,
    WithDrawings,
    WithTitle
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function view(): View
    {
        return view(
            'exports.monthly-audit.aght-excel',
            $this->data
        );
    }
    public function title(): string
    {
        return 'Data AGHT';
    }

    public function styles(Worksheet $sheet)
    {
        // Font global
        $sheet->getParent()
            ->getDefaultStyle()
            ->getFont()
            ->setName('Arial');

        $sheet->getParent()
            ->getDefaultStyle()
            ->getFont()
            ->setSize(10);

        // Wrap text
        $sheet->getStyle('A1:J300')
            ->getAlignment()
            ->setWrapText(true);

        return [

            // Header
            'A1:J4' => [
                'font' => [
                    'bold' => true
                ],

                'alignment' => [
                    'vertical' => 'center'
                ]
            ],

            // Table Header
            'A7:J8' => [

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
            'B' => 35,
            'C' => 15,
            'D' => 12,
            'E' => 25,
            'F' => 30,
            'G' => 35,
            'H' => 25,
            'I' => 25,
            'J' => 15,
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

        $drawing->setOffsetX(10);
        $drawing->setOffsetY(5);

        return $drawing;
    }
}