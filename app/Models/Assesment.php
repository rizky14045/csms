<?php

namespace App\Models;

use App\Models\Vendor;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Assesment extends Model
{
    use HasFactory, SoftDeletes;
    use \App\Models\Concerns\HasStatusHistory;
    protected $guarded = ['id'];

    public function vendor()
    {
        return $this->hasOne(Vendor::class, 'id', 'vendor_id');
    }
    public function unit()
    {
        return $this->hasOne(Unit::class, 'id', 'unit_id');
    }
    public function bujpProfile()
    {
        return $this->hasOne(BujpProfile::class, 'user_id', 'created_by');
    }

    public function getInvalidItemsQuestionByBujp()
    {
        return $this->hasMany(
            \App\Models\SignQuestionAssesment::class,
            'assesment_id'
        )->where(function ($q) {
            $q->whereNull('level')
            ->orWhere('level', 0)
            ->orWhereNull('attachment_file');
        });
    }

    public function getInvalidItemsQuestionByUnit()
    {
        return $this->hasMany(
            \App\Models\SignQuestionAssesment::class,
            'assesment_id'
        )->where(function ($q) {
            $q->whereNull('evaluation_unit')
            ->orWhere('evaluation_unit', 0);
        });
    }

    public function statusHistoryCreatedLabel(): string
    {
        return 'Assessment dibuat (Input BUJP)';
    }

    public function statusHistoryChanges(): array
    {
        if (!$this->wasChanged('send_status')) {
            return [];
        }

        $from = $this->getOriginal('send_status');
        $to   = (int) $this->send_status;
        $labels = [
            0 => 'Dikembalikan ke Input BUJP',
            1 => 'Dikirim BUJP ke Unit (pengecekan Unit)',
            2 => 'Diterima Unit dan dikirim ke Pusat',
            3 => 'Revisi diminta Unit (kembali ke BUJP)',
        ];

        return [['send_status', $labels[$to] ?? 'Status berubah menjadi ' . $to, $from === null ? null : (int) $from, $to]];
    }
}
