<?php

namespace App\Http\Controllers\Mmrk;

use App\Helpers\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\Marturity;
use App\Services\Marturity\MarturityService;
use RealRashid\SweetAlert\Facades\Alert;

class MarturityController extends Controller
{
    protected $marturityService;

    public function __construct(MarturityService $marturityService)
    {
        $this->marturityService = $marturityService;

        $this->middleware('can:view.marturity.mmrk')->only(['index', 'show']);
        $this->middleware('can:send.marturity.mmrk')->only(['send']);
    }

    protected function ownedOrFail(Marturity $marturity)
    {
        if ($marturity->unit_id !== auth()->user()->unit_id || (int) $marturity->status < 1) {
            abort(404);
        }
    }

    public function index()
    {
        $paginator = Marturity::with('unit')
            ->where('unit_id', auth()->user()->unit_id)
            ->where('status', '>=', 1)
            ->orderByDesc('id')
            ->paginate(10);

        $data['marturities'] = getPaginate(JsonResponse::success($paginator, 'Marturity found', 200));

        return view('mmrk.marturity.index', $data);
    }

    public function show(Marturity $marturity)
    {
        $this->ownedOrFail($marturity);

        $result = $this->marturityService->getAlMarturityArea(['subAreas', 'subAreas.levels'], $marturity->id);

        $data['areas']     = getData($result);
        $data['marturity'] = $marturity;
        $data['mode']      = 'mmrk';
        $data['checked']   = [];
        $data['actual']    = ['subAreas' => [], 'total' => 0];
        $data['backUrl']   = route('mmrk.marturity.index');

        return view('admin.marturity.show', $data);
    }

    public function send(Marturity $marturity)
    {
        $this->ownedOrFail($marturity);

        if ($this->marturityService->sendToPusat($marturity)) {
            Alert::success('Berhasil Dikirim', 'Maturity berhasil dikirim ke Pusat!');
        } else {
            Alert::error('Gagal', 'Maturity sudah dikirim atau belum dikirim oleh Unit!');
        }

        return redirect()->route('mmrk.marturity.index');
    }
}
