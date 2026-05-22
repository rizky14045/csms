<?php

namespace App\Http\Validation;
use Illuminate\Validation\Rule;

class AuditSMPDataValidation
{
    public static function rulesForCreate()
    {
        return [
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
                    $query->whereIn('type', ['user', 'pusat']);
                }),
            ],

            'auditors_ids' => ['required', 'array'],

            'auditors_ids.*' => [
                Rule::exists('users', 'id')->where(function ($query) {
                    $query->whereIn('type', ['user', 'pusat']);
                }),
            ],

            'start_audit' => ['required', 'date'],
            'end_audit'   => ['required', 'date', 'after_or_equal:start_audit'],

            'sk_file' => ['nullable', 'file', 'mimes:pdf', 'max:15360'],
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

    public static function rulesForUpdateEvidence($id){
        return [
            "evidence_file_$id" => ['required', 'file', 'mimes:pdf', 'max:5120'],
        ];
    } 

    public static function rulesForUpdateSelfAudit($id){
        return [
            "pencapaian_nilai_kriteria_self_$id" => ['required', 'numeric', 'between:0,2'],
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

            'start_audit.required'      => 'Tanggal mulai harus diisi.',
            'start_audit.date'          => 'Tanggal mulai harus berupa tanggal yang valid.',
            'end_audit.required'        => 'Tanggal selesai harus diisi.',
            'end_audit.date'            => 'Tanggal selesai harus berupa tanggal yang valid.',
            'end_audit.after_or_equal'  => 'Tanggal selesai harus sama dengan atau setelah tanggal mulai.',

            'sk_file.file'  => 'File SK Audit harus berupa file yang valid.',
            'sk_file.mimes' => 'File SK Audit harus berformat PDF.',
            'sk_file.max'   => 'Ukuran file SK Audit maksimal 15MB.',
        ];

        if ($id) {
            $messages["pencapaian_nilai_kriteria_$id.required"] = 'Pencapaian nilai kriteria harus diisi.';
            $messages["pencapaian_nilai_kriteria_$id.numeric"] = 'Pencapaian nilai kriteria harus berupa angka.';
            $messages["pencapaian_nilai_kriteria_$id.between"] = 'Pencapaian nilai kriteria harus antara 0 dan 2.';

            $messages["pencapaian_nilai_kriteria_self_$id.required"] = 'Pencapaian nilai kriteria harus diisi.';
            $messages["pencapaian_nilai_kriteria_self_$id.numeric"] = 'Pencapaian nilai kriteria harus berupa angka.';
            $messages["pencapaian_nilai_kriteria_self_$id.between"] = 'Pencapaian nilai kriteria harus antara 0 dan 2.';

            $messages["due_date_$id.date"] = 'Tanggal jatuh tempo harus berupa tanggal yang valid.';
            $messages["due_date_$id.after_or_equal"] = 'Tanggal jatuh tempo harus sama dengan atau setelah hari ini.';

            $messages["rekomendasi_$id.string"] = 'Rekomendasi harus berupa teks.';
            $messages["temuan_$id.string"] = 'Temuan harus berupa teks.';

            $messages["evidence_file_$id.required"] = 'File bukti harus diunggah.';
            $messages["evidence_file_$id.file"] = 'File bukti harus berupa file yang valid.';
            $messages["evidence_file_$id.mimes"] = 'File bukti harus berupa file dengan format PDF.';
            $messages["evidence_file_$id.max"] = 'Ukuran file bukti tidak boleh lebih dari 5MB.';

        }

        return $messages;
    }
}
