<?php

namespace App\Http\Validation;

use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rule;

class UserValidation
{
    public static function rulesForCreate($isLdap = false)
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'string',
                'max:255',
                Rule::unique('users', 'email')
                    ->whereNull('deleted_at'),
            ],

            'password' => [
                $isLdap ? 'nullable' : 'required',
                'confirmed',
                Password::min(12)
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
            ],

            'role' => [
                'required',
                'integer',
                'exists:roles,id',
            ],

            'is_ldap' => [
                'nullable',
                'boolean',
            ],
        ];
    }

    public static function rulesForUpdate($userId)
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'string',
                'max:255',
                Rule::unique('users', 'email')
                    ->whereNull('deleted_at')
                    ->ignore($userId),
            ],

            'password' => [
                'nullable',
                'confirmed',
                Password::min(12)
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
            ],

            'role' => [
                'required',
                'integer',
                'exists:roles,id',
            ],

            'is_ldap' => [
                'nullable',
                'boolean',
            ],
        ];
    }

    public static function messages()
    {
        return [
            'name.required' => 'Nama wajib diisi.',
            'name.string'   => 'Nama harus berupa teks.',
            'name.max'      => 'Nama maksimal 255 karakter.',

            'email.required' => 'Email/Username wajib diisi.',
            'email.unique'   => 'Email/Username sudah digunakan.',

            'password.required' => 'Password wajib diisi.',
            'password.confirmed' => 'Konfirmasi password tidak sesuai.',
            'password.min' => 'Password minimal :min karakter.',
            'password.mixedCase' => 'Password harus mengandung huruf besar dan kecil.',
            'password.numbers' => 'Password harus mengandung angka.',
            'password.symbols' => 'Password harus mengandung simbol.',

            'role.required' => 'Role wajib dipilih.',
            'role.integer'  => 'Role tidak valid.',
            'role.exists'   => 'Role yang dipilih tidak ditemukan.',
        ];
    }
}
