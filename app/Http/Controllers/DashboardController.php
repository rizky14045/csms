<?php

namespace App\Http\Controllers;

use App\Models\SecurityForm;
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

        return view('dashboard.dashboard', [
            'users' => $users,
            'month' => $month,
            'year'  => $year,
            'units' => $units
        ]);
    }
}
