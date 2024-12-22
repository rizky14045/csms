<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateForeignWorkersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('foreign_workers', function (Blueprint $table) {
            $table->id();
            $table->integer('monthly_report_id')->refences('id')->on('monthly_reports')->nullable();
            $table->integer('user_id')->refences('id')->on('users')->nullable();
            $table->string('name')->nullable();
            $table->string('nationality')->nullable();
            $table->string('company')->nullable();
            $table->string('position')->nullable();
            $table->string('category')->nullable();
            $table->string('paspor')->nullable();
            $table->string('visa')->nullable();
            $table->string('vitas')->nullable();
            $table->string('kitas')->nullable();
            $table->string('rptka')->nullable();
            $table->string('arrived_date')->nullable();
            $table->string('return_date')->nullable();
            $table->string('note')->nullable();
            $table->string('attachment_file')->nullable();
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
        Schema::dropIfExists('foreign_workers');
    }
}
