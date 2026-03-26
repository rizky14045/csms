<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\User\UserService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index(){
        $data = [];
        if(auth()->user()->hasRole('BUJP')) {
            $results = $this->userService->getAllUnitByVendorID(0, false, auth()->user()->id);
            $data['units'] = getData($results);
        }
        return view('dashboard.dashboard', $data);
    }


    public function getMonthlyReport(){

        $users = User::where('type','unit')->get();
    }
}
