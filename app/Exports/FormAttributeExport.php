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

class FormAttributeExport implements
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
            'exports.monthly-audit.form-attribute-excel',
            $this->data
        );
    }


    public function title(): string
    {
        return 'Attribute';
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
            ->setSize(10);

        $sheet->getStyle('A1:J500')
            ->getAlignment()
            ->setWrapText(true);

        return [

            // Header dokumen
            'A1:J4' => [
                'font' => [
                    'bold' => true
                ]
            ],

        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 35,
            'C' => 20,
            'D' => 12,
            'E' => 20,
            'F' => 12,
            'G' => 12,
            'H' => 18,
            'I' => 20,
            'J' => 30,
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