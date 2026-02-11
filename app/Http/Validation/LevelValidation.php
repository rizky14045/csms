<?php

namespace App\Http\Validation;

class LevelValidation
{
    public static function rulesForCreate()
    {
        return [
            'level' => 'required',
            'description' => 'required',
        ];
    }


    public static function rulesForUpdate()
    {
        return [
            'level' => 'required',
            'description' => 'required',
        ];
    }

    public static function messages()
    {
        return [
            'level.required' => 'Level harus diisi!',
            'description.required' => 'Uraian harus diisi!',
        ];
    }
}
