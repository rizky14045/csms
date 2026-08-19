<?php

namespace App\Http\Validation;

use Illuminate\Validation\Rule;

class EmailSettingValidation
{
    /**
     * Validation rules for update email setting
     */
    public static function rulesForUpdate()
    {
        return [
            'provider' => [
                'required',
                'string',
                'max:255',
            ],
            'host' => [
                'required',
                'string',
                'max:255',
            ],
            'port' => [
                'required',
                'integer',
            ],
            'username' => [
                'required',
                'string',
                'max:255',
            ],
            'password' => [
                'required',
                'string',
                'max:255',
            ],
            'from' => [
                'required',
                'string',
                'max:255',
            ],
            'alias' => [
                'required',
                'string',
                'max:255',
            ],
            'timeout' => [
                'required',
                'integer',
            ],
            'security' => [
                'required',
                'string',
                'in:ssl,tls,none',
            ],
            'email_test' => [
                'required',
                'string',
                'max:255',
            ],
        ];
    }

    public static function messages()
    {
        return [
            'provider.string' => 'Provider harus berupa string.',
            'provider.max' => 'Provider tidak boleh lebih dari 255 karakter.',
            'host.string' => 'Host harus berupa string.',
            'host.max' => 'Host tidak boleh lebih dari 255 karakter.',
            'port.integer' => 'Port harus berupa angka.',
            'username.string' => 'Username harus berupa string.',
            'username.max' => 'Username tidak boleh lebih dari 255 karakter.',
            'password.string' => 'Password harus berupa string.',
            'password.max' => 'Password tidak boleh lebih dari 255 karakter.',
            'from.string' => 'From harus berupa string.',
            'from.max' => 'From tidak boleh lebih dari 255 karakter.',
            'alias.string' => 'Alias harus berupa string.',
            'alias.max' => 'Alias tidak boleh lebih dari 255 karakter.',
            'timeout.integer' => 'Timeout harus berupa angka.',
            'security.string' => 'Security harus berupa string.',
            'email_test.string' => 'Email test harus berupa string.',
            'email_test.max' => 'Email test tidak boleh lebih dari 255 karakter.',
            'security.in' => 'Security harus bernilai SSL, TLS, atau None.',
            'provider.required' => 'Provider wajib diisi.',
            'host.required' => 'Host wajib diisi.',
            'port.required' => 'Port wajib diisi.',
            'username.required' => 'Username wajib diisi.',
            'password.required' => 'Password wajib diisi.',
            'from.required' => 'From wajib diisi.',
            'alias.required' => 'Alias wajib diisi.',
            'timeout.required' => 'Timeout wajib diisi.',
            'security.required' => 'Security wajib diisi.',
            'email_test.required' => 'Email test wajib diisi.',
        ];
    }
}
