<?php

namespace App\Http\Validation;

class KpiValidation
{
    public static function rulesForCreate()
    {
        return [
            'date' => 'required|date',
            'triwulan' => 'required|in:1,2,3,4',
        ];
    }


    public static function rulesForUpdate()
    {
        return [
            'date' => 'required|date',
            'triwulan' => 'required|in:1,2,3,4',
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
            'date.required' => 'Tanggal harus diisi!',
            'date.date' => 'Format tanggal tidak valid!',
            'triwulan.required' => 'Triwulan harus diisi!',
            'triwulan.in' => 'Triwulan harus salah satu dari 1, 2, 3, atau 4!',
        ];

        if ($noteId) {
            $messages['attachment_file_'.$noteId.'.required'] = 'File lampiran wajib diisi!';
            $messages['attachment_file_'.$noteId.'.mimes'] = 'File lampiran harus beresktensi .pdf!';
        }

        return $messages;
    }
}
