<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAghtDatasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aght_datas', function (Blueprint $table) {
            $table->id();
            $table->integer('monthly_report_id')->refences('id')->on('monthly_reports')->nullable();
            $table->integer('user_id')->refences('id')->on('users')->nullable();
            $table->string('activity')->nullable();
            $table->date('incident_date')->nullable();
            $table->time('incident_time')->nullable();
            $table->string('incident_location')->nullable();
            $table->string('loss')->nullable();
            $table->string('after_incident')->nullable();
            $table->string('officer_contacted')->nullable();
            $table->string('note')->nullable();
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
        Schema::dropIfExists('aght_datas');
    }
}
