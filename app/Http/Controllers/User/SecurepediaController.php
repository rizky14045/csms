<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\Securepedia\SecurepediaService;
use Illuminate\Http\Request;

class SecurepediaController extends Controller
{
    protected $securepediaService;

    public function __construct(SecurepediaService $securepediaService)
    {
        $this->securepediaService = $securepediaService;
        $this->middleware('can:view.securepedia.user')->only(['index']);
    }

    public function index(Request $request)
    {
        $user     = auth()->user();
        $roleName = $user->roles[0]->name ?? null;

        // BUJP hanya bisa melihat data External
        $type = ($roleName === 'BUJP') ? 'External' : null;

        $result = $this->securepediaService->getAllSecurepedia(25, true, $type);

        $data['securepedias'] = getPaginate($result);
        $data['request']      = $request;

        return view('user.securepedia.index', $data);
    }
}
