<?php

namespace App\Http\Validation;

class MarturityValidation
{
    public static function rulesForCreate()
    {
        return [
            'date' => ['required', 'date'],
            'triwulan' => ['required', 'integer', 'between:1,4'],
        ];
    }

    public static function rulesForUpdate()
    {
        return [
            'date' => ['required', 'date'],
            'triwulan' => ['required', 'integer', 'between:1,4'],
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
            'date.required' => 'Tanggal wajib diisi!',
            'date.date' => 'Format tanggal tidak valid!',

            'triwulan.required' => 'Triwulan wajib diisi!',
            'triwulan.integer' => 'Triwulan harus berupa angka!',
            'triwulan.between' => 'Triwulan hanya boleh antara 1 sampai 4!',
        ];

        if ($noteId) {
            $messages['attachment_file_'.$noteId.'.required'] = 'File lampiran wajib diisi!';
            $messages['attachment_file_'.$noteId.'.mimes'] = 'File lampiran harus beresktensi .pdf!';
        }

        return $messages;
    }
}
