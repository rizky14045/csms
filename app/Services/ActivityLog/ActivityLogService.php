<?php
namespace App\Services\ActivityLog;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogService
{
    public function log(
        string $type,
        string $activity,
        int $statusCode,
        array $properties = []
    ) {
        return ActivityLog::create([
            'type'            => $type,
            'error_code'      => $statusCode,
            'time'            => now(),
            'ip'              => request()->ip(),
            'user_id'         => auth()->id(),
            'activity'        => $activity,
            'browser'         => request()->header('User-Agent'),
            'os'              => php_uname('s'),
            'properties_data' => $properties,
        ]);
    }
}

