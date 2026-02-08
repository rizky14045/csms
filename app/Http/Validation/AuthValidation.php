<?php

namespace App\Http\Validation;
use Illuminate\Validation\Rules\Password;

class AuthValidation
{
    public static function rulesForLogin()
    {
        return [
            'email' => 'required|email',
            'password' => 'required',
        ];
    }

    public static function rulesForForgotPassword()
    {
        return [
            'email' => 'required|email|exists:users,email',
        ];
    }

    public static function rulesForResetPassword()
    {
        return [
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => [
                'required',
                'confirmed',
                Password::min(12)
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
            ],
        ];
    }

    public static function messages(){
        return [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Email harus berformat email yang valid.',
            'email.exists'   => 'Email not found.',
            'password.required' => 'Password wajib diisi.',
            'password.confirmed' => 'Konfirmasi password tidak sesuai.',
            'password.min' => 'Password minimal :min karakter.',
            'password.mixedCase' => 'Password harus mengandung huruf besar dan kecil.',
            'password.numbers' => 'Password harus mengandung angka.',
            'password.symbols' => 'Password harus mengandung simbol.',
        ];
    }
}
