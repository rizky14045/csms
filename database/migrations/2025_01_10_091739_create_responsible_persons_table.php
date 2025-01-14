<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResponsiblePersonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('responsible_persons', function (Blueprint $table) {
            $table->id();
            $table->integer('monthly_report_id')->refences('id')->on('monthly_reports')->nullable();
            $table->integer('user_id')->refences('id')->on('users')->nullable();
            $table->string('name')->nullable();
            $table->string('position')->nullable();
            $table->string('work_unit')->nullable();
            $table->string('training_smp')->nullable();
            $table->string('auditor_smp')->nullable();
            $table->string('main')->nullable();
            $table->string('investigation')->nullable();
            $table->string('mansrisk')->nullable();
            $table->string('stackholder_management')->nullable();
            $table->string('last_education')->nullable();
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
        Schema::dropIfExists('responsible_persons');
    }
}
