<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSignLevelAssesmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sign_level_assesments', function (Blueprint $table) {
            $table->id();
            $table->integer('vendor_id')->refences('id')->on('vendors')->nullable();
            $table->integer('assesment_id')->refences('id')->on('assesments')->nullable();
            $table->integer('level_assesment_id')->refences('id')->on('level_assesments')->nullable();
            $table->integer('sign_question_id')->refences('id')->on('sign_question_assesments')->nullable();
            $table->integer('level')->nullable();
            $table->string('level_description')->nullable();
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
        Schema::dropIfExists('sign_level_assesments');
    }
}
