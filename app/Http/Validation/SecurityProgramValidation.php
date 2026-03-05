<?php

namespace App\Http\Validation;

class SecurityProgramValidation
{
    public static function rulesForCreate()
    {
        return [
            'program_name' => 'required',
            'description' => 'required',
            'year' => 'required|integer|min:1900|max:' . date('Y'),
        ];
    }

    public static function rulesForUpdate()
    {
            return [
                'program_name' => 'required',
                'description' => 'required',
                'year' => 'required|integer|min:1900|max:' . date('Y'),
        ];
    }

    public static function messages()
    {
        $messages = [
            'program_name.required' => 'Nama program harus diisi!',
            'description.required' => 'Deskripsi harus diisi!',
            'year.required' => 'Tahun harus diisi!',
            'year.integer' => 'Tahun harus berupa angka!',
            'year.min' => 'Tahun tidak valid!',
            'year.max' => 'Tahun tidak valid!',
        ];

        return $messages;
    }
}
