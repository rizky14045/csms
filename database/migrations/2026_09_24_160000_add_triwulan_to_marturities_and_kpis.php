<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTriwulanToMarturitiesAndKpis extends Migration
{
    public function up()
    {
        foreach (['marturities', 'kpis'] as $table) {
            Schema::table($table, function (Blueprint $t) {
                // Periode baru per triwulan (1-4). Data lama (semester) dibiarkan dengan triwulan null.
                $t->unsignedSmallInteger('triwulan')->nullable();
            });
        }
    }

    public function down()
    {
        foreach (['marturities', 'kpis'] as $table) {
            Schema::table($table, function (Blueprint $t) {
                $t->dropColumn('triwulan');
            });
        }
    }
}
