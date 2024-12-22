<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSecurityFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('security_forms', function (Blueprint $table) {
            $table->id();
            $table->integer('monthly_report_id')->refences('id')->on('monthly_reports')->nullable();
            $table->integer('user_id')->refences('id')->on('users')->nullable();
            $table->integer('security_id')->refences('id')->on('securities')->nullable();
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
        Schema::dropIfExists('security_forms');
    }
}
