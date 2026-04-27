<?php

namespace App\Http\Validation;

use Illuminate\Validation\Rule;

class FasumTypeValidation
{
    public static function rulesForCreate()
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'color_code' => [
                'required',
                'string',
                'max:255',
            ],
        ];
    }

    /**
     * Validation rules for update role
     */
    public static function rulesForUpdate()
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'color_code' => [
                'required',
                'string',
                'max:255',
            ],
        ];
    }

    public static function messages()
    {
        return [
            'name.required' => 'Nama unit wajib diisi.',
            'color_code.required' => 'Kode warna unit wajib diisi.',
        ];
    }
}
