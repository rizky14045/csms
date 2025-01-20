<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMarturitySubAreasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('marturity_sub_areas', function (Blueprint $table) {
            $table->id();
            $table->integer('marturity_area_id')->refences('id')->on('marturity_areas')->nullable();
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->string('reference')->nullable();
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
        Schema::dropIfExists('marturity_sub_areas');
    }
}
