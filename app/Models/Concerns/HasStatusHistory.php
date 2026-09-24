<?php

namespace App\Models\Concerns;

use App\Models\StatusHistory;

/**
 * Mencatat siapa & kapan untuk pembuatan dan tiap perubahan status.
 * Model wajib mengimplementasikan statusHistoryCreatedLabel() dan
 * statusHistoryChanges() (daftar [event, label, from, to] dari perubahan saat ini).
 */
trait HasStatusHistory
{
    public static function bootHasStatusHistory()
    {
        static::created(function ($model) {
            StatusHistory::record($model, 'created', $model->statusHistoryCreatedLabel(), null, 0);
        });

        static::updated(function ($model) {
            foreach ($model->statusHistoryChanges() as [$event, $label, $from, $to]) {
                StatusHistory::record($model, $event, $label, $from, $to);
            }
        });
    }

    public function statusHistories()
    {
        return StatusHistory::where('subject_type', static::class)
            ->where('subject_id', $this->getKey())
            ->orderBy('created_at')->orderBy('id');
    }
}
