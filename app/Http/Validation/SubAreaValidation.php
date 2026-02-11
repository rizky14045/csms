<?php

namespace App\Http\Validation;

class SubAreaValidation
{
    public static function rulesForCreate()
    {
        return [
            'name' => 'required',
            'description' => 'required',
            'reference' => 'required',
        ];
    }


    public static function rulesForUpdate()
    {
        return [
            'name' => 'required',
            'description' => 'required',
            'reference' => 'required',
        ];
    }

    public static function messages()
    {
        return [
            'name.required' => 'Nama harus diisi!',
            'description.required' => 'Uraian harus diisi!',
            'reference.required' => 'Referensi harus diisi!',
        ];
    }
}
