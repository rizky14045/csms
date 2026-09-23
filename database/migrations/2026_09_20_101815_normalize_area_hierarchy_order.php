<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class NormalizeAreaHierarchyOrder extends Migration
{
    /**
     * Renumber `order` sequentially (1..n) within each correct parent scope.
     * Existing data has `order` values that were assigned as a single counter
     * per `type` across the whole table instead of per-parent, causing gaps
     * and duplicates once rows were reordered/deleted independently per parent.
     *
     * @return void
     */
    public function up()
    {
        foreach (['marturity', 'kpi'] as $type) {
            $areas = DB::table('areas')
                ->where('type', $type)
                ->whereNull('deleted_at')
                ->orderBy('order')
                ->orderBy('id')
                ->get();

            $order = 1;
            foreach ($areas as $area) {
                DB::table('areas')->where('id', $area->id)->update(['order' => $order++]);
            }
        }

        $areaIds = DB::table('sub_areas')->whereNull('deleted_at')->distinct()->pluck('area_id');
        foreach ($areaIds as $areaId) {
            $subAreas = DB::table('sub_areas')
                ->where('area_id', $areaId)
                ->whereNull('deleted_at')
                ->orderBy('order')
                ->orderBy('id')
                ->get();

            $order = 1;
            foreach ($subAreas as $subArea) {
                DB::table('sub_areas')->where('id', $subArea->id)->update(['order' => $order++]);
            }
        }

        $subAreaIds = DB::table('levels')->whereNull('deleted_at')->distinct()->pluck('sub_area_id');
        foreach ($subAreaIds as $subAreaId) {
            $levels = DB::table('levels')
                ->where('sub_area_id', $subAreaId)
                ->whereNull('deleted_at')
                ->orderBy('order')
                ->orderBy('id')
                ->get();

            $order = 1;
            foreach ($levels as $level) {
                DB::table('levels')->where('id', $level->id)->update(['order' => $order++]);
            }
        }

        $levelIds = DB::table('notes')->whereNull('deleted_at')->distinct()->pluck('level_id');
        foreach ($levelIds as $levelId) {
            $notes = DB::table('notes')
                ->where('level_id', $levelId)
                ->whereNull('deleted_at')
                ->orderBy('order')
                ->orderBy('id')
                ->get();

            $order = 1;
            foreach ($notes as $note) {
                DB::table('notes')->where('id', $note->id)->update(['order' => $order++]);
            }
        }
    }

    /**
     * This is a data-normalization migration; the previous (inconsistent)
     * order values are not meaningful to restore.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
