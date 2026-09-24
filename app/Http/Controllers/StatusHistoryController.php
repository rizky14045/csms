<?php

namespace App\Http\Controllers;

use App\Models\StatusHistory;
use App\Services\Unit\UnitScope;

class StatusHistoryController extends Controller
{
    public function show($type, $id)
    {
        $class = StatusHistory::SUBJECTS[$type] ?? abort(404);
        $subject = $class::findOrFail($id);
        $user = auth()->user();

        // Unit/UL hanya boleh melihat riwayat data unitnya sendiri (dan UL-nya).
        if (!$user->hasAnyRole(['Pusat', 'Admin', 'MMRK', 'BUJP', 'Auditor'])
            && !in_array((int) $subject->unit_id, UnitScope::visibleUnitIds($user), true)) {
            abort(403);
        }

        $rows = $subject->statusHistories()->get()->map(fn($h) => [
            'label' => $h->label,
            'user'  => $h->user_name ?: '-',
            'role'  => $h->user_role,
            'time'  => optional($h->created_at)->format('d-m-Y H:i:s'),
        ]);

        return response()->json(['data' => $rows]);
    }
}
