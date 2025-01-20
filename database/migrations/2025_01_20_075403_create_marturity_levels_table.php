<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMarturityLevelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('marturity_levels', function (Blueprint $table) {
            $table->id();
            $table->integer('marturity_sub_area_id')->refences('id')->on('marturity_sub_areas')->nullable();
            $table->string('level')->nullable();
            $table->text('description')->nullable();
            $table->integer('order')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('marturity_levels');
    }
}
