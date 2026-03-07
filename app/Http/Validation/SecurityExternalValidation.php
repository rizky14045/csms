<?php

namespace App\Http\Validation;

class SecurityExternalValidation
{
    public static function rulesForCreate()
    {
        return [
            'name' => 'required',
            'instansi' => 'required',
            'regional_unit' => 'required',
            'warrant_number' => 'required',
            'note' => 'required|in:Polri,TNI',
        ];
    }

    public static function rulesForUpdate()
    {
            return [
                'name' => 'required',
                'instansi' => 'required',
                'regional_unit' => 'required',
                'warrant_number' => 'required',
                'note' => 'required|in:Polri,TNI',
        ];
    }

    public static function messages($noteId = null)
    {
        $messages = [
            'name.required' => 'Nama harus diisi!',
            'instansi.required' => 'Instansi harus diisi!',
            'regional_unit.required' => 'Satuan wilayah harus diisi!',
            'warrant_number.required' => 'Nomor surat perintah harus diisi!',
            'note.required' => 'Keterangan harus diisi!',
            'note.in' => 'Keterangan tidak valid!',
        ];

        return $messages;
    }
}
