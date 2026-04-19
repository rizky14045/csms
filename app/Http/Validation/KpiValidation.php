<?php

namespace App\Http\Validation;

class KpiValidation
{
    public static function rulesForCreate()
    {
        return [
            'year' => 'required|integer',
            'semester' => 'required|in:1,2',
        ];
    }


    public static function rulesForUpdate()
    {
        return [
            'year' => 'required|integer',
            'semester' => 'required|in:1,2',
        ];
    }

    public static function rulesForUploadNote($noteId)
    {
        return [
            'attachment_file_'.$noteId => ['required', 'mimes:pdf'],
        ];
    }

    public static function messages($noteId = null)
    {
        $messages = [
            'year.required' => 'Tahun harus diisi!',
            'year.integer' => 'Format tahun tidak valid!',
            'semester.required' => 'Semester harus diisi!',
            'semester.in' => 'Semester harus salah satu dari 1 atau 2!',
        ];

        if ($noteId) {
            $messages['attachment_file_'.$noteId.'.required'] = 'File lampiran wajib diisi!';
            $messages['attachment_file_'.$noteId.'.mimes'] = 'File lampiran harus beresktensi .pdf!';
        }

        return $messages;
    }
}
