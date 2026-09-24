<?php

namespace App\Services\Unit;

use App\Models\Unit;
use App\Models\User;

/**
 * Cakupan master data untuk grup unit induk + UL.
 * Unit tanpa UL (bukan induk, bukan UL) tetap memakai aturan lama: master data per user.
 */
class UnitScope
{
    public static function isGroup(User $user): bool
    {
        if (!$user->unit_id) {
            return false;
        }

        $unit = Unit::find($user->unit_id);
        if (!$unit) {
            return false;
        }

        return $unit->type === 'UL'
            || Unit::where('parent_unit_id', $unit->id)->where('type', 'UL')->exists();
    }

    public static function isUl(User $user): bool
    {
        return $user->unit_id && Unit::where('id', $user->unit_id)->where('type', 'UL')->exists();
    }

    /** Id unit yang datanya boleh dilihat: UL hanya dirinya, induk = dirinya + semua UL-nya. */
    public static function visibleUnitIds(User $user): array
    {
        if (!$user->unit_id) {
            return [];
        }

        if (self::isUl($user)) {
            return [(int) $user->unit_id];
        }

        return array_merge(
            [(int) $user->unit_id],
            Unit::where('parent_unit_id', $user->unit_id)->where('type', 'UL')->pluck('id')->map(fn($i) => (int) $i)->all()
        );
    }

    /** Unit yang bisa dipilih sebagai tempat kerja/penugasan (induk + UL-nya). Kosong jika bukan grup. */
    public static function assignableUnits(User $user)
    {
        if (!self::isGroup($user)) {
            return collect();
        }

        return Unit::whereIn('id', self::visibleUnitIds($user))->orderByRaw('id = ? desc', [$user->unit_id])->orderBy('name')->get();
    }

    /** Unit tujuan penugasan yang valid; jika tidak valid/kosong jatuh ke unit user sendiri. */
    public static function assignedUnitFor(User $user, $requested = null): ?int
    {
        if ($requested && self::isGroup($user) && in_array((int) $requested, self::visibleUnitIds($user), true)) {
            return (int) $requested;
        }

        return $user->unit_id ? (int) $user->unit_id : null;
    }

    /**
     * Master data dimiliki unit, bukan akun: semua akun di unit yang sama berbagi data.
     * user_id tetap wajib terisi sebagai penanda baris master (baris salinan laporan bulanan user_id-nya null).
     * Baris lama tanpa unit_id dianggap milik unit dari akun pembuatnya.
     */
    protected static function unitMaster($query, array $unitIds)
    {
        return $query->whereNotNull('user_id')->where(function ($w) use ($unitIds) {
            $w->whereIn('unit_id', $unitIds)
                ->orWhere(function ($legacy) use ($unitIds) {
                    $legacy->whereNull('unit_id')
                        ->whereIn('user_id', User::whereIn('unit_id', $unitIds)->select('id'));
                });
        });
    }

    /** Batasi query master data ke unit user (grup = unit + UL-nya); user tanpa unit hanya melihat miliknya. */
    public static function applyMaster($query, User $user)
    {
        if (!$user->unit_id) {
            return $query->where('user_id', $user->id);
        }

        return self::unitMaster($query, self::visibleUnitIds($user));
    }

    public static function canAccess($model, User $user): bool
    {
        if ($model->user_id === null) {
            return false;
        }

        if (!$user->unit_id) {
            return $model->user_id === $user->id;
        }

        if ($model->unit_id !== null) {
            return in_array((int) $model->unit_id, self::visibleUnitIds($user), true);
        }

        // baris lama tanpa unit_id: ikut unit pembuatnya
        $ownerUnit = User::where('id', $model->user_id)->value('unit_id');

        return $ownerUnit !== null && in_array((int) $ownerUnit, self::visibleUnitIds($user), true);
    }

    public static function isGroupUnit($unitId): bool
    {
        if (!$unitId) {
            return false;
        }

        return Unit::where('id', $unitId)->where('type', 'UL')->exists()
            || Unit::where('parent_unit_id', $unitId)->where('type', 'UL')->exists();
    }

    /** Master data yang disalin ke laporan bulanan: master unit laporan itu (bukan hanya milik pembuat laporan). */
    public static function applyReportMaster($query, $report)
    {
        if (!$report->unit_id) {
            return $query->where('user_id', $report->user_id);
        }

        return self::unitMaster($query, [(int) $report->unit_id]);
    }

    /** Master Administrasi: data global buatan admin (tanpa pemilik) + milik unit/user laporan itu sendiri, bukan milik unit lain. */
    public static function applyReportAdministration($query, $report)
    {
        return $query->where(function ($w) use ($report) {
            $w->where(function ($global) {
                $global->whereNull('user_id')->whereNull('source_id');
            })->orWhere(function ($own) use ($report) {
                self::applyReportMaster($own, $report);
            });
        });
    }
}
