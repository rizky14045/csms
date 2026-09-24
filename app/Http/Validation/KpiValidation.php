<?php

namespace App\Http\Validation;

class KpiValidation
{
    public static function rulesForCreate()
    {
        return [
            'year' => 'required|integer',
            'triwulan' => 'required|integer|between:1,4',
        ];
    }


    public static function rulesForUpdate()
    {
        return [
            'year' => 'required|integer',
            'triwulan' => 'required|integer|between:1,4',
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
            'triwulan.required' => 'Triwulan harus diisi!',
            'triwulan.between' => 'Triwulan hanya boleh antara 1 sampai 4!',
        ];

        if ($noteId) {
            $messages['attachment_file_'.$noteId.'.required'] = 'File lampiran wajib diisi!';
            $messages['attachment_file_'.$noteId.'.mimes'] = 'File lampiran harus beresktensi .pdf!';
        }

        return $messages;
    }
}
