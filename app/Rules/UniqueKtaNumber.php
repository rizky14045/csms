<?php

namespace App\Rules;

use App\Models\Security;
use Illuminate\Contracts\Validation\Rule;

/**
 * Nomor registrasi KTA harus unik di antara master data satpam (semua unit, yang belum dihapus).
 * Salinan di laporan bulanan (user_id null) tidak dihitung. Jika nomor tidak diubah pada saat
 * edit, tidak diperiksa lagi agar data lama yang sudah kembar tidak mengunci edit field lain.
 */
class UniqueKtaNumber implements Rule
{
    protected $ignore;

    public function __construct(?Security $ignore = null)
    {
        $this->ignore = $ignore;
    }

    public static function normalize($value): string
    {
        return mb_strtolower(trim((string) $value));
    }

    public static function exists($value, $ignoreId = null): bool
    {
        $number = self::normalize($value);
        if ($number === '') {
            return false;
        }

        return Security::whereNotNull('user_id')
            ->whereRaw('LOWER(TRIM(registration_number)) = ?', [$number])
            ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
            ->exists();
    }

    public function passes($attribute, $value)
    {
        if ($this->ignore && self::normalize($this->ignore->registration_number) === self::normalize($value)) {
            return true;
        }

        return !self::exists($value, $this->ignore->id ?? null);
    }

    public function message()
    {
        return 'Nomor registrasi KTA sudah terdaftar pada satuan pengamanan lain!';
    }
}
