<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrderToMarturityAndKpiSnapshotTables extends Migration
{
    protected $tables = [
        'marturity_areas',
        'marturity_sub_areas',
        'marturity_levels',
        'marturity_notes',
        'kpi_areas',
        'kpi_sub_areas',
        'kpi_levels',
        'kpi_notes',
    ];

    /**
     * @return void
     */
    public function up()
    {
        foreach ($this->tables as $table) {
            Schema::table($table, function (Blueprint $blueprint) {
                $blueprint->integer('order')->nullable();
            });
        }
    }

    /**
     * @return void
     */
    public function down()
    {
        foreach ($this->tables as $table) {
            Schema::table($table, function (Blueprint $blueprint) {
                $blueprint->dropColumn('order');
            });
        }
    }
}
