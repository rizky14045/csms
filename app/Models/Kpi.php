<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kpi extends Model
{
    use HasFactory, SoftDeletes;
    use \App\Models\Concerns\HasStatusHistory;
    use \App\Models\Concerns\HasRebuttal;

    protected $guarded = ['id'];
    protected $appends = ['period_label'];

    public function getPeriodLabelAttribute()
    {
        return \App\Services\Score\TriwulanRule::label($this->triwulan, $this->semester);
    }

    /** Potongan nama file: tw1 / s1 (data lama). */
    public function getPeriodSlugAttribute()
    {
        return $this->triwulan ? 'tw' . $this->triwulan : 's' . $this->semester;
    }
    protected $table = 'kpis';

    public function unit()
    {
        return $this->hasOne(Unit::class, 'id', 'unit_id');
    }

    public function getInvalidItemsNotesByUnit()
    {
        return $this->hasMany(
            \App\Models\KpiNote::class,
            'kpi_id'
        )->where(function ($q) {
            $q->whereNull('attachment_file');
        });
    }

    public function statusHistoryCreatedLabel(): string
    {
        return 'KPI dibuat';
    }

    public function statusHistoryChanges(): array
    {
        // Tahap sanggahan: satu peristiwa dengan label sanggahan (status ikut berubah bersamaan).
        if ($this->wasChanged('rebuttal_state')) {
            $rebuttalLabels = [
                1 => 'Sanggahan dimulai oleh Unit (data dapat diubah)',
                2 => 'Sanggahan dikirim ke MMRK',
                3 => 'Sanggahan dikirim ke Pusat (menunggu validasi)',
                4 => 'Validasi sanggahan selesai (Selesai sanggah)',
            ];
            $state = (int) $this->rebuttal_state;

            return [['rebuttal', $rebuttalLabels[$state] ?? 'Tahap sanggahan ' . $state, (int) $this->getOriginal('status'), (int) $this->status]];
        }

        if (!$this->wasChanged('status')) {
            return [];
        }

        $from = $this->getOriginal('status');
        $to   = (int) $this->status;
        $labels = [
            1 => 'Dikirim ke MMRK',
            2 => 'Dikirim ke Pusat (menunggu validasi)',
            3 => 'Validasi Pusat selesai',
            0 => 'Dikembalikan ke draft',
        ];

        return [['status', $labels[$to] ?? 'Status berubah menjadi ' . $to, $from === null ? null : (int) $from, $to]];
    }
}
