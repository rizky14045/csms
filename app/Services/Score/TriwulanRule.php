<?php

namespace App\Services\Score;

class TriwulanRule
{
    public static function label($triwulan, $semester = null): string
    {
        if ($triwulan) {
            return 'Triwulan ' . $triwulan;
        }

        return $semester ? 'Semester ' . $semester . ' (lama)' : '-';
    }

    /**
     * Validasi pembuatan periode triwulan untuk satu unit.
     * $modelClass: Marturity::class atau Kpi::class. Mengembalikan pesan error atau null jika valid.
     */
    public static function validate(string $modelClass, $unitId, int $year, int $triwulan): ?string
    {
        if ($triwulan < 1 || $triwulan > 4) {
            return 'Triwulan hanya boleh antara 1 sampai 4!';
        }

        $currentYear = now()->year;
        $currentQuarter = (int) ceil(now()->month / 3);

        if ($year > $currentYear || ($year == $currentYear && $triwulan > $currentQuarter)) {
            return "Belum waktunya mengisi Triwulan {$triwulan} tahun {$year}!";
        }

        $base = $modelClass::where('unit_id', $unitId)->where('year', $year);

        if ((clone $base)->where('triwulan', $triwulan)->exists()) {
            return "Triwulan {$triwulan} tahun {$year} sudah diisi!";
        }

        if ($triwulan > 1 && !(clone $base)->where('triwulan', $triwulan - 1)->exists()) {
            $prev = $triwulan - 1;

            return "Urutan salah: Triwulan {$prev} tahun {$year} harus diisi terlebih dahulu sebelum Triwulan {$triwulan}!";
        }

        return null;
    }
}
