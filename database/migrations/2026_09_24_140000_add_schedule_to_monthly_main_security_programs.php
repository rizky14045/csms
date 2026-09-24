<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddScheduleToMonthlyMainSecurityPrograms extends Migration
{
    public function up()
    {
        Schema::table('monthly_main_security_programs', function (Blueprint $table) {
            // Realisasi per-minggu (JSON [[bulan, minggu], ...]). Null = data lama, pakai rentang start-end.
            $table->text('schedule')->nullable();
        });
    }

    public function down()
    {
        Schema::table('monthly_main_security_programs', function (Blueprint $table) {
            $table->dropColumn('schedule');
        });
    }
}
