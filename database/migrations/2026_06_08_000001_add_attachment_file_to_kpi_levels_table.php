<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAttachmentFileToKpiLevelsTable extends Migration
{
    public function up()
    {
        Schema::table('kpi_levels', function (Blueprint $table) {
            $table->string('attachment_file')->nullable()->after('description');
        });
    }

    public function down()
    {
        Schema::table('kpi_levels', function (Blueprint $table) {
            $table->dropColumn('attachment_file');
        });
    }
}
