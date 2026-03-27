<?php

namespace App\Http\Validation;

class ElementAuditSMPValidation
{
    public static function rulesForCreate()
    {
        return [
            'name' => 'required',
            'type' => 'required|in:kriteria,pernyataan',
        ];
    }

    public static function rulesForUpdate()
    {
        return [
            'name' => 'required',
        ];
    }

    public static function messages()
    {
        return [
            'name.required' => 'Nama harus diisi!',
            'type.required' => 'Tipe harus diisi!',
            'type.in' => 'Tipe tidak sesuai!'
        ];
    }
}
