<?php

namespace App\Http\Validation;

class AuditSMPValidation
{
    public static function rulesForCreate()
    {
        return [
            'name'  => 'required',
            'bobot' => 'required|numeric|min:1|max:100',
        ];
    }

    public static function rulesForUpdate()
    {
        return [
            'name'  => 'required',
            'bobot' => 'required|numeric|min:1|max:100',
        ];
    }

    public static function messages()
    {
        return [
            'name.required'  => 'Nama harus diisi!',
            'bobot.required' => 'Bobot harus diisi!',
            'bobot.numeric'  => 'Bobot harus berupa angka!',
            'bobot.min'      => 'Bobot minimal 1%!',
            'bobot.max'      => 'Bobot tidak boleh lebih dari 100%!',
            'type.required'  => 'Tipe harus diisi!',
            'type.in'        => 'Tipe tidak sesuai!',
        ];
    }
}
