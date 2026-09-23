<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AghtData;
use App\Models\AgreementExternal;
use App\Models\ExternalVulnerability;
use App\Models\ForeignWorker;
use App\Models\FormAttribute;
use App\Models\InternalVulnerability;
use App\Models\LaporanBulananBiaya;
use App\Models\MonthlyAgreementExternal;
use App\Models\MonthlyGangguan;
use App\Models\MonthlyReport;
use App\Models\MonthlyResponsiblePerson;
use App\Models\MonthlySecurityExternal;
use App\Models\MonthlySecurityProgram;
use App\Models\OutsourceEmployee;
use App\Models\ReportEmployee;
use App\Models\ResponsiblePerson;
use App\Models\SecurityExternal;
use App\Models\SecurityForm;
use App\Services\MonthlyReport\UlAggregationService;
use App\Services\Unit\UnitService;
use Illuminate\Http\Request;

class MonthlyAuditController extends Controller
{
    protected $unitService;
    protected $ulAggregationService;

    public function __construct(UnitService $unitService, UlAggregationService $ulAggregationService)
    {
        $this->unitService = $unitService;
        $this->ulAggregationService = $ulAggregationService;
    }

    public function index(Request $request)
    {
        $query = MonthlyReport::query();

        // ===============================
        // FILTER DEFAULT
        // ===============================
        // laporan terkirim dari semua unit + laporan milik user yang sedang login (termasuk draft)
        $query->where(function ($q) {
            $q->where('send_status', true)->orWhere('user_id', auth()->id());
        });

        if ($request->month) {
            $query->where('report_date', $request->month);
        }

        // ===============================
        // 🔥 FILTER UNIT CODE (RELASI)
        // ===============================
        if ($request->unit_code) {
            $query->whereHas('detailUnit', function ($q) use ($request) {
                $q->where('unit_code', $request->unit_code);
            });
        }

        // ===============================
        // RESULT
        // ===============================
        $data['forms'] = $query
            ->with('detailUnit')
            ->latest()
            ->paginate(25);

        $result = $this->unitService->getAllUnit(0, false);
        $data['all_units'] = getData($result);

        $data['request'] = $request->all();

        return view('admin.monthly-audit.index', $data);
    }
    
