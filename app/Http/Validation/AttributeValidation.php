<?php

namespace App\Http\Validation;

class AttributeValidation
{
    public static function rulesForCreate()
    {
        return [
            'name' => 'required',
            'status_ownership' => [
                'required',
                'in:BUJP,PNP',
            ],
            'unit' => [
                'required',
                'in:Unit,Lembar,Jumlah,Orang,Titik,Meter',
            ],
            'standard_contract' => 'required',
        ];
    }


    public static function rulesForUpdate()
    {
        return [
            'name' => 'required',
            'status_ownership' => [
                'required',
                'in:BUJP,PNP',
            ],
            'unit' => [
                'required',
                'in:Unit,Lembar,Jumlah,Orang,Titik,Meter',
            ],
            'standard_contract' => 'required',
        ];
    }

    public static function messages()
    {
        return [
            'name.required' => 'Nama Atribut harus diisi!',
            'status_ownership.required' => 'Status Kepemilikan harus diisi!',
            'status_ownership.in' => 'Status Kepemilikan harus berupa BUJP atau PNP!',
            'unit.required' => 'Satuan harus diisi!',
            'unit.in' => 'Satuan harus berupa Unit, Lembar, Jumlah, Orang, Titik, atau Meter!',
            'standard_contract.required' => 'Jumlah Standar Kontrak harus diisi!',
        ];
    }
}
