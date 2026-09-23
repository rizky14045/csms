<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSourceIdAndExclusionsForMonthlyReportSync extends Migration
{
    protected $tables = ['securities', 'attributes', 'responsible_persons', 'security_externals', 'agreement_externals'];

    public function up()
    {
        // Salinan per laporan menyimpan id master asalnya di source_id
        foreach ($this->tables as $table) {
            Schema::table($table, function (Blueprint $t) {
                $t->unsignedBigInteger('source_id')->nullable();
            });
        }

        // Baris master yang sengaja dikeluarkan dari sebuah laporan (agar tidak ditambahkan lagi saat sinkron)
        Schema::create('monthly_report_exclusions', function (Blueprint $t) {
            $t->id();
            $t->unsignedBigInteger('monthly_report_id');
            $t->string('section');
            $t->unsignedBigInteger('master_id');
            $t->timestamps();
            $t->unique(['monthly_report_id', 'section', 'master_id'], 'mre_unique');
        });
    }

    public function down()
    {
        Schema::dropIfExists('monthly_report_exclusions');

        foreach ($this->tables as $table) {
            Schema::table($table, function (Blueprint $t) {
                $t->dropColumn('source_id');
            });
        }
    }
}
