<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSecuritiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('securities', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->refences('id')->on('users')->nullable();
            $table->string('name')->nullable();
            $table->string('unit_work')->nullable();
            $table->string('nid')->nullable();
            $table->string('registration_number')->nullable();
            $table->date('expired_card_date')->nullable();
            $table->string('position')->nullable();
            $table->string('birth_place')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('qualification')->nullable();
            $table->string('last_education')->nullable();
            $table->string('gender')->nullable();
            $table->string('note')->nullable();
            $table->softDeletes(); 
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
        Schema::dropIfExists('securities');
    }
}
