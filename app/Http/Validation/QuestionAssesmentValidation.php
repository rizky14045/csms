<?php

namespace App\Http\Validation;

class QuestionAssesmentValidation
{
    public static function rulesForCreate()
    {
        return [
            'indicator' => 'required',
        ];
    }


    public static function rulesForUpdate()
    {
        return [
            'indicator' => 'required',
        ];
    }

    public static function messages()
    {
        return [
            'indicator.required' => 'Indikator harus diisi!',
        ];
    }
}
