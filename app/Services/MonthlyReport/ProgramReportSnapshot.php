<?php

namespace App\Services\MonthlyReport;

use App\Models\MainSecurityProgram;
use App\Models\MonthlyMainSecurityProgram;
use App\Models\MonthlySecurityProgram;
use App\Models\SecurityProgram;

/**
 * Laporan bulanan memegang salinan program (user_id null, source_id = master) supaya perubahan
 * atau penghapusan master tidak mengubah laporan yang sudah dibuat. Laporan lama yang masih menunjuk
 * langsung ke master dijadikan salinan (dengan nilai LAMA master) tepat sebelum master berubah/dihapus.
 */
class ProgramReportSnapshot
{
    protected static function copyAttributes(array $attrs, $sourceId): array
    {
        unset($attrs['id'], $attrs['deleted_at'], $attrs['deleted_by'], $attrs['updated_by']);
        $attrs['user_id'] = null;
        $attrs['source_id'] = $sourceId;
        $attrs['created_at'] = now();
        $attrs['updated_at'] = now();

        return $attrs;
    }

    public static function copyProgram(array $attrs, $sourceId): SecurityProgram
    {
        $copy = new SecurityProgram();
        $copy->forceFill(self::copyAttributes($attrs, $sourceId))->save();

        return $copy;
    }

    public static function copyMain(array $attrs, $sourceId, $programId): MainSecurityProgram
    {
        $attrs = self::copyAttributes($attrs, $sourceId);
        $attrs['program_id'] = $programId;

        $copy = new MainSecurityProgram();
        $copy->forceFill($attrs)->save();

        return $copy;
    }

    /** Baris laporan yang masih menunjuk langsung ke master program -> jadikan salinan (nilai lama). */
    public static function detachProgramRows(SecurityProgram $master, array $originalAttrs): void
    {
        MonthlySecurityProgram::where('program_id', $master->id)->get()->each(function ($row) use ($master, $originalAttrs) {
            $copy = self::copyProgram($originalAttrs, $master->id);
            $row->update(['program_id' => $copy->id]);
        });
    }

    /** Baris laporan yang masih menunjuk langsung ke master detail program -> jadikan salinan (nilai lama). */
    public static function detachMainRows(MainSecurityProgram $master, array $originalAttrs): void
    {
        MonthlyMainSecurityProgram::where('main_program_id', $master->id)->get()->each(function ($row) use ($master, $originalAttrs) {
            $parent = MonthlySecurityProgram::find($row->monthly_program_id);
            $copy = self::copyMain($originalAttrs, $master->id, $parent->program_id ?? $originalAttrs['program_id'] ?? null);
            $row->update(['main_program_id' => $copy->id]);
        });
    }
}
