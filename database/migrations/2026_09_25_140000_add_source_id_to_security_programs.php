<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSourceIdToSecurityPrograms extends Migration
{
    /** Salinan program di laporan bulanan: user_id null, source_id = id master asalnya. */
    protected $tables = ['security_programs', 'main_security_programs'];

    public function up()
    {
        foreach ($this->tables as $table) {
            Schema::table($table, function (Blueprint $blueprint) {
                $blueprint->unsignedBigInteger('source_id')->nullable()->index();
            });
        }
    }

    public function down()
    {
        foreach ($this->tables as $table) {
            Schema::table($table, function (Blueprint $blueprint) {
                $blueprint->dropColumn('source_id');
            });
        }
    }
}
