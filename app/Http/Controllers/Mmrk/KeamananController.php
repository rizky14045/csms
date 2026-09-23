<?php

namespace App\Http\Controllers\Mmrk;

use App\Helpers\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\Kpi;
use App\Services\Kpi\KpiService;
use RealRashid\SweetAlert\Facades\Alert;

class KeamananController extends Controller
{
    protected $kpiService;

    public function __construct(KpiService $kpiService)
    {
        $this->kpiService = $kpiService;

        $this->middleware('can:view.kpi.mmrk')->only(['index', 'show']);
        $this->middleware('can:send.kpi.mmrk')->only(['send']);
    }

    protected function ownedOrFail(Kpi $kpi)
    {
        if ($kpi->unit_id !== auth()->user()->unit_id || (int) $kpi->status < 1) {
            abort(404);
        }
    }

    public function index()
    {
        $paginator = Kpi::with('unit')
            ->where('unit_id', auth()->user()->unit_id)
            ->where('status', '>=', 1)
            ->orderByDesc('id')
            ->paginate(10);

        $data['kpis'] = getPaginate(JsonResponse::success($paginator, 'Kpi found', 200));

        return view('mmrk.keamanan.index', $data);
    }

    public function show(Kpi $kpi)
    {
        $this->ownedOrFail($kpi);

        $result = $this->kpiService->getAllKpiArea(0, false, $kpi->id, ['subAreas', 'subAreas.levels']);

        $data['areas']   = getData($result);
        $data['kpi']     = $kpi;
        $data['mode']    = 'mmrk';
        $data['checked'] = [];
        $data['actual']  = ['subAreas' => [], 'total' => 0];
        $data['backUrl'] = route('mmrk.keamanan.index');

        return view('admin.keamanan.show', $data);
    }

    public function send(Kpi $kpi)
    {
        $this->ownedOrFail($kpi);

        if ($this->kpiService->sendToPusat($kpi)) {
            Alert::success('Berhasil Dikirim', 'KPI berhasil dikirim ke Pusat!');
        } else {
            Alert::error('Gagal', 'KPI sudah dikirim atau belum dikirim oleh Unit!');
        }

        return redirect()->route('mmrk.keamanan.index');
    }
}
