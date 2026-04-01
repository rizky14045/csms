<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuditSmpScoreTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('audit_smp_score', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('audit_smp_data_id')->nullable();
            $table->text('name')->nullable();
            $table->integer('bobot')->nullable();
            $table->string('type')->nullable();
            $table->integer('parent_id')->nullable();
            $table->float('pencapaian_nilai_kriteria')->nullable();
            $table->float('pencapaian_nilai_elemen')->nullable();
            $table->string('temuan')->nullable();
            $table->string('rekomendasi')->nullable();
            $table->date('due_date')->nullable();
            $table->string('pic')->nullable();
            $table->string('evidence_file')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('audit_smp_score');
    }
}
