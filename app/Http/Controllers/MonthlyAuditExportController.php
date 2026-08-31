<?php

namespace App\Http\Controllers;

use App\Exports\AghtExport;
use App\Exports\BudgetAbsorptionExport;
use App\Exports\ForeignWorkerExport;
use App\Exports\FormAttributeExport;
use App\Exports\FormFormulirExport;
use App\Exports\MonthlyAuditAllExport;
use App\Exports\SecurityFormExport;
use App\Exports\SecurityProgramExport;
use App\Exports\VulnerabilityExternalExport;
use App\Exports\VulnerabilityInternalExport;
use App\Exports\WorkerSumExport;
use App\Models\AghtData;
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
use App\Models\SecurityForm;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class MonthlyAuditExportController extends Controller
{
    public function exportFormFormulir($monthlyId)
    {
        // ambil data (copy dari index kamu)
        $data['monthlyId'] = $monthlyId;

        $data['monthlyReport'] = MonthlyReport::where('id', $monthlyId)
            ->select('report_date')
            ->first();

        $data['employee'] = ReportEmployee::where('monthly_report_id', $monthlyId)->first();

        $data['outsources'] = OutsourceEmployee::where('monthly_report_id', $monthlyId)->get();

        $securities = SecurityForm::join('securities','securities.id','=','security_forms.security_id')
            ->where('security_forms.monthly_report_id', $monthlyId)
            ->select('securities.*')
            ->get();

        $data['securities'] = $securities;

        $securityExternal = MonthlySecurityExternal::join(
            'security_externals',
            'security_externals.id',
            '=',
            'monthly_security_externals.security_external_id'
        )->where('monthly_security_externals.monthly_report_id', $monthlyId);

        $data['securityPolri'] = (clone $securityExternal)->where('note','Polri')->count();
        $data['securityTNI'] = (clone $securityExternal)->where('note','TNI')->count();
        $data['securityExternal'] = (clone $securityExternal)->count();

        $foreign = ForeignWorker::where('monthly_report_id', $monthlyId);

        $data['foreignAhli'] = (clone $foreign)->where('position','Tenaga Ahli')->count();
        $data['foreignStaff'] = (clone $foreign)->where('position','staff')->count();
        $data['foreign'] = (clone $foreign)->count();

        $data['gangguan'] = MonthlyGangguan::where('monthly_report_id', $monthlyId)->first();

        $data['totalAll'] =
            ($data['employee']->employee_man ?? 0) +
            ($data['employee']->employee_woman ?? 0) +
            ($data['employee']->student_man ?? 0) +
            ($data['employee']->student_woman ?? 0) +
            $data['outsources']->sum('total') +
            $data['securityExternal'] +
            $data['foreign'];

        $data['totalAllMan'] =
            ($data['employee']->employee_man ?? 0) +
            ($data['employee']->student_man ?? 0) +
            $data['outsources']->sum('man');

        $data['totalAllWoman'] =
            ($data['employee']->employee_woman ?? 0) +
            ($data['employee']->student_woman ?? 0) +
            $data['outsources']->sum('woman');

        return Excel::download(new FormFormulirExport($data), 'Form-Formulir.xlsx');
    }
    public function exportWorkerSum($monthlyId)
    {
        $monthly = MonthlyReport::findOrFail($monthlyId);
        $data['monthlyReport'] = $monthly;
        
        // Pastikan relasi 'person', 'security', dan 'agreement' ada di database
        $data['persons'] = MonthlyResponsiblePerson::with('person')
            ->whereHas('person') 
            ->where('monthly_report_id', $monthlyId)
            ->get();
            
        $data['securities'] = MonthlySecurityExternal::with('security')
            ->whereHas('security')
            ->where('monthly_report_id', $monthlyId)
            ->get();
            
        $data['agreements'] = MonthlyAgreementExternal::with('agreement')
            ->whereHas('agreement')
            ->where('monthly_report_id', $monthlyId)
            ->get();

        return Excel::download(new WorkerSumExport($data),'Form-Formulir.xlsx');
    }

    public function exportFormSecurity($monthlyId)
    {
        $monthly = MonthlyReport::findOrFail($monthlyId);

        $data['monthlyReport'] = $monthly;
        $data['monthly'] = $monthly;

        $data['forms'] = SecurityForm::with('security')
            ->where('monthly_report_id', $monthlyId)
            ->get();

        return Excel::download(
            new SecurityFormExport($data),
            'Form-Personil-Satpam.xlsx'
        );
    }
    public function exportAght($monthlyId)
    {
        $monthly = MonthlyReport::findOrFail($monthlyId);
        $data['monthlyReport'] = $monthly;

        $data['monthly'] = $monthly;

        $data['aghts'] = AghtData::where(
            'monthly_report_id',
            $monthlyId
        )->get();

        return Excel::download(
            new AghtExport($data),
            'Data-AGHT.xlsx'
        );
    }
    public function exportFormAttribute($monthlyId)
    {
        $monthly = MonthlyReport::findOrFail($monthlyId);
        $data['monthlyReport'] = $monthly;

        $query = FormAttribute::join(
                'attributes',
                'attributes.id',
                '=',
                'form_attributes.attribute_id'
            )
            ->select(
                'form_attributes.*',
                'attributes.name',
                'attributes.status_ownership',
                'attributes.unit',
                'attributes.standard_contract'
            )
            ->where('monthly_report_id', $monthlyId);

        $data['monthly'] = $monthly;

        $data['attributes'] = (clone $query)
            ->where('attributes.type_attribute', 'Attribute')
            ->get();

        $data['administrations'] = (clone $query)
            ->where('attributes.type_attribute', 'Administrasi')
            ->get();

        $data['saranas'] = (clone $query)
            ->where('attributes.type_attribute', 'Sarana')
            ->get();

        return Excel::download(new FormAttributeExport($data),'Form-Atribut-Peralatan.xlsx');
    }
    public function exportForeignWorker($monthlyId)
    {
        $monthly = MonthlyReport::findOrFail($monthlyId);
        $data['monthlyReport'] = $monthly;

        $data['monthlyId'] = $monthlyId;

        $data['foreigns'] = ForeignWorker::where(
            'monthly_report_id',
            $monthlyId
        )->get();

        return Excel::download(
            new ForeignWorkerExport($data),
            'foreign-worker.xlsx'
        );
    }
    public function exportSecurityProgram($monthlyId)
    {
        $monthly = MonthlyReport::findOrFail($monthlyId);
        $data['monthlyReport'] = $monthly;

        $data['monthly'] = $monthly;

        $data['programs'] = MonthlySecurityProgram::with(
            'securityProgram',
            'programs.mainProgram'
        )
        ->where('monthly_report_id', $monthlyId)
        ->get();

        return Excel::download(
            new SecurityProgramExport($data),
            'Program-Keamanan.xlsx'
        );
    }
    public function exportVulnerabilityInternal($monthlyId)
    {
        $monthly = MonthlyReport::findOrFail($monthlyId);
        $data['monthlyReport'] = $monthly;

        $data['internals'] = InternalVulnerability::with('vulnerability')
            ->where('monthly_report_id', $monthlyId)
            ->get();

        return Excel::download(
            new VulnerabilityInternalExport($data),
            'Kerawanan-Internal.xlsx'
        );
    }
    public function exportVulnerabilityExternal($monthlyId)
    {
        $monthly = MonthlyReport::findOrFail($monthlyId);
        $data['monthlyReport'] = $monthly;

        $data['externals'] = ExternalVulnerability::with('vulnerability')
            ->where('monthly_report_id', $monthlyId)
            ->get();

        return Excel::download(
            new VulnerabilityExternalExport($data),
            'Kerawanan-Eksternal.xlsx'
        );
    }
    public function exportBudgetAbsorption($monthlyId)
    {
        $monthly = MonthlyReport::findOrFail($monthlyId);
        $data['monthlyReport'] = $monthly;
        
        $dataBiaya = LaporanBulananBiaya::where('monthly_report_id', $monthlyId)
            ->whereIn('type', ['administrasi', 'pemeliharaan'])
            ->get()
            ->map(function ($item) {

                $item->prosentase_penyerapan =
                    $item->jumlah_anggaran != 0
                    ? ($item->penyerapan_anggaran / $item->jumlah_anggaran) * 100
                    : 0;

                return $item;
            })
            ->groupBy('type');

        $data['administrasi'] = $dataBiaya->get('administrasi', collect());
        $data['pemeliharaan'] = $dataBiaya->get('pemeliharaan', collect());

        return Excel::download(
            new BudgetAbsorptionExport($data),
            'Penyerapan-Anggaran.xlsx'
        );
    }

   public function exportAll($monthlyId)
    {
        /*
        |--------------------------------------------------------------------------
        | FORM FORMULIR
        |--------------------------------------------------------------------------
        */

        $data['monthlyId'] = $monthlyId;

        $data['monthlyReport'] = MonthlyReport::where('id', $monthlyId)
            ->select('report_date')
            ->first();

        $data['employee'] = ReportEmployee::where(
            'monthly_report_id',
            $monthlyId
        )->first();

        $data['outsources'] = OutsourceEmployee::where(
            'monthly_report_id',
            $monthlyId
        )->get();

        $securities = SecurityForm::join(
            'securities',
            'securities.id',
            '=',
            'security_forms.security_id'
        )
        ->where('security_forms.monthly_report_id', $monthlyId)
        ->select('securities.*')
        ->get();

        $data['securitiesForm'] = $securities;

        $securityExternal = MonthlySecurityExternal::join(
            'security_externals',
            'security_externals.id',
            '=',
            'monthly_security_externals.security_external_id'
        )->where('monthly_security_externals.monthly_report_id', $monthlyId);

        $data['securityPolri'] = (clone $securityExternal)
            ->where('note', 'Polri')
            ->count();

        $data['securityTNI'] = (clone $securityExternal)
            ->where('note', 'TNI')
            ->count();

        $data['securityExternal'] = (clone $securityExternal)->count();

        $foreign = ForeignWorker::where(
            'monthly_report_id',
            $monthlyId
        );

        $data['foreignAhli'] = (clone $foreign)
            ->where('position', 'Tenaga Ahli')
            ->count();

        $data['foreignStaff'] = (clone $foreign)
            ->where('position', 'staff')
            ->count();

        $data['foreign'] = (clone $foreign)->count();

        $data['gangguan'] = MonthlyGangguan::where(
            'monthly_report_id',
            $monthlyId
        )->first();

        /*
        |--------------------------------------------------------------------------
        | TOTAL
        |--------------------------------------------------------------------------
        */

        $data['totalAll'] =
            ($data['employee']->employee_man ?? 0) +
            ($data['employee']->employee_woman ?? 0) +
            ($data['employee']->student_man ?? 0) +
            ($data['employee']->student_woman ?? 0) +
            $data['outsources']->sum('total') +
            $data['securityExternal'] +
            $data['foreign'];

        $data['totalAllMan'] =
            ($data['employee']->employee_man ?? 0) +
            ($data['employee']->student_man ?? 0) +
            $data['outsources']->sum('man');

        $data['totalAllWoman'] =
            ($data['employee']->employee_woman ?? 0) +
            ($data['employee']->student_woman ?? 0) +
            $data['outsources']->sum('woman');

        /*
        |--------------------------------------------------------------------------
        | WORKER SUMMARY
        |--------------------------------------------------------------------------
        */

        $data['persons'] = MonthlyResponsiblePerson::with('person')
            ->whereHas('person')
            ->where('monthly_report_id', $monthlyId)
            ->get();

        $data['securities'] = MonthlySecurityExternal::with('security')
            ->whereHas('security')
            ->where('monthly_report_id', $monthlyId)
            ->get();

        $data['agreements'] = MonthlyAgreementExternal::with('agreement')
            ->whereHas('agreement')
            ->where('monthly_report_id', $monthlyId)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | SECURITY FORM
        |--------------------------------------------------------------------------
        */

        $data['forms'] = SecurityForm::with('security')
            ->where('monthly_report_id', $monthlyId)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | AGHT
        |--------------------------------------------------------------------------
        */

        $data['aghts'] = AghtData::where(
            'monthly_report_id',
            $monthlyId
        )->get();

        /*
        |--------------------------------------------------------------------------
        | ATTRIBUTE
        |--------------------------------------------------------------------------
        */

        $query = FormAttribute::join(
                'attributes',
                'attributes.id',
                '=',
                'form_attributes.attribute_id'
            )
            ->select(
                'form_attributes.*',
                'attributes.name',
                'attributes.status_ownership',
                'attributes.unit',
                'attributes.standard_contract'
            )
            ->where('monthly_report_id', $monthlyId);

        $data['attributes'] = (clone $query)
            ->where('attributes.type_attribute', 'Attribute')
            ->get();

        $data['administrations'] = (clone $query)
            ->where('attributes.type_attribute', 'Administrasi')
            ->get();

        $data['saranas'] = (clone $query)
            ->where('attributes.type_attribute', 'Sarana')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | FOREIGN WORKER
        |--------------------------------------------------------------------------
        */

        $data['foreigns'] = ForeignWorker::where(
            'monthly_report_id',
            $monthlyId
        )->get();

        /*
        |--------------------------------------------------------------------------
        | SECURITY PROGRAM
        |--------------------------------------------------------------------------
        */

        $data['programs'] = MonthlySecurityProgram::with(
            'securityProgram',
            'programs.mainProgram'
        )
        ->where('monthly_report_id', $monthlyId)
        ->get();

        /*
        |--------------------------------------------------------------------------
        | INTERNAL VULNERABILITY
        |--------------------------------------------------------------------------
        */

        $data['internals'] = InternalVulnerability::with('vulnerability')
            ->where('monthly_report_id', $monthlyId)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | EXTERNAL VULNERABILITY
        |--------------------------------------------------------------------------
        */

        $data['externals'] = ExternalVulnerability::with('vulnerability')
            ->where('monthly_report_id', $monthlyId)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | BUDGET ABSORPTION
        |--------------------------------------------------------------------------
        */

        $dataBiaya = LaporanBulananBiaya::where(
                'monthly_report_id',
                $monthlyId
            )
            ->whereIn('type', ['administrasi', 'pemeliharaan'])
            ->get()
            ->map(function ($item) {

                $item->prosentase_penyerapan =
                    $item->jumlah_anggaran != 0
                    ? ($item->penyerapan_anggaran / $item->jumlah_anggaran) * 100
                    : 0;

                return $item;
            })
            ->groupBy('type');

        $data['administrasi'] = $dataBiaya->get(
            'administrasi',
            collect()
        );

        $data['pemeliharaan'] = $dataBiaya->get(
            'pemeliharaan',
            collect()
        );

        $monthLabel = $data['monthlyReport'] && $data['monthlyReport']->report_date
            ? Str::slug(Carbon::parse($data['monthlyReport']->report_date)->locale('id')->translatedFormat('F Y'))
            : $monthlyId;

        return Excel::download(
            new MonthlyAuditAllExport($data),
            "laporan-bulanan-{$monthLabel}.xlsx"
        );
    }

}
