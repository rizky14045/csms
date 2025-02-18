<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSignQuestionAssesmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sign_question_assesments', function (Blueprint $table) {
            $table->id();
            $table->integer('vendor_id')->refences('id')->on('vendors')->nullable();
            $table->integer('assesment_id')->refences('id')->on('assesments')->nullable();
            $table->integer('question_assesment_id')->refences('id')->on('question_assesments')->nullable();
            $table->integer('sign_category_id')->refences('id')->on('sign_category_assesments')->nullable();
            $table->string('indicator')->nullable();
            $table->integer('level')->default(0);
            $table->string('attachment_file')->nullable();
            $table->text('note')->nullable();
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
        Schema::dropIfExists('sign_question_assesments');
    }
}
