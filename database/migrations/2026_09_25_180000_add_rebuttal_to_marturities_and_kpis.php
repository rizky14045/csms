<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRebuttalToMarturitiesAndKpis extends Migration
{
    protected $tables = ['marturities', 'kpis'];

    /**
     * validated_at        : waktu validasi Pusat selesai (awal masa sanggah 7 hari). Data lama = null (tidak ada masa sanggah).
     * rebuttal_state      : 0 belum, 1 Unit menyusun sanggahan, 2 dikirim ke MMRK, 3 dikirim ke Pusat, 4 selesai sanggah.
     */
    public function up()
    {
        foreach ($this->tables as $table) {
            Schema::table($table, function (Blueprint $blueprint) {
                $blueprint->timestamp('validated_at')->nullable();
                $blueprint->smallInteger('rebuttal_state')->default(0);
                $blueprint->timestamp('rebuttal_started_at')->nullable();
                $blueprint->timestamp('rebuttal_finished_at')->nullable();
            });
        }
    }

    public function down()
    {
        foreach ($this->tables as $table) {
            Schema::table($table, function (Blueprint $blueprint) {
                $blueprint->dropColumn(['validated_at', 'rebuttal_state', 'rebuttal_started_at', 'rebuttal_finished_at']);
            });
        }
    }
}
