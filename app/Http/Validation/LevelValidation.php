<?php

namespace App\Http\Validation;

class LevelValidation
{
    public static function rulesForCreate()
    {
        return [
            'description'    => 'required',
            'total_evidence' => 'required|integer|min:1',
        ];
    }

    public static function rulesForUpdate()
    {
        return [
            'description'    => 'required',
            'total_evidence' => 'required|integer|min:1',
        ];
    }

    public static function messages()
    {
        return [
            'description.required'    => 'Uraian harus diisi!',
            'total_evidence.required' => 'Total evidence harus diisi!',
            'total_evidence.integer'  => 'Total evidence harus berupa angka!',
            'total_evidence.min'      => 'Total evidence harus lebih dari 0!',
        ];
    }
}
