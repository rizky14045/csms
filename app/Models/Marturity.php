<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Marturity extends Model
{
    use HasFactory, SoftDeletes;
    use \App\Models\Concerns\HasStatusHistory;
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

    protected $casts = [
        'send_date' => 'date:Y-m-d',
    ];

    public function unit()
    {
        return $this->hasOne(Unit::class, 'id', 'unit_id');
    }

    public function getInvalidItemsNotesByUnit()
    {
        return $this->hasMany(
            \App\Models\MarturityNote::class,
            'marturity_id'
        )->where(function ($q) {
            $q->whereNull('attachment_file');
        });
    }

    public function statusHistoryCreatedLabel(): string
    {
        return 'Maturity dibuat';
    }

    public function statusHistoryChanges(): array
    {
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
