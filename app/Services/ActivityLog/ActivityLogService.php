<?php

namespace App\Services\ActivityLog;

use App\Models\ActivityLog;

class ActivityLogService
{
    public function log(
        string $type,
        string $activity,
        int $statusCode,
        array $properties = []
    ) {
        $request = request();

        return ActivityLog::create([
            'type'            => $type,
            'method'          => $request->method(),   // GET, POST, PUT, DELETE
            'url'             => $request->fullUrl(),  // Full URL
            'error_code'      => $statusCode,
            'time'            => now(),
            'ip'              => $request->ip(),
            'user_id'         => auth()->id(),
            'activity'        => $activity,
            'browser'         => $request->header('User-Agent'),
            'os'              => php_uname('s'),
            'properties_data' => $properties,
        ]);
    }
}
