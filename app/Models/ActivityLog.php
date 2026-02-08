<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'error_code',
        'time',
        'ip',
        'user_id',
        'activity',
        'browser',
        'os',
        'properties_data',
    ];

    protected $casts = [
        'properties_data' => 'array',
        'time' => 'datetime',
    ];
}
