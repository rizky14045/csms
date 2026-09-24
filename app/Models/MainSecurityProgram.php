<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MainSecurityProgram extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = ['id'];

    const MONTHS = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    const WEEKS = 4;

    /** Sel jadwal [[bulan 1-12, minggu 1-5], ...] dari baris (model/array); data lama tanpa schedule diturunkan dari rentang start-end. */
    public static function cellsFor($row): array
    {
        $get = fn($k) => is_array($row) ? ($row[$k] ?? null) : ($row->$k ?? null);

        $raw = $get('schedule');
        if ($raw) {
            $decoded = is_array($raw) ? $raw : json_decode($raw, true);
            if (is_array($decoded) && count($decoded) > 0) {
                return self::normalizeCells($decoded);
            }
        }

        return self::cellsFromRange($get('start_month'), $get('start_week'), $get('end_month'), $get('end_week'));
    }

    public static function cellsFromRange($startMonth, $startWeek, $endMonth, $endWeek): array
    {
        $s = array_search($startMonth, self::MONTHS);
        $e = array_search($endMonth, self::MONTHS);
        if ($s === false || $e === false || $e < $s) {
            return [];
        }

        $startWeek = max(1, min(self::WEEKS, (int) $startWeek));
        $endWeek = max(1, min(self::WEEKS, (int) $endWeek));
        $cells = [];

        for ($m = $s; $m <= $e; $m++) {
            for ($w = 1; $w <= self::WEEKS; $w++) {
                if ($m == $s && $w < $startWeek) {
                    continue;
                }
                if ($m == $e && $w > $endWeek) {
                    continue;
                }
                $cells[] = [$m + 1, $w];
            }
        }

        return $cells;
    }

    /** Buang sel tidak valid & duplikat, urutkan kronologis. */
    public static function normalizeCells(array $cells): array
    {
        $unique = [];
        foreach ($cells as $cell) {
            if (!is_array($cell) || count($cell) < 2) {
                continue;
            }
            $m = (int) $cell[0];
            $w = (int) $cell[1];
            if ($m >= 1 && $m <= 12 && $w >= 1 && $w <= self::WEEKS) {
                $unique[$m * 10 + $w] = [$m, $w];
            }
        }
        ksort($unique);

        return array_values($unique);
    }

    /** [start_month, start_week, end_month, end_week] dari sel jadwal (tetap dipakai laporan bulanan & export lama). */
    public static function rangeFromCells(array $cells): array
    {
        $cells = self::normalizeCells($cells);
        $first = $cells[0];
        $last = $cells[count($cells) - 1];

        return [
            'start_month' => self::MONTHS[$first[0] - 1],
            'start_week'  => $first[1],
            'end_month'   => self::MONTHS[$last[0] - 1],
            'end_week'    => $last[1],
        ];
    }
}
