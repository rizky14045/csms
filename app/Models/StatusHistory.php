<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StatusHistory extends Model
{
    public $timestamps = false;

    protected $guarded = ['id'];

    protected $casts = ['created_at' => 'datetime'];

    /** slug URL => class model yang punya riwayat status */
    public const SUBJECTS = [
        'monthly-report' => MonthlyReport::class,
        'assesment'      => Assesment::class,
        'marturity'      => Marturity::class,
        'kpi'            => Kpi::class,
        'audit-smp'      => AuditSmpData::class,
    ];

    public static function record($subject, string $event, string $label, $from = null, $to = null): void
    {
        $user = auth()->user();

        static::create([
            'subject_type' => get_class($subject),
            'subject_id'   => $subject->getKey(),
            'event'        => $event,
            'label'        => $label,
            'from_status'  => $from,
            'to_status'    => $to,
            'user_id'      => $user->id ?? null,
            'user_name'    => $user->name ?? null,
            'user_role'    => $user ? $user->getRoleNames()->first() : null,
            'created_at'   => now(),
        ]);
    }
}
