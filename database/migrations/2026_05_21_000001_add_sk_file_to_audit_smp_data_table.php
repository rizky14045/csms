<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSkFileToAuditSmpDataTable extends Migration
{
    public function up()
    {
        Schema::table('audit_smp_data', function (Blueprint $table) {
            $table->string('sk_file')->nullable()->after('end_audit');
        });
    }

    public function down()
    {
        Schema::table('audit_smp_data', function (Blueprint $table) {
            $table->dropColumn('sk_file');
        });
    }
}
