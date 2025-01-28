<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMarturityNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('marturity_notes', function (Blueprint $table) {
            $table->id();
            $table->integer('unit_id')->refences('id')->on('users')->nullable();
            $table->integer('marturity_id')->refences('id')->on('marturities')->nullable();
            $table->integer('level_id')->refences('id')->on('marturity_levels')->nullable();
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
        Schema::dropIfExists('marturity_notes');
    }
}
