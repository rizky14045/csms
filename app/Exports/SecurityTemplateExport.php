<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SecurityTemplateExport implements FromArray, WithStyles, WithColumnWidths, WithTitle
{
    protected $unitCode;

    /** $unitCode diisi hanya untuk unit yang punya UL / UL: menambahkan kolom Kode Unit. */
    public function __construct($unitCode = null)
    {
        $this->unitCode = $unitCode;
    }

    public function array(): array
    {
        $rows = [
            [
                'Nama',
                'Jenis Kelamin',
                'Unit Kerja',
                'NID',
                'Nomor Registrasi KTA',
                'Tanggal Kadaluarsa KTA',
                'Jabatan',
                'Tempat Lahir',
                'Tanggal Lahir',
                'Kualifikasi',
                'Pendidikan Terakhir',
                'Catatan',
            ],
            [
                'Contoh Nama',
                'Pria',
                'Unit Induk',
                '1234567890',
                'REG-0001',
                '2027-12-31',
                'Anggota',
                'Jakarta',
                '1995-01-31',
                'Madya',
                'SMA',
                'Opsional',
            ],
        ];

        if ($this->unitCode !== null) {
            $rows[0][] = 'Kode Unit';
            $rows[1][] = $this->unitCode;
        }

        return $rows;
    }

    public function title(): string
    {
        return 'Format Import Satpam';
    }

    public function styles(Worksheet $sheet)
    {
        $lastCol = $this->unitCode !== null ? 'M' : 'L';
        $sheet->getStyle("A1:{$lastCol}1")->getFont()->setBold(true);
        $sheet->getStyle("A2:{$lastCol}2")->getFont()->setItalic(true);
        $sheet->getStyle("A2:{$lastCol}2")->getFont()->getColor()->setRGB('999999');

        return [];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 22,
            'B' => 16,
            'C' => 20,
            'D' => 16,
            'E' => 20,
            'F' => 20,
            'G' => 14,
            'H' => 18,
            'I' => 16,
            'J' => 14,
            'K' => 20,
            'L' => 25,
            'M' => 18,
        ];
    }
}
