<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKpiAreasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kpi_areas', function (Blueprint $table) {
            $table->id();
            $table->integer('unit_id')->refences('id')->on('users')->nullable();
            $table->integer('kpi_id')->refences('id')->on('kpis')->nullable();
            $table->string('name')->nullable();
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
        Schema::dropIfExists('kpi_areas');
    }
}
