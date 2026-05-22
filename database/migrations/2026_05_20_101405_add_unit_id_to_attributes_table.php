<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUnitIdToAttributesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('attributes', function (Blueprint $table) {
            $table->integer('unit_id')->nullable()->after('user_id');
        });
    }

    public function down()
    {
        Schema::table('attributes', function (Blueprint $table) {
            $table->dropColumn('unit_id');
        });
    }
}
