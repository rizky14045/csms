<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'Administrator',
                'email' => 'admin@admin.com',
                'password' => Hash::make('password'),

                'remember_token' => Str::random(10),

                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'deleted_at' => null,

                'created_by' => 1,
                'updated_by' => 1,
                'deleted_by' => null,

                'type' => 'admin',

                'session_id' => null,
                'session_expired_date' => null,

                'expired_password_date' => Carbon::now()->addMonths(3),
                'is_password_change' => 1,

                'access_failed_count' => 0,
                'locked_until' => null,
            ],
        ]);
    }
}