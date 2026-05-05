<?php

namespace App\Http\Validation;

class MarturityValidation
{
    public static function rulesForCreate()
    {
        return [
            'year' => ['required', 'integer'],
            'semester' => ['required', 'integer', 'between:1,2'],
        ];
    }

    public static function rulesForUpdate()
    {
        return [
            'year' => ['required', 'integer'],
            'semester' => ['required', 'integer', 'between:1,2'],
        ];
    }

    public static function rulesForUploadNote($noteId)
    {
        return [
            'attachment_file_'.$noteId => ['required', 'mimes:pdf', 'max:2048'],
        ];
    }

    public static function messages($noteId = null)
    {
        $messages = [
            'year.required' => 'Tahun wajib diisi!',
            'year.integer' => 'Tahun harus berupa angka!',

            'semester.required' => 'semester wajib diisi!',
            'semester.integer' => 'semester harus berupa angka!',
            'semester.between' => 'semester hanya boleh antara 1 sampai 2!',
        ];

        if ($noteId) {
            $messages['attachment_file_'.$noteId.'.required'] = 'File lampiran wajib diisi!';
            $messages['attachment_file_'.$noteId.'.mimes'] = 'File lampiran harus beresktensi .pdf!';
            $messages['attachment_file_'.$noteId.'.max'] = 'File lampiran tidak boleh lebih dari 2MB!';
        }

        return $messages;
    }
}
