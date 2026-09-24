<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateStatusHistoriesTable extends Migration
{
    /** tabel => [class model, label "dibuat"] */
    protected $backfill = [
        'monthly_reports' => ['App\Models\MonthlyReport', 'Laporan bulanan dibuat'],
        'assesments'      => ['App\Models\Assesment', 'Assessment dibuat (Input BUJP)'],
        'marturities'     => ['App\Models\Marturity', 'Maturity dibuat'],
        'kpis'            => ['App\Models\Kpi', 'KPI dibuat'],
        'audit_smp_data'  => ['App\Models\AuditSmpData', 'Audit SMP dibuat'],
    ];

    public function up()
    {
        Schema::create('status_histories', function (Blueprint $table) {
            $table->id();
            $table->string('subject_type', 100);
            $table->unsignedBigInteger('subject_id');
            $table->string('event', 50);
            $table->string('label');
            $table->smallInteger('from_status')->nullable();
            $table->smallInteger('to_status')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('user_name')->nullable();
            $table->string('user_role')->nullable();
            $table->timestamp('created_at')->nullable();

            $table->index(['subject_type', 'subject_id']);
        });

        // Data lama: hanya "dibuat" yang pelakunya tercatat (created_by). Pengiriman
        // sebelumnya hanya menyimpan tanggal tanpa pelaku, jadi tidak diisi ulang.
        foreach ($this->backfill as $tbl => [$class, $label]) {
            DB::statement(
                "INSERT INTO status_histories (subject_type, subject_id, event, label, to_status, user_id, user_name, created_at)
                 SELECT ?, t.id, 'created', ?, 0, t.created_by, u.name, t.created_at
                 FROM {$tbl} t LEFT JOIN users u ON u.id = t.created_by
                 WHERE t.deleted_at IS NULL",
                [$class, $label]
            );
        }
    }

    public function down()
    {
        Schema::dropIfExists('status_histories');
    }
}
