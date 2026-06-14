<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\Securepedia\SecurepediaService;
use App\Services\User\UserService;
use Illuminate\Http\Request;

class SecurepediaController extends Controller
{
    protected $securepediaService, $userService;

public function __construct(SecurepediaService $securepediaService, UserService $userService)
    {
        $this->securepediaService = $securepediaService;
        $this->userService = $userService;
        $this->middleware('can:view.securepedia.user')->only(['index']);
    }

    public function index(Request $request)
    {
        $units = [];
         if(auth()->user()->hasRole('BUJP')) {
            $results = $this->userService->getAllUnitByVendorID(0, false, auth()->user()->id);
            $units = getData($results);
        }
        $user     = auth()->user();
        $roleName = $user->roles[0]->name ?? null;

        // BUJP hanya bisa melihat data External
        $type = ($roleName === 'BUJP') ? 'External' : null;

        $result = $this->securepediaService->getAllSecurepedia(25, true, $type);

        $data['securepedias'] = getPaginate($result);
        $data['request']      = $request;
        $data['units']        = $units;

        return view('user.securepedia.index', $data);
    }
}
