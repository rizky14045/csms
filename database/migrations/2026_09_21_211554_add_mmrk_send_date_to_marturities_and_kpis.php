<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMmrkSendDateToMarturitiesAndKpis extends Migration
{
    public function up()
    {
        foreach (['marturities', 'kpis'] as $table) {
            Schema::table($table, function (Blueprint $t) {
                // tanggal Unit mengirim ke MMRK (send_date = tanggal kirim ke Pusat)
                $t->date('mmrk_send_date')->nullable();
            });
        }
    }

    public function down()
    {
        foreach (['marturities', 'kpis'] as $table) {
            Schema::table($table, function (Blueprint $t) {
                $t->dropColumn('mmrk_send_date');
            });
        }
    }
}
