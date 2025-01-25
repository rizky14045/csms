<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubAreasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_areas', function (Blueprint $table) {
            $table->id();
            $table->integer('area_id')->refences('id')->on('areas')->nullable();
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->string('reference')->nullable();
            $table->integer('order')->nullable();
            $table->enum('type', ['marturity', 'kpi'])->nullable();
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
        Schema::dropIfExists('sub_areas');
    }
}
