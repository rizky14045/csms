<?php

namespace App\Http\Validation;

class ResponsiblePersonValidation
{
    public static function rulesForCreate()
    {
        return [
            'name' => 'required',
            'position' => 'required',
            'work_unit' => 'required',
        ];
    }

    public static function rulesForUpdate()
    {
            return [
                'name' => 'required',
                'position' => 'required',
                'work_unit' => 'required',
        ];
    }

    public static function messages($noteId = null)
    {
        $messages = [
            'name.required' => 'Nama harus diisi!',
            'position.required' => 'Jabatan harus diisi!',
            'work_unit.required' => 'Unit kerja harus diisi!',
        ];

        return $messages;
    }
}
