<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;

class AssessmentViewExport implements FromView, WithTitle, WithColumnWidths, WithStyles
{
    protected $view;
    protected $data;
    protected $title;

    public function __construct(string $view, array $data, string $title)
    {
        $this->view = $view;
        $this->data = $data;
        $this->title = $title;
    }

    public function view(): View
    {
        return view($this->view, $this->data);
    }

    public function title(): string
    {
        return $this->title;
    }

    /** Teks panjang dibungkus ke bawah (wrap) dan rata atas agar tidak terpotong. */
    public function styles(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet)
    {
        $range = 'A1:' . $sheet->getHighestColumn() . $sheet->getHighestRow();
        $sheet->getStyle($range)->getAlignment()
            ->setWrapText(true)
            ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);

        return [];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 6, 'B' => 32, 'C' => 8, 'D' => 45, 'E' => 40,
            'F' => 14, 'G' => 14, 'H' => 14, 'I' => 14, 'J' => 14, 'K' => 14, 'L' => 14, 'M' => 14,
        ];
    }
}
