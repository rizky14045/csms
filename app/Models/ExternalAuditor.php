<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExternalAuditor extends Model
{
    use SoftDeletes;

    public const ROLE = 'Auditor External';

    protected $guarded = ['id'];

    protected $casts = ['expired_at' => 'date'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->withTrashed();
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function audits()
    {
        return $this->belongsToMany(AuditSmpData::class, 'external_auditor_audits', 'user_id', 'audit_smp_data_id', 'user_id', 'id');
    }

    /** Berlaku sampai akhir hari tanggal kedaluwarsa. */
    public function isExpired(): bool
    {
        return $this->expired_at->endOfDay()->isPast();
    }

    public static function forUser($user): ?self
    {
        return $user ? static::where('user_id', $user->id)->first() : null;
    }
}
