<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddScoreBeforeRebuttal extends Migration
{
    protected $tables = ['marturities', 'kpis'];

    /** Total Score ML aktual (hasil cek Pusat) tepat saat sanggahan dimulai = hasil validasi pertama. */
    public function up()
    {
        foreach ($this->tables as $table) {
            Schema::table($table, function (Blueprint $blueprint) {
                $blueprint->decimal('score_before_rebuttal', 10, 4)->nullable();
            });
        }
    }

    public function down()
    {
        foreach ($this->tables as $table) {
            Schema::table($table, function (Blueprint $blueprint) {
                $blueprint->dropColumn('score_before_rebuttal');
            });
        }
    }
}
