<?php
namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class FormFormulirExport implements FromView, WithDrawings, WithStyles, WithColumnWidths
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function view(): View
    {
        return view('exports.monthly-audit.form-formulir', $this->data);
    }

    public function columnWidths(): array
    {
        return [
            'A' => 25,
            'B' => 15,
            'C' => 15,
            'D' => 15,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Mengatur font default untuk seluruh sheet
            1 => ['font' => ['name' => 'Arial', 'size' => 11]],
        ];
    }

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setPath(public_path('logo.png'));
        $drawing->setHeight(60);
        $drawing->setCoordinates('A1');
        // Memberikan sedikit offset agar tidak menempel garis
        $drawing->setOffsetX(5); 
        $drawing->setOffsetY(5);

        return $drawing;
    }
}