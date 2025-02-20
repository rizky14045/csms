<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAttachmentFileToForeignWorkersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('foreign_workers', function (Blueprint $table) {
            $table->string('paspor_file')->nullable();
            $table->string('visa_file')->nullable();
            $table->string('vitas_file')->nullable();
            $table->string('kitas_file')->nullable();
            $table->string('rptka_file')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('foreign_workers', function (Blueprint $table) {
            $table->dropColumn('paspor_file');
            $table->dropColumn('visa_file');
            $table->dropColumn('vitas_file');
            $table->dropColumn('kitas_file');
            $table->dropColumn('rptka_file');
        });
    }
}
