<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActivityLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();

            $table->string('type', 50);          // permission.create / permission.delete
            $table->unsignedSmallInteger('error_code'); // 200, 403, 404, 500
            $table->timestamp('time');

            $table->string('ip', 45)->nullable();
            $table->unsignedBigInteger('user_id')->nullable();

            $table->string('activity');
            $table->string('browser')->nullable();
            $table->string('os')->nullable();

            $table->json('properties_data')->nullable();

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
        Schema::dropIfExists('activity_logs');
    }
}
