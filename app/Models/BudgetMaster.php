<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BudgetMaster extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    /** Kolom yang disalin antara master <-> baris laporan. */
    public const FIELDS = ['type', 'kode_aktifitas', 'kode_prk', 'deskripsi_kegiatan', 'jumlah_anggaran', 'penyerapan_anggaran', 'keterangan'];

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }
}
