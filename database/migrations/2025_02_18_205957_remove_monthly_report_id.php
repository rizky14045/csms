<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveMonthlyReportId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('responsible_persons', function (Blueprint $table) {
            $table->dropColumn('monthly_report_id');
        });
        Schema::table('security_externals', function (Blueprint $table) {
            $table->dropColumn('monthly_report_id');
        });
        Schema::table('agreement_externals', function (Blueprint $table) {
            $table->dropColumn('monthly_report_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('responsible_persons', function (Blueprint $table) {
             $table->integer('monthly_report_id')->refences('id')->on('monthly_reports')->nullable();
        });
        Schema::table('security_externals', function (Blueprint $table) {
             $table->integer('monthly_report_id')->refences('id')->on('monthly_reports')->nullable();
        });
        Schema::table('agreement_externals', function (Blueprint $table) {
             $table->integer('monthly_report_id')->refences('id')->on('monthly_reports')->nullable();
        });
    }
}
