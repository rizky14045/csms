<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\AuditSMP;
use App\Models\CategoryAssesment;
use App\Models\Level;
use App\Models\LevelAssesment;
use App\Models\Note;
use App\Models\QuestionAssesment;
use App\Models\SubArea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReorderController extends Controller
{
    /**
     * entity => [model, kolom parent (null jika tidak ada), kolom type (null jika tidak ada), permission]
     * {type} pada permission diganti dengan marturity/kpi atau jenis node audit.
     */
    protected function config($entity, $type)
    {
        switch ($entity) {
            case 'area':
                return [Area::class, null, 'type', "edit.{$type}.area"];
            case 'sub_area':
                return [SubArea::class, 'area_id', 'type', "edit.{$type}.subarea"];
            case 'level':
                return [Level::class, 'sub_area_id', 'type', "edit.{$type}.level"];
            case 'note':
                return [Note::class, 'level_id', 'type', "edit.{$type}.note"];
            case 'audit':
                $perm = [
                    'header'     => 'edit.audit.smp.admin',
                    'pernyataan' => 'edit.element.audit.smp.admin',
                    'kriteria'   => 'edit.criteria.audit.smp.admin',
                    'evidence'   => 'edit.evidence.audit.smp.admin',
                ][$type] ?? null;
                return $perm ? [AuditSMP::class, 'parent_id', 'type', $perm] : null;
            case 'assesment_category':
                return [CategoryAssesment::class, null, null, 'edit.category.assesment'];
            case 'assesment_question':
                return [QuestionAssesment::class, 'category_id', null, 'edit.question.assesment'];
            case 'assesment_level':
                return [LevelAssesment::class, 'question_id', null, 'edit.level.assesment'];
        }

        return null;
    }

    public function store(Request $request)
    {
        $entity = (string) $request->input('entity');
        $type   = (string) $request->input('type');
        $ids    = array_values(array_unique(array_map('intval', (array) $request->input('ids', []))));

        $config = $this->config($entity, $type);
        if (!$config || count($ids) === 0) {
            return response()->json(['success' => false, 'message' => 'Permintaan tidak valid'], 422);
        }

        [$model, $parentCol, $typeCol, $permission] = $config;

        if (!auth()->user()->can($permission)) {
            return response()->json(['success' => false, 'message' => 'Tidak memiliki akses'], 403);
        }

        $rows = $model::whereIn('id', $ids)->get();
        if ($rows->count() !== count($ids)) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan'], 422);
        }

        if ($typeCol && $rows->pluck($typeCol)->unique()->count() !== 1) {
            return response()->json(['success' => false, 'message' => 'Data tidak sejajar'], 422);
        }
        if ($typeCol && in_array($entity, ['area', 'sub_area', 'level', 'note'], true) && $rows->first()->$typeCol !== $type) {
            return response()->json(['success' => false, 'message' => 'Tipe data tidak sesuai'], 422);
        }

        // semua item harus satu induk dan mencakup seluruh saudaranya
        $siblings = $model::query();
        if ($parentCol) {
            if ($rows->pluck($parentCol)->unique()->count() !== 1) {
                return response()->json(['success' => false, 'message' => 'Data tidak sejajar'], 422);
            }
            $parent = $rows->first()->$parentCol;
            $parent === null ? $siblings->whereNull($parentCol) : $siblings->where($parentCol, $parent);
        }
        if ($typeCol) {
            $siblings->where($typeCol, $rows->first()->$typeCol);
        }

        if ($siblings->count() !== count($ids)) {
            return response()->json(['success' => false, 'message' => 'Daftar urutan tidak lengkap, muat ulang halaman'], 409);
        }

        DB::transaction(function () use ($model, $ids, $entity) {
            foreach ($ids as $index => $id) {
                $position = $index + 1;
                $values = ['order' => $position];

                // Untuk Level Assesment, level menentukan urutan: drag & drop
                // ikut mengubah nilai level supaya keduanya tetap sinkron.
                if ($entity === 'assesment_level') {
                    $values['level'] = $position;
                }

                $model::where('id', $id)->update($values);
            }
        });

        return response()->json(['success' => true]);
    }
}
