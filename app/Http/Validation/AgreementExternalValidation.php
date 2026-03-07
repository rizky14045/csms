<?php

namespace App\Http\Validation;

class AgreementExternalValidation
{
    public static function rulesForCreate()
    {
        return [
            'regional_unit' => 'required',
            'instansi' => 'required',
            'name' => 'required',
            'pkt_number' => 'required',
            'pkt_title' => 'required',
            'expired_date' => 'required|date',
        ];
    }

    public static function rulesForUpdate()
    {
            return [
                'regional_unit' => 'required',
                'instansi' => 'required',
                'name' => 'required',
                'pkt_number' => 'required',
                'pkt_title' => 'required',
                'expired_date' => 'required|date',
            ];
    }

    public static function messages($noteId = null)
    {
        $messages = [
            'regional_unit.required' => 'Satuan wilayah harus diisi!',
            'instansi.required' => 'Instansi harus diisi!',
            'name.required' => 'Nama harus diisi!',
            'pkt_number.required' => 'Nomor PKT harus diisi!',
            'pkt_title.required' => 'Judul PKT harus diisi!',
            'expired_date.required' => 'Tanggal masa berlaku harus diisi!',
            'expired_date.date' => 'Tanggal masa berlaku harus berupa tanggal yang valid!',
        ];

        return $messages;
    }
}
