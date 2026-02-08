<?php

namespace App\Services\Auth;

use App\Models\PasswordHistory;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ForgotPasswordService
{
    /**
     * Send password reset email.
     */
    public function sendResetLink(string $email): void
    {
        $token = Str::random(64);

        DB::table('password_resets')->updateOrInsert(
            ['email' => $email],
            [
                'token'      => Hash::make($token),
                'created_at' => now(),
            ]
        );

        Mail::send('auth.email-reset-password', [
            'token' => $token,
            'email' => $email,
        ], function ($message) use ($email) {
            $message->to($email)
                ->subject('Reset Password');
        });
    }

    /**
     * Reset user password.
     */
    public function resetPassword(
        string $email,
        string $password,
        string $token
    ): bool {
        DB::beginTransaction();
        $record = DB::table('password_resets')
            ->where('email', $email)
            ->first();

        if (!$record || !Hash::check($token, $record->token)) {
            DB::rollBack();
            return false;
        }

        if (now()->diffInMinutes($record->created_at) > 10) {
            DB::table('password_resets')->where('email', $email)->delete();
            DB::rollBack();
            return false;
        }

         $admin = User::where('email', $email)->first();
        $admin->update([
            'password' => Hash::make($password)
        ]);

        $admin = User::where('email', $email)->first();
        PasswordHistory::create([
            'user_id' => $admin->id,
            'password_hash' => $password,
        ]);

        DB::table('password_resets')->where('email', $email)->delete();
        DB::commit();

        return true;
    }
}
