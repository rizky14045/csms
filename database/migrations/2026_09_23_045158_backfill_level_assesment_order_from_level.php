<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class BackfillLevelAssesmentOrderFromLevel extends Migration
{
    public function up()
    {
        // Level menentukan urutan; samakan order dengan level untuk data yang sudah ada.
        DB::statement("UPDATE level_assesments SET \"order\" = level WHERE level IS NOT NULL AND (\"order\" IS NULL OR \"order\" != level)");
    }

    public function down()
    {
        //
    }
}
