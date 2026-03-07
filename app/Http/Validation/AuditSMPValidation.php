<?php

namespace App\Http\Validation;

class AuditSMPValidation
{
    public static function rulesForCreate()
    {
        return [
            'name' => 'required',
            'bobot' => 'required',
        ];
    }
    public static function rulesForCreateElement()
    {
        return [
            'name' => 'required',
            'type' => 'required|in:kriteria,eviden',
        ];
    }
    public static function rulesForCreateEvident()
    {
        return [
            'name' => 'required',
            'type' => 'required|in:eviden',
        ];
    }

    public static function rulesForUpdate()
    {
        return [
            'name' => 'required',
            'bobot' => 'required',
        ];
    }

    public static function messages()
    {
        return [
            'name.required' => 'Nama harus diisi!',
            'bobot.required' => 'Bobot harus diisi!',
            'type.required' => 'Tipe harus diisi!',
            'type.in' => 'Tipe tidak sesuai!'
        ];
    }
}
