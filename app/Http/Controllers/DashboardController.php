<?php

namespace App\Http\Controllers;

use App\Models\SecurityForm;
use App\Models\Unit;
use App\Models\User;
use App\Services\User\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index(Request $request)
    {
        $month = $request->month ?? date('m');
        $year  = $request->year ?? date('Y');

        $monthlyReports = DB::table('monthly_reports')
            ->select('id', 'user_id')
            ->when($month, function ($q) use ($month) {
                $q->whereMonth('created_at', $month);
            })
            ->when($year, function ($q) use ($year) {
                $q->whereYear('created_at', $year);
            });

        $users = User::where('type', 'user')
            ->leftJoin('user_profiles', 'user_profiles.user_id', '=', 'users.id')
            ->leftJoinSub($monthlyReports, 'mr', function ($join) {
                $join->on('mr.user_id', '=', 'users.id');
            })
            ->leftJoin(DB::raw('(
                SELECT monthly_report_id, COUNT(*) as total_satpam 
                FROM security_forms 
                GROUP BY monthly_report_id
            ) as sf'), 'sf.monthly_report_id', '=', 'mr.id')

            ->select(
                'users.name',
                'user_profiles.latitude',
                'user_profiles.longitude',
                DB::raw('CASE WHEN mr.id IS NOT NULL THEN 1 ELSE 0 END as status_report'),
                DB::raw('COALESCE(sf.total_satpam, 0) as satpam')
            )
            ->get();

            
        $units = [];
         if(auth()->user()->hasRole('BUJP')) {
            $results = $this->userService->getAllUnitByVendorID(0, false, auth()->user()->id);
            $units = getData($results);
        }

        $isCentral = auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Pusat');

        $mapUnits = [];
        if ($isCentral) {
            $mapUnits = $this->getUnitMapData($month, $year);
        }

        return view('dashboard.dashboard', [
            'users' => $users,
            'month' => $month,
            'year'  => $year,
            'units' => $units,
            'isCentral' => $isCentral,
            'mapUnits' => $mapUnits,
        ]);
    }

    protected function getUnitMapData($month, $year)
    {
        // Laporan milik UL dihitung sebagai milik unit induknya, sehingga
        // data yang tampil di peta adalah total gabungan Unit + seluruh UL-nya.
        $unitMonthlyReports = DB::table('monthly_reports as mr_src')
            ->join('units as ru', 'ru.id', '=', 'mr_src.unit_id')
            ->select(
                'mr_src.id',
                DB::raw("CASE WHEN ru.type = 'UL' THEN ru.parent_unit_id ELSE ru.id END as unit_id")
            )
            ->when($month, function ($q) use ($month) {
                $q->whereMonth('mr_src.created_at', $month);
            })
            ->when($year, function ($q) use ($year) {
                $q->whereYear('mr_src.created_at', $year);
            });

        $activeVendors = DB::table('vendors')
            ->select('unit_id', DB::raw("STRING_AGG(DISTINCT name, ', ') as active_vendor_names"))
            ->whereNull('deleted_at')
            ->whereNotNull('unit_id')
            ->when($month && $year, function ($q) use ($month, $year) {
                $monthStart = \Carbon\Carbon::createFromDate($year, $month, 1)->startOfMonth()->toDateString();
                $monthEnd = \Carbon\Carbon::createFromDate($year, $month, 1)->endOfMonth()->toDateString();

                $q->where(function ($sub) use ($monthEnd) {
                    $sub->whereNull('start_date')->orWhere('start_date', '<=', $monthEnd);
                })->where(function ($sub) use ($monthStart) {
                    $sub->whereNull('end_date')->orWhere('end_date', '>=', $monthStart);
                });
            })
            ->groupBy('unit_id');

        return Unit::query()
            ->where(function ($q) {
                $q->whereNull('type')->orWhere('type', '!=', 'UL');
            })
            ->leftJoinSub($unitMonthlyReports, 'mr', function ($join) {
                $join->on('mr.unit_id', '=', 'units.id');
            })
            ->leftJoinSub($activeVendors, 'av', function ($join) {
                $join->on('av.unit_id', '=', 'units.id');
            })
            ->leftJoin(DB::raw('(
                SELECT monthly_report_id, SUM(employee_man + employee_woman) as total_karyawan
                FROM report_employees
                GROUP BY monthly_report_id
            ) as re'), 're.monthly_report_id', '=', 'mr.id')
            ->leftJoin(DB::raw('(
                SELECT monthly_report_id, COUNT(*) as total_satpam
                FROM security_forms
                WHERE deleted_at IS NULL
                GROUP BY monthly_report_id
            ) as sf'), 'sf.monthly_report_id', '=', 'mr.id')
            ->leftJoin(DB::raw('(
                SELECT monthly_report_id, COUNT(*) as total
                FROM internal_vulnerabilities
                WHERE deleted_at IS NULL
                GROUP BY monthly_report_id
            ) as iv'), 'iv.monthly_report_id', '=', 'mr.id')
            ->leftJoin(DB::raw('(
                SELECT monthly_report_id, COUNT(*) as total
                FROM external_vulnerabilities
                WHERE deleted_at IS NULL
                GROUP BY monthly_report_id
            ) as ev'), 'ev.monthly_report_id', '=', 'mr.id')
            ->select(
                'units.id',
                'units.name',
                'units.latitude',
                'units.longitude',
                'av.active_vendor_names',
                DB::raw('MAX(CASE WHEN mr.id IS NOT NULL THEN 1 ELSE 0 END) as status_report'),
                DB::raw('COALESCE(SUM(re.total_karyawan), 0) as total_karyawan'),
                DB::raw('COALESCE(SUM(sf.total_satpam), 0) as total_satpam'),
                DB::raw('COALESCE(SUM(iv.total), 0) + COALESCE(SUM(ev.total), 0) as total_kerawanan')
            )
            ->groupBy('units.id', 'units.name', 'units.latitude', 'units.longitude', 'av.active_vendor_names')
            ->get();
    }
}
