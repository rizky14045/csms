<?php

namespace App\Http\Validation;

class SecurepediaValidation
{
    public static function rulesForCreate()
    {
        return [
            'title' => 'required',
            'type' => [
                'required',
                'in:Internal,External',
            ],
            'file' => 'nullable|file|mimes:pdf|max:5120'
        ];
    }


    public static function rulesForUpdate()
    {
        return [
            'title' => 'required',
            'type' => [
                'required',
                'in:Internal,External',
            ],
            'file' => 'nullable|file|mimes:pdf|max:5120'
        ];
    }

    public static function messages()
    {
        return [
            'title.required' => 'Judul harus diisi.',
            'type.required' => 'Tipe harus dipilih.',
            'type.in' => 'Tipe tidak valid. Pilih Internal atau External.',
            'file.file' => 'File harus berupa file yang valid.',
            'file.mimes' => 'File harus berformat PDF.',
            'file.max' => 'Ukuran file maksimal 5MB.',
        ];
    }
}
