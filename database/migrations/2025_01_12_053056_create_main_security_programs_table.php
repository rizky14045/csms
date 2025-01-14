<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMainSecurityProgramsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('main_security_programs', function (Blueprint $table) {
            $table->id();
            $table->integer('program_id')->refences('id')->on('security_programs')->nullable();
            $table->integer('user_id')->refences('id')->on('users')->nullable();
            $table->string('program_name');
            $table->enum('start_month', ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember']);
            $table->enum('start_week', ['1', '2', '3', '4']);
            $table->enum('end_month', ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember']);
            $table->enum('end_week', ['1', '2', '3', '4']);
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
        Schema::dropIfExists('main_security_programs');
    }
}
