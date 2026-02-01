<?php

namespace App\Http\Helper;

use App\Models\PasswordHistory;
use Illuminate\Support\Facades\Hash;

class PasswordHelper
{
    /**
     * Return TRUE  -> password boleh dipakai
     * Return FALSE -> password sama dengan password sebelumnya
     */
    public static function isPasswordAllowed(int $userId, string $newPassword, int $limit = 5): bool
    {
        $histories = PasswordHistory::where('user_id', $userId)
            ->latest()
            ->take($limit)
            ->pluck('password_hash');

        foreach ($histories as $oldHash) {
            if (Hash::check($newPassword, $oldHash)) {
                return false;
            }
        }

        return true;
    }
}
