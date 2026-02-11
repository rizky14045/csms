<?php

namespace App\Http\Validation;

class NoteValidation
{
    public static function rulesForCreate()
    {
        return [
            'note' => 'required',
        ];
    }


    public static function rulesForUpdate()
    {
        return [
            'note' => 'required',
        ];
    }

    public static function messages()
    {
        return [
            'note.required' => 'Note harus diisi!',
        ];
    }
}
