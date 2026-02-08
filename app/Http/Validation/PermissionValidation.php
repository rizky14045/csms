<?php

namespace App\Http\Validation;

use App\Models\Permission;
use Illuminate\Validation\Rule;

class PermissionValidation
{
    /**
     * Validation rules for create permission
     */
    public static function rulesForCreate()
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('permissions', 'name'),
            ],
        ];
    }

    /**
     * Validation rules for update permission
     */
    public static function rulesForUpdate($permissionId)
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('permissions', 'name')->ignore($permissionId),
            ],
        ];
    }

    /**
     * Custom validation messages
     */
    public static function messages()
    {
        return [
            'name.required' => 'Nama permission wajib diisi.',
            'name.string'   => 'Nama permission harus berupa teks.',
            'name.max'      => 'Nama permission maksimal 255 karakter.',
            'name.unique'   => 'Nama permission sudah digunakan.',
        ];
    }
}
