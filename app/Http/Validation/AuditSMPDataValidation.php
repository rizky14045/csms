<?php

namespace App\Http\Validation;
use Illuminate\Validation\Rule;

class AuditSMPDataValidation
{
    public static function rulesForCreate()
    {
        return [
            'unit_id' => ['required', 'exists:units,id'],

            'auditor_lead_id' => [
                'required',
                Rule::exists('users', 'id')->where(function ($query) {
                    $query->where('type', 'auditor');
                }),
            ],

            'auditors_ids' => ['required', 'array'],

            'auditors_ids.*' => [
                Rule::exists('users', 'id')->where(function ($query) {
                    $query->where('type', 'auditor');
                }),
            ],

            'start_audit' => ['required', 'date'],
            'end_audit' => ['required', 'date', 'after_or_equal:start_audit'],
        ];
    }

    public static function rulesForUpdate()
    {
        return [
            'unit_id' => ['required', 'exists:units,id'],

            'auditor_lead_id' => [
                'required',
                Rule::exists('users', 'id')->where(function ($query) {
                    $query->where('type', 'auditor');
                }),
            ],

            'auditors_ids' => ['required', 'array'],

            'auditors_ids.*' => [
                Rule::exists('users', 'id')->where(function ($query) {
                    $query->where('type', 'auditor');
                }),
            ],

            'start_audit' => ['required', 'date'],
            'end_audit' => ['required', 'date', 'after_or_equal:start_audit'],
        ];
    }

    public static function rulesForUpdateScoreByAuditor($id){
        return [
            // "pencapaian_nilai_kriteria_$id" => ['required', 'numeric', 'between:0,2'],
            "rekomendasi_$id" => ['nullable', 'string'],
            "temuan_$id" => ['nullable', 'string'],
            "due_date_$id" => ['nullable', 'date', 'after_or_equal:today'],
            "pic_$id" => ['nullable', 'string'],
        ];
    }

    public static function rulesForUpdateAchievementByAuditor($id){
        return [
            "pencapaian_nilai_kriteria_$id" => ['required', 'numeric', 'between:0,2'],
        ];
    }

    public static function messages($id = null)
    {
        $messages = [
            'unit_id.required' => 'Unit harus dipilih.',
            'unit_id.exists' => 'Unit yang dipilih tidak valid.',

            'auditor_lead_id.required' => 'Ketua auditor harus dipilih.',
            'auditor_lead_id.exists' => 'Ketua auditor yang dipilih tidak valid.',

            'auditors_ids.required' => 'Anggota auditor harus dipilih.',
            'auditors_ids.array' => 'Format anggota auditor tidak valid.',
            'auditors_ids.*.exists' => 'Anggota auditor yang dipilih tidak valid.',

            'start_audit.required' => 'Tanggal mulai harus diisi.',
            'start_audit.date' => 'Tanggal mulai harus berupa tanggal yang valid.',
            'end_audit.required' => 'Tanggal selesai harus diisi.',
            'end_audit.date' => 'Tanggal selesai harus berupa tanggal yang valid.',
            'end_audit.after_or_equal' => 'Tanggal selesai harus sama dengan atau setelah tanggal mulai.',
        ];

        if ($id) {
            $messages["pencapaian_nilai_kriteria_$id.required"] = 'Pencapaian nilai kriteria harus diisi.';
            $messages["pencapaian_nilai_kriteria_$id.numeric"] = 'Pencapaian nilai kriteria harus berupa angka.';
            $messages["pencapaian_nilai_kriteria_$id.between"] = 'Pencapaian nilai kriteria harus antara 0 dan 2.';

            $messages["due_date_$id.date"] = 'Tanggal jatuh tempo harus berupa tanggal yang valid.';
            $messages["due_date_$id.after_or_equal"] = 'Tanggal jatuh tempo harus sama dengan atau setelah hari ini.';

            $messages["rekomendasi_$id.string"] = 'Rekomendasi harus berupa teks.';
            $messages["temuan_$id.string"] = 'Temuan harus berupa teks.';

        }

        return $messages;
    }
}
