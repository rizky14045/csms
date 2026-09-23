<?php

namespace App\Http\Controllers\Mmrk;

use App\Http\Controllers\Controller;
use App\Models\MonthlyReport;
use Illuminate\Http\Request;

class MonthlyAuditController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:view.monthly.audit.mmrk');
    }

    public function index(Request $request)
    {
        $query = MonthlyReport::where('unit_id', auth()->user()->unit_id);

        if ($request->month) {
            $query->where('report_date', $request->month);
        }

        $data['forms'] = $query->with('detailUnit')->latest()->paginate(25);
        $data['request'] = $request->all();

        return view('mmrk.monthly-audit.index', $data);
    }

    public function show($monthlyId)
    {
        $report = MonthlyReport::findOrFail($monthlyId);

        if ($report->unit_id !== auth()->user()->unit_id) {
            abort(404);
        }

        return app(\App\Http\Controllers\Admin\MonthlyAuditController::class)
            ->show($monthlyId)
            ->with('backUrl', route('mmrk.monthly-audit.index'));
    }
}
