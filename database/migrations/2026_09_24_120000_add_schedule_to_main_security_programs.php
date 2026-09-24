<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddScheduleToMainSecurityPrograms extends Migration
{
    public function up()
    {
        Schema::table('main_security_programs', function (Blueprint $table) {
            // Daftar minggu terpilih (JSON [[bulan 1-12, minggu 1-5], ...]). Null = data lama, pakai rentang start-end.
            $table->text('schedule')->nullable();
        });
    }

    public function down()
    {
        Schema::table('main_security_programs', function (Blueprint $table) {
            $table->dropColumn('schedule');
        });
    }
}
