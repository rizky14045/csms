<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddKtaFileToSecuritiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('securities', function (Blueprint $table) {
            $table->string('kta_file')->nullable()->after('note');
        });
    }

    public function down()
    {
        Schema::table('securities', function (Blueprint $table) {
            $table->dropColumn('kta_file');
        });
    }
}