    public function show($monthlyId){

        $ownReport = MonthlyReport::findOrFail($monthlyId);
        $reportIds = $this->ulAggregationService->reportIdsFor($ownReport);

        $data['monthlyId'] = $monthlyId;
        $data['monthlyReport'] = $ownReport;

        $employees = ReportEmployee::whereIn('monthly_report_id', $reportIds)->get();
        $data['employee'] = (object) [
            'employee_man' => $employees->sum('employee_man'),
            'employee_woman' => $employees->sum('employee_woman'),
            'student_man' => $employees->sum('student_man'),
            'student_woman' => $employees->sum('student_woman'),
        ];

        $gangguanRows = MonthlyGangguan::whereIn('monthly_report_id', $reportIds)->get();
        $gangguan = (object) [
            'kriminal' => $gangguanRows->sum('kriminal'),
            'politis' => $gangguanRows->sum('politis'),
            'kebakaran' => $gangguanRows->sum('kebakaran'),
            'bencana_alam' => $gangguanRows->sum('bencana_alam'),
            'other' => $gangguanRows->sum('other'),
        ];
        $data['gangguan'] = $gangguan;
        $data['outsources'] = OutsourceEmployee::whereIn('monthly_report_id', $reportIds)->latest()->get();
        $security = SecurityForm::join('securities', 'security_forms.security_id','securities.id')->whereNull('securities.deleted_at')->whereIn('monthly_report_id', $reportIds);
        $data['securityKomandan'] = (clone $security)->where('securities.position', 'Komandan')->get()->count();
        $data['securityAnggota'] = (clone $security)->where('securities.position', 'Anggota')->get()->count();
        $data['securityChief'] = (clone $security)->where('securities.position', 'Chief')->get()->count();
        $data['security'] = (clone $security)->get()->count();
        $securityExternal = MonthlySecurityExternal::join('security_externals','security_externals.id','monthly_security_externals.security_external_id')->whereNull('security_externals.deleted_at')->whereIn('monthly_report_id', $reportIds);
        $data['securityPolri'] = (clone $securityExternal)->where('note', 'Polri')->get()->count();
        $data['securityTNI'] = (clone $securityExternal)->where('note', 'TNI')->get()->count();
        $data['securityExternal'] = (clone $securityExternal)->get()->count();

        $foreign = ForeignWorker::whereIn('monthly_report_id', $reportIds);
        $data['foreignAhli'] = (clone $foreign)->where('position', 'Tenaga Ahli')->get()->count();
        $data['foreignStaff'] = (clone $foreign)->where('position', 'staff')->get()->count();
        $data['foreign'] = (clone $foreign)->get()->count();

         /*
        |--------------------------------------------------------------------------
        | Total All
        |--------------------------------------------------------------------------
        */
        $total = 
            ($data['employee']->employee_man ?? 0) +
            ($data['employee']->employee_woman ?? 0) +
            ($data['employee']->student_man ?? 0) +
            ($data['employee']->student_woman ?? 0) +
            $data['outsources']->sum('total') +
            $data['securityExternal'] +
            $data['foreign'];

        /*
        |--------------------------------------------------------------------------
        | Total Man
        |--------------------------------------------------------------------------
        */
        $totalMan =
            ($data['employee']->employee_man ?? 0) +
            ($data['employee']->student_man ?? 0) +
            $data['outsources']->sum('man') +

            (clone $securityExternal)
                ->where('note', 'TNI')
                ->where('gender', 'Pria')
                ->count() +

            (clone $securityExternal)
                ->where('note', 'Polri')
                ->where('gender', 'Pria')
                ->count() +

            $security->where('gender', 'Pria')->count();

        /*
        |--------------------------------------------------------------------------
        | Total Woman
        |--------------------------------------------------------------------------
        */
        $totalWoman =
            ($data['employee']->employee_woman ?? 0) +
            ($data['employee']->student_woman ?? 0) +
            $data['outsources']->sum('woman') +

            (clone $securityExternal)
                ->where('note', 'TNI')
                ->where('gender', 'Wanita')
                ->count() +

            (clone $securityExternal)
                ->where('note', 'Polri')
                ->where('gender', 'Wanita')
                ->count() +

        $security->where('gender', 'Wanita')->count();

        $data['totalAll'] = $total;
        $data['totalAllMan'] = $totalMan;
        $data['totalAllWoman'] = $totalWoman;
        

        $data['persons'] = MonthlyResponsiblePerson::with('person')->whereIn('monthly_report_id', $reportIds)->get();
        $data['securities'] = MonthlySecurityExternal::with('security')->whereIn('monthly_report_id', $reportIds)->get();
        $data['agreements'] = MonthlyAgreementExternal::with('agreement')->whereIn('monthly_report_id', $reportIds)->get();

        $data['securityForms'] = SecurityForm::with('security')->whereIn('monthly_report_id', $reportIds)->get();

        $data['aghts'] = AghtData::whereIn('monthly_report_id', $reportIds)->get();

        $attribute = FormAttribute::join('attributes', 'attributes.id', 'form_attributes.attribute_id')
        ->select('form_attributes.*', 'attributes.name', 'attributes.status_ownership', 'attributes.unit', 'attributes.standard_contract')->whereIn('monthly_report_id', $reportIds);

        $data['attributes'] = (clone $attribute)->where('attributes.type_attribute', 'Attribute')->get();
        $data['administrations'] = (clone $attribute)->where('attributes.type_attribute', 'Administrasi')->get();
        $data['saranas'] = (clone $attribute)->where('attributes.type_attribute', 'Sarana')->get();
        $data['foreignWorkers'] = ForeignWorker::whereIn('monthly_report_id', $reportIds)->get();
        $data['programs'] = MonthlySecurityProgram::with('securityProgram','programs')->whereIn('monthly_report_id', $reportIds)->get();

        $data['internals'] = InternalVulnerability::with('vulnerability')->whereIn('monthly_report_id', $reportIds)->get();
        $data['externals'] = ExternalVulnerability::with('vulnerability')->whereIn('monthly_report_id', $reportIds)->get();
        $dataBiaya = LaporanBulananBiaya::whereIn('monthly_report_id', $reportIds)
            ->whereIn('type', ['administrasi', 'pemeliharaan'])
            ->get()
            ->map(function ($item) {
                $item->prosentase_penyerapan = $item->jumlah_anggaran != 0
                    ? ($item->penyerapan_anggaran / $item->jumlah_anggaran) * 100
                    : 0;
                return $item;
            })
            ->groupBy('type');

        $data['administrasi'] = $dataBiaya->get('administrasi', collect());
        $data['pemeliharaan'] = $dataBiaya->get('pemeliharaan', collect());
        return view('admin.monthly-audit.show',$data);
    }

}
