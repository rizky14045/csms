<?php

namespace App\Http\Validation;

class SecurityValidation
{
    public static function rulesForCreate()
    {
        return [
            'name' => 'required|string',
            'gender' => [
                'required',
                'in:Pria,Wanita',
            ],
            'unit_work' => 'required|string',
            'nid' => 'required',
            'registration_number' => 'required',
            'expired_card_date' => 'required|date',
            'position' => [
                'required',
                'in:Komandan,Anggota,Chief',
            ],
            'birth_place' => 'required|string',
            'birth_date' => 'required|date|before:today',
            'qualification' => [
                'required',
                'in:Pratama,Madya,Utama',
            ],
            'last_education' => 'required|string',
            'note' => 'nullable|string',
            'kta_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ];
    }

    public static function rulesForUpdate()
    {
        return [
            'name' => 'required|string',
            'gender' => [
                'required',
                'in:Pria,Wanita',
            ],
            'unit_work' => 'required|string',
            'nid' => 'required',
            'registration_number' => 'required',
            'expired_card_date' => 'required|date',
            'position' => [
                'required',
                'in:Komandan,Anggota,Chief',
            ],
            'birth_place' => 'required|string',
            'birth_date' => 'required|date|before:today',
            'qualification' => [
                'required',
                'in:Pratama,Madya,Utama',
            ],
            'last_education' => 'required|string',
            'note' => 'nullable|string',
            'kta_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ];
    }

    public static function messages()
    {
        return [
            'name.required' => 'Nama harus diisi!',

            'gender.required' => 'Jenis kelamin harus diisi!',
            'gender.in' => 'Jenis kelamin harus berupa Pria atau Wanita!',

            'unit_work.required' => 'Unit kerja harus diisi!',

            'nid.required' => 'NID harus diisi!',

            'registration_number.required' => 'Nomor registrasi harus diisi!',

            'expired_card_date.required' => 'Tanggal kadaluarsa kartu harus diisi!',
            'expired_card_date.date' => 'Tanggal kadaluarsa harus berupa format tanggal yang valid!',

            'position.required' => 'Jabatan harus diisi!',
            'position.in' => 'Jabatan harus berupa Komandan, Anggota, atau Chief!',

            'birth_place.required' => 'Tempat lahir harus diisi!',

            'birth_date.required' => 'Tanggal lahir harus diisi!',
            'birth_date.date' => 'Tanggal lahir harus berupa format tanggal yang valid!',
            'birth_date.before' => 'Tanggal lahir tidak boleh melebihi hari ini!',

            'qualification.required' => 'Kualifikasi harus diisi!',
            'qualification.in' => 'Kualifikasi harus berupa Pratama, Madya, atau Utama!',

            'last_education.required' => 'Pendidikan terakhir harus diisi!',

            'note.required' => 'Catatan harus diisi!',
            'note.string' => 'Catatan harus berupa teks!',

            'kta_file.required' => 'File KTA wajib diunggah!',
            'kta_file.file' => 'KTA harus berupa file!',
            'kta_file.mimes' => 'Format KTA harus pdf, jpg, jpeg, atau png!',
            'kta_file.max' => 'Ukuran file KTA maksimal 5 MB!',
        ];
    }

}
