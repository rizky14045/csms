<?php

namespace App\Services\Concerns;

use Illuminate\Support\Facades\DB;

trait AllocatesIds
{
    /**
     * Ambil sejumlah id dari sequence tabel sekaligus, agar induk dan anak
     * bisa di-insert massal dengan relasi yang sudah diketahui.
     */
    protected function allocateIds($table, $count)
    {
        if ($count <= 0) {
            return [];
        }

        $rows = DB::select(
            "select nextval(pg_get_serial_sequence('{$table}', 'id')) as id from generate_series(1, {$count})"
        );

        return array_map(fn($r) => (int) $r->id, $rows);
    }
}
