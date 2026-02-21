<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLaporanBulananBiayaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('laporan_bulanan_biaya', function (Blueprint $table) {
            $table->id();
            $table->integer('monthly_report_id')->refences('id')->on('monthly_reports')->nullable();
            $table->integer('user_id')->refences('id')->on('users')->nullable();
            $table->string('kode_aktifitas')->nullable();
            $table->string('kode_prk')->nullable();
            $table->string('deskripsi_kegiatan')->nullable();
            $table->string('jumlah_anggaran')->nullable();
            $table->string('penyerapan_anggaran')->nullable();
            $table->string('prosentase_anggaran')->nullable();
            $table->string('keterangan')->nullable();
            $table->string('type')->nullable();
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
        Schema::dropIfExists('laporan_bulanan_biaya');
    }
}
