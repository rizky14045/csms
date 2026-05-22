<?php

namespace App\Http\Validation;

class ResponsiblePersonValidation
{
    private static function checklistRules(): array
    {
        return [
            'training_smp'           => 'nullable|in:Ya,Tidak',
            'auditor_smp'            => 'nullable|in:Ya,Tidak',
            'main'                   => 'nullable|in:Ya,Tidak',
            'investigation'          => 'nullable|in:Ya,Tidak',
            'mansrisk'               => 'nullable|in:Ya,Tidak',
            'stackholder_management' => 'nullable|in:Ya,Tidak',
        ];
    }

    public static function rulesForCreate()
    {
        return array_merge([
            'name'      => 'required',
            'position'  => 'required',
            'work_unit' => 'required',
        ], self::checklistRules());
    }

    public static function rulesForUpdate()
    {
        return array_merge([
            'name'      => 'required',
            'position'  => 'required',
            'work_unit' => 'required',
        ], self::checklistRules());
    }

    public static function messages($noteId = null)
    {
        return [
            'name.required'                  => 'Nama harus diisi!',
            'position.required'              => 'Jabatan harus diisi!',
            'work_unit.required'             => 'Unit kerja harus diisi!',
            'training_smp.in'               => 'Pelatihan SMP harus Ya atau Tidak!',
            'auditor_smp.in'                => 'Auditor SMP harus Ya atau Tidak!',
            'main.in'                        => 'Utama harus Ya atau Tidak!',
            'investigation.in'               => 'Investigasi harus Ya atau Tidak!',
            'mansrisk.in'                    => 'Mansrisk harus Ya atau Tidak!',
            'stackholder_management.in'      => 'Stakeholder Management harus Ya atau Tidak!',
        ];
    }
}
