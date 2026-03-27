<?php

namespace App\Http\Validation;

class EvidenceAuditSMPValidation
{
    public static function rulesForCreate()
    {
        return [
            'name' => 'required',
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
