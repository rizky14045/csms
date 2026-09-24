<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class ChangeKpiNotesNoteToText extends Migration
{
    /**
     * Snapshot note KPI dulu varchar(255); master `notes` sudah longText,
     * sehingga note panjang dari master gagal disalin ke kpi_notes.
     */
    public function up()
    {
        DB::statement('ALTER TABLE kpi_notes ALTER COLUMN note TYPE TEXT');
    }

    public function down()
    {
        DB::statement('ALTER TABLE kpi_notes ALTER COLUMN note TYPE VARCHAR(255) USING LEFT(note, 255)');
    }
}
