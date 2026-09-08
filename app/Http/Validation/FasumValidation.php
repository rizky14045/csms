<?php

namespace App\Http\Validation;

use Illuminate\Validation\Rule;

class FasumValidation
{
    public static function rulesForCreate()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'contact' => ['nullable', 'string', 'max:255'],
            'type_id' => ['required', 'integer', 'exists:fasum_types,id'],
            'province_id' => ['required', 'integer', 'exists:provinces,id'],
            'city_id' => ['required', 'integer', 'exists:cities,id'],
            'latitude' => ['required', 'numeric', 'between:-11,6.5'],
            'longitude' => ['required', 'numeric', 'between:95,141'],
        ];
    }

    public static function rulesForUpdate()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'contact' => ['nullable', 'string', 'max:255'],
            'type_id' => ['required', 'integer', 'exists:fasum_types,id'],
            'province_id' => ['required', 'integer', 'exists:provinces,id'],
            'city_id' => ['required', 'integer', 'exists:cities,id'],
            'latitude' => ['required', 'numeric', 'between:-11,6.5'],
            'longitude' => ['required', 'numeric', 'between:95,141'],
        ];
    }

    public static function messages()
    {
        return [
            'name.required' => 'Nama unit wajib diisi.',
            'address.required' => 'Alamat unit wajib diisi.',

            'type_id.required' => 'Tipe fasilitas wajib dipilih.',
            'type_id.exists' => 'Tipe fasilitas tidak valid.',

            'province_id.required' => 'Provinsi wajib dipilih.',
            'province_id.exists' => 'Provinsi tidak valid.',

            'city_id.required' => 'Kota wajib dipilih.',
            'city_id.exists' => 'Kota tidak valid.',

            'latitude.required' => 'Latitude wajib diisi.',
            'latitude.numeric' => 'Latitude harus berupa angka.',
            'latitude.between' => 'Latitude harus berada di wilayah Indonesia (-11 s.d. 6.5).',

            'longitude.required' => 'Longitude wajib diisi.',
            'longitude.numeric' => 'Longitude harus berupa angka.',
            'longitude.between' => 'Longitude harus berada di wilayah Indonesia (95 s.d. 141).',
        ];
    }
}
