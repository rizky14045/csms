<?php

namespace App\Http\Validation;

use App\Models\Permission;
use Illuminate\Validation\Rule;

class RoleValidation
{
    public static function rulesForCreate()
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('roles', 'name')->whereNull('deleted_at'),
            ],
            'permissions' => [
                'required',
                'array',
                'min:1',
                function ($attribute, $value, $fail) {
                    $validCount = Permission::whereIn('id', $value)->count();
                    if ($validCount !== count(array_unique($value))) {
                        $fail('Beberapa permission tidak valid.');
                    }
                },
            ],
            'permissions.*' => [
                'integer',
                'distinct',
            ],
        ];
    }

    /**
     * Validation rules for update role
     */
    public static function rulesForUpdate($roleId)
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('roles', 'name')->ignore($roleId),
            ],
            'permissions' => [
                'required',
                'array',
                'min:1',
                function ($attribute, $value, $fail) {
                    $validCount = Permission::whereIn('id', $value)->count();
                    if ($validCount !== count(array_unique($value))) {
                        $fail('Beberapa permission tidak valid.');
                    }
                },
            ],
            'permissions.*' => [
                'integer',
                'distinct',
            ],
        ];
    }

    public static function messages()
    {
        return [
            'name.required' => 'Nama role wajib diisi.',
            'name.unique'   => 'Nama role sudah digunakan.',

            'permissions.required' => 'Permission wajib dipilih minimal 1.',
            'permissions.array'    => 'Format permission tidak valid.',
            'permissions.min'      => 'Pilih minimal 1 permission.',

            'permissions.*.distinct'=> 'Permission tidak boleh duplikat.',
        ];
    }
}
