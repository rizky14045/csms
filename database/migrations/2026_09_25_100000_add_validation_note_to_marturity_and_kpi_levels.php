<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddValidationNoteToMarturityAndKpiLevels extends Migration
{
    /** Catatan validasi Pusat per level (level adalah salinan per Maturity/KPI, jadi tersimpan per pengisian). */
    protected $tables = ['marturity_levels', 'kpi_levels'];

    public function up()
    {
        foreach ($this->tables as $table) {
            Schema::table($table, function (Blueprint $blueprint) {
                $blueprint->text('validation_note')->nullable();
            });
        }
    }

    public function down()
    {
        foreach ($this->tables as $table) {
            Schema::table($table, function (Blueprint $blueprint) {
                $blueprint->dropColumn('validation_note');
            });
        }
    }
}
