<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSignCategoryAssesmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sign_category_assesments', function (Blueprint $table) {
            $table->id();
            $table->integer('vendor_id')->refences('id')->on('vendors')->nullable();
            $table->integer('assesment_id')->refences('id')->on('assesments')->nullable();
            $table->integer('category_assesment_id')->refences('id')->on('category_assesments')->nullable();
            $table->string('category_name')->nullable();
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
        Schema::dropIfExists('sign_category_assesments');
    }
}
