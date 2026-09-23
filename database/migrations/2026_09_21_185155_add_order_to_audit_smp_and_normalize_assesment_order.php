<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddOrderToAuditSmpAndNormalizeAssesmentOrder extends Migration
{
    public function up()
    {
        Schema::table('audit_smp', function (Blueprint $table) {
            $table->integer('order')->nullable();
        });

        // Backfill audit_smp: urut berdasarkan created_at, id per (parent_id, type)
        $groups = DB::table('audit_smp')->whereNull('deleted_at')
            ->select('parent_id', 'type')->distinct()->get();
        foreach ($groups as $g) {
            $q = DB::table('audit_smp')->whereNull('deleted_at')->where('type', $g->type);
            $g->parent_id === null ? $q->whereNull('parent_id') : $q->where('parent_id', $g->parent_id);
            $n = 1;
            foreach ($q->orderBy('created_at')->orderBy('id')->get() as $row) {
                DB::table('audit_smp')->where('id', $row->id)->update(['order' => $n++]);
            }
        }

        // Rapikan order assesment per parent (sebelumnya urutan hitung tidak konsisten)
        $this->normalize('category_assesments', null);
        $this->normalize('question_assesments', 'category_id');
        $this->normalize('level_assesments', 'question_id');
    }

    protected function normalize($table, $parentCol)
    {
        $parents = $parentCol
            ? DB::table($table)->whereNull('deleted_at')->distinct()->pluck($parentCol)
            : collect([null]);

        foreach ($parents as $parent) {
            $q = DB::table($table)->whereNull('deleted_at');
            if ($parentCol) {
                $q->where($parentCol, $parent);
            }
            $n = 1;
            foreach ($q->orderBy('order')->orderBy('id')->get() as $row) {
                DB::table($table)->where('id', $row->id)->update(['order' => $n++]);
            }
        }
    }

    public function down()
    {
        Schema::table('audit_smp', function (Blueprint $table) {
            $table->dropColumn('order');
        });
    }
}
