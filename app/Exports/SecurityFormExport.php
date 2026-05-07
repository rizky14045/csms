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

class SecurityFormExport implements FromView, WithStyles, WithColumnWidths, WithDrawings,WithTitle
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function view(): View
    {
        return view(
            'exports.monthly-audit.security-form-excel',
            $this->data
        );
    }

    public function title(): string
    {
        return 'Satpam';
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
        $sheet->getStyle('A1:O300')
            ->getAlignment()
            ->setWrapText(true);

        // Tinggi row header
        $sheet->getRowDimension(1)->setRowHeight(25);
        $sheet->getRowDimension(2)->setRowHeight(25);
        $sheet->getRowDimension(3)->setRowHeight(25);
        $sheet->getRowDimension(4)->setRowHeight(25);

        return [

            // Header dokumen
            'A1:O4' => [
                'font' => [
                    'bold' => true,
                ],

                'alignment' => [
                    'vertical' => 'center',
                ]
            ],

            // Header table
            'A7:O8' => [
                'font' => [
                    'bold' => true,
                ],

                'alignment' => [
                    'horizontal' => 'center',
                    'vertical' => 'center',
                ]
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 25,
            'C' => 20,
            'D' => 15,
            'E' => 22,
            'F' => 18,
            'G' => 18,
            'H' => 28,
            'I' => 10,
            'J' => 10,
            'K' => 10,
            'L' => 10,
            'M' => 20,
            'N' => 25,
            'O' => 20,
        ];
    }

    public function drawings()
    {
        $drawing = new Drawing();

        $drawing->setName('Logo');
        $drawing->setDescription('Logo PLN');

        // public/logo.png
        $drawing->setPath(public_path('logo.png'));

        $drawing->setHeight(60);

        $drawing->setCoordinates('A1');

        $drawing->setOffsetX(10);
        $drawing->setOffsetY(5);

        return $drawing;
    }
}