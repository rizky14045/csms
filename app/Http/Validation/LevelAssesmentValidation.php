<?php

namespace App\Http\Validation;

use Illuminate\Validation\Rule;

class LevelAssesmentValidation
{
    public static function rulesForCreate($questionId)
    {
        return [
            'level' => [
                'required',
                'integer',
                'between:1,5',
                Rule::unique('level_assesments', 'level')
                    ->where('question_id', $questionId)
                    ->whereNull('deleted_at'),
            ],
            'level_description' => [
                'required',
                'string',
            ],
        ];
    }

    public static function rulesForUpdate($questionId, $levelId)
    {
        return [
            'level' => [
                'required',
                'integer',
                'between:1,5',
                Rule::unique('level_assesments', 'level')
                    ->where('question_id', $questionId)
                    ->whereNull('deleted_at')
                    ->ignore($levelId),
            ],
            'level_description' => [
                'required',
                'string',
            ],
        ];
    }

    public static function messages()
    {
        return [
            'level.required' => 'Level wajib diisi.',
            'level.integer'  => 'Level harus berupa angka.',
            'level.between'  => 'Level hanya boleh antara 1 sampai 5.',
            'level.unique'   => 'Level ini sudah dipakai pada indikator yang sama.',

            'level_description.required' => 'Deskripsi level wajib diisi.',
            'level_description.string'   => 'Deskripsi level harus berupa teks.',
        ];
    }
}
