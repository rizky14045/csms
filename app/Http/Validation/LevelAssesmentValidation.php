<?php

namespace App\Http\Validation;

class LevelAssesmentValidation
{
    public static function rulesForCreate()
    {
        return [
            'level' => [
                'required',
                'integer',
                'between:1,5',
            ],
            'level_description' => [
                'required',
                'string',
            ],
        ];
    }

    public static function rulesForUpdate()
    {
        return [
            'level' => [
                'required',
                'integer',
                'between:1,5',
            ],
            'level_description' => [
                'required',
                'string',
            ],
        ];
    }

    public static function messages()
    {
        return [
            'level.required' => 'Level wajib diisi.',
            'level.integer'  => 'Level harus berupa angka.',
            'level.between'  => 'Level hanya boleh antara 1 sampai 5.',

            'level_description.required' => 'Deskripsi level wajib diisi.',
            'level_description.string'   => 'Deskripsi level harus berupa teks.',
        ];
    }
}
