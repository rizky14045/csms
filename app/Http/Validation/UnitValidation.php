<?php

namespace App\Http\Validation;

use Illuminate\Validation\Rule;

class UnitValidation
{
    public static function rulesForCreate()
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'address' => [
                'required',
                'string',
                'max:255',
            ],
            'type' => 'nullable|in:Pusat,Unit,UL',
            'parent_unit_id' => 'required_if:type,UL|nullable|exists:units,id',
        ];
    }

    /**
     * Validation rules for update role
     */
    public static function rulesForUpdate()
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'address' => [
                'required',
                'string',
                'max:255',
            ],
            'type' => 'nullable|in:Pusat,Unit,UL',
            'parent_unit_id' => 'required_if:type,UL|nullable|exists:units,id',
        ];
    }

    public static function messages()
    {
        return [
            'name.required' => 'Nama unit wajib diisi.',
            'address.required' => 'Alamat unit wajib diisi.',
            'parent_unit_id.required_if' => 'Unit induk wajib dipilih untuk tipe UL.',
            'parent_unit_id.exists' => 'Unit induk tidak valid.',
        ];
    }
}
