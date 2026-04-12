<?php

namespace App\Http\Validation;

class AssesmentValidation
{
    public static function rulesForCreate()
    {
        return [
            'year' => ['required', 'integer', 'digits:4'],
            'triwulan' => ['required', 'integer', 'between:1,4'],
        ];
    }

    public static function rulesForUpdate()
    {
        return [
            'year' => ['required', 'integer', 'digits:4'],
            'triwulan' => ['required', 'integer', 'between:1,4'],
        ];
    }

    public static function rulesForUpdateQuestion($questionId)
    {
        return [
            'level_'.$questionId => 'required',
                'attachment_file_'.$questionId => 'mimes:pdf|max:2048',
        ];
    }

    public static function rulesForUpdateQuestionUnit($questionId)
    {
        return [
            'evaluation_unit_'.$questionId => 'required',
        ];
    }
    
    public static function messages($questionId = null)
    {
        $messages = [
            'year.required' => 'Tahun wajib diisi!',
            'year.integer' => 'Tahun harus berupa angka!',
            'year.digits:4' => 'Format tahun tidak valid!',

            'triwulan.required' => 'Triwulan wajib diisi!',
            'triwulan.integer' => 'Triwulan harus berupa angka!',
            'triwulan.between' => 'Triwulan hanya boleh antara 1 sampai 4!',
        ];

        if ($questionId) {
            $messages['level_'.$questionId.'.required'] = 'Level wajib diisi!';
            $messages['attachment_file_'.$questionId.'.mimes'] = 'File harus berupa PDF!';
            $messages['attachment_file_'.$questionId.'.max'] = 'Ukuran file maksimal 2MB!';
            $messages['evaluation_unit_'.$questionId.'.required'] = 'Level evaluasi unit wajib diisi!';
        }

        return $messages;
    }
}
