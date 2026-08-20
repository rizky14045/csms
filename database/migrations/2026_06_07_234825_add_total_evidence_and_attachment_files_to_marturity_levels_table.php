<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTotalEvidenceAndAttachmentFilesToMarturityLevelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('marturity_levels', function (Blueprint $table) {
            $table->unsignedInteger('total_evidence')->nullable()->after('description');
            $table->text('attachment_files')->nullable()->after('total_evidence');
        });
    }

    public function down()
    {
        Schema::table('marturity_levels', function (Blueprint $table) {
            $table->dropColumn(['total_evidence', 'attachment_files']);
        });
    }
}
