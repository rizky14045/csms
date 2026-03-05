<?php

namespace App\Http\Validation;

class MainSecurityProgramValidation
{
    public static function rulesForCreate()
    {
        return [
            'program_name' => 'required',
            'start_month' => 'required|in:Januari,Februari,Maret,April,Mei,Juni,Juli,Agustus,September,Oktober,November,Desember',
            'start_week' => 'required|integer|min:1|max:5',
            'end_month' => 'required|in:Januari,Februari,Maret,April,Mei,Juni,Juli,Agustus,September,Oktober,November,Desember',
            'end_week' => 'required|integer|min:1|max:5',
        ];
    }

    public static function rulesForUpdate()
    {
            return [
                'program_name' => 'required',
                'start_month' => 'required|in:Januari,Februari,Maret,April,Mei,Juni,Juli,Agustus,September,Oktober,November,Desember',
                'start_week' => 'required|integer|min:1|max:5',
                'end_month' => 'required|in:Januari,Februari,Maret,April,Mei,Juni,Juli,Agustus,September,Oktober,November,Desember',
                'end_week' => 'required|integer|min:1|max:5',
        ];
    }

    public static function messages()
    {
        $messages = [
            'program_name.required' => 'Nama program wajib diisi.',
            'start_month.required' => 'Bulan mulai wajib diisi.',
            'start_month.in' => 'Bulan mulai harus salah satu dari: Januari, Februari, Maret, April, Mei, Juni, Juli, Agustus, September, Oktober, November, Desember.',
            'start_week.required' => 'Minggu mulai wajib diisi.',
            'start_week.integer' => 'Minggu mulai harus berupa angka.',
            'start_week.min' => 'Minggu mulai minimal adalah 1.',
            'start_week.max' => 'Minggu mulai maksimal adalah 5.',
            'end_month.required' => 'Bulan selesai wajib diisi.',
            'end_month.in' => 'Bulan selesai harus salah satu dari: Januari, Februari, Maret, April, Mei, Juni, Juli, Agustus, September, Oktober, November, Desember.',
            'end_week.required' => 'Minggu selesai wajib diisi.',
            'end_week.integer' => 'Minggu selesai harus berupa angka.',
            'end_week.min' => 'Minggu selesai minimal adalah 1.',
            'end_week.max' => 'Minggu selesai maksimal adalah 5.',
        ];

        return $messages;
    }
}
