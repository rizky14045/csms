<?php

namespace App\Http\Validation;

use Illuminate\Support\Facades\DB;

class VendorValidation
{
    public static function rulesForCreate()
    {
        return [

            'vendor_exists' => 'required|in:0,1',

            // ⭐ Vendor SUDAH ADA
            'vendor_id' => 'required_if:vendor_exists,1',

            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
            'contract_number' => 'required',


            // ⭐ Vendor BELUM ADA
            'name'    => 'required_if:vendor_exists,0',
            'npwp'    => 'required_if:vendor_exists,0',
            'email'   => 'required_if:vendor_exists,0|unique:users',
            'address' => 'required_if:vendor_exists,0',

        ];
    }

    public static function messages()
    {
        return [

            'vendor_exists.required' => 'Status vendor harus dipilih.',

            // ⭐ Vendor sudah ada
            'vendor_id.required_if' => 'Vendor harus dipilih.',
            'vendor_id.exists' => 'Vendor tidak ditemukan.',

            'start_date.required' => 'Tanggal mulai wajib diisi.',
            'start_date.date' => 'Format tanggal mulai tidak valid.',

            'end_date.required' => 'Tanggal selesai wajib diisi.',
            'end_date.date' => 'Format tanggal selesai tidak valid.',
            'end_date.after_or_equal' => 'Tanggal selesai harus setelah atau sama dengan tanggal mulai.',

            'contract_number.required' => "Nomor kontrak wajib diisi",

            // ⭐ Vendor baru
            'name.required_if' => 'Nama harus diisi!',
            'npwp.required_if' => 'NPWP harus diisi!',
            'email.required_if' => 'Email harus diisi!',
            'email.unique' => 'Email sudah digunakan!',
            'email.email' => 'Format email tidak sesuai!',
            'address.required_if' => 'Alamat harus diisi!',

        ];
    }
}