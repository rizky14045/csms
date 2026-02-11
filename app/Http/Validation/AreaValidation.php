<?php

namespace App\Http\Validation;

class AreaValidation
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
        ];
    }
}
