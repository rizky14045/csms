<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgreementExternalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agreement_externals', function (Blueprint $table) {
            $table->id();
            $table->integer('monthly_report_id')->refences('id')->on('monthly_reports')->nullable();
            $table->integer('user_id')->refences('id')->on('users')->nullable();
            $table->string('instansi')->nullable();
            $table->string('name')->nullable();
            $table->string('pkt_number')->nullable();
            $table->string('pkt_title')->nullable();
            $table->date('expired_date')->nullable();
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
        Schema::dropIfExists('agreement_externals');
    }
}
