<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\AghtData;
use App\Models\AgreementExternal;
use App\Models\Attribute;
use App\Models\ExternalVulnerability;
use App\Models\ForeignWorker;
use App\Models\FormAttribute;
use App\Models\InternalVulnerability;
use App\Models\LaporanBulananBiaya;
use App\Models\MainSecurityProgram;
use App\Models\MonthlyAgreementExternal;
use App\Models\MonthlyMainSecurityProgram;
use App\Models\MonthlyReport;
use App\Models\MonthlyResponsiblePerson;
use App\Models\MonthlySecurityExternal;
use App\Models\MonthlySecurityProgram;
use App\Models\OutsourceEmployee;
use App\Models\ReportEmployee;
use App\Models\ResponsiblePerson;
use App\Models\Security;
use App\Models\SecurityExternal;
use App\Models\SecurityForm;
use App\Models\SecurityPerson;
use App\Models\SecurityProgram;
use App\Models\Vulnerability;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class MonthlyAuditController extends Controller
{
    public function index(Request $request){
        $userId = Auth::user()->id;

        $query = MonthlyReport::query();

        $query->where('user_id', $userId);

        // ===============================
        // FILTER DEFAULT
        // ===============================
        $query->where('send_status', true);

        if ($request->month) {
            $query->where('report_date', $request->month);
        }

        // ===============================
        // 🔥 FILTER UNIT CODE (RELASI)
        // ===============================
        if ($request->unit_code) {
            $query->whereHas('detailUnit', function ($q) use ($request) {
                $q->where('unit_code', 'like', '%' . $request->unit_code . '%');
            });
        }

        // ===============================
        // RESULT
        // ===============================
        $data['forms'] = $query
            ->with('detailUnit')
            ->latest()
            ->paginate(25);

        $data['request'] = $request->all();

        return view('user.monthly-audit.index',$data);
    }

    public function create(){
        return view('user.monthly-audit.create');
    }
    
    public function store(Request $request){

        try {
            DB::beginTransaction();

            $userId = Auth::user()->id;
            $unitId = Auth::user()->unit_id;

            $date = explode("-", $request->report_date);
            $year = (int)$date[0];
            $month = (int)$date[1];

            $currentYear = now()->year;
            $currentMonth = now()->month;

            if ($year > $currentYear || ($year == $currentYear && $month > $currentMonth)) {
                DB::rollback();
                Alert::error('Tanggal Laporan Tidak Valid', 'Tanggal laporan tidak boleh di masa depan!');
                return back()->withErrors(['report_date' => 'Tanggal laporan tidak boleh di masa depan.'])->withInput();
            }

            $reportDate = sprintf('%04d-%02d', $year, $month);

            $exists = MonthlyReport::where('user_id', $userId)
                ->where('report_date', $reportDate)
                ->exists();

            if ($exists) {
                DB::rollback();
                Alert::error('Laporan Sudah Ada', 'Laporan bulanan untuk bulan dan tahun tersebut sudah ada!');
                return back()->withErrors(['report_date' => 'Laporan bulanan untuk bulan dan tahun tersebut sudah ada.'])->withInput();
            }

            // Ambil data terakhir
            $lastReport = MonthlyReport::where('user_id', $userId)
                ->orderBy('report_date', 'desc')
                ->first();

            if ($lastReport) {
                $lastDate = explode('-', $lastReport->report_date);
                $lastYear = (int)$lastDate[0];
                $lastMonth = (int)$lastDate[1];

                // hitung bulan berikutnya yang seharusnya
                if ($lastMonth == 12) {
                    $expectedYear = $lastYear + 1;
                    $expectedMonth = 1;
                } else {
                    $expectedYear = $lastYear;
                    $expectedMonth = $lastMonth + 1;
                }

                // kalau tidak sesuai urutan → tolak
                if ($year != $expectedYear || $month != $expectedMonth) {
                    DB::rollback();
                    Alert::error('Urutan Laporan Salah', 'Anda harus mengisi laporan bulan sebelumnya terlebih dahulu!');
                    return back()->withErrors([
                        'report_date' => "Harus mengisi bulan {$expectedYear}-" . sprintf('%02d', $expectedMonth) . " terlebih dahulu."
                    ])->withInput();
                }
            }
                        
            $administrations = Attribute::select('id')->where('type_attribute','Administrasi')->get();
            $attributes = Attribute::select('id')->where('user_id',$userId)->get();
            $persons = ResponsiblePerson::select('id')->where('user_id',$userId)->get();
            $agreements = AgreementExternal::select('id')->where('user_id',$userId)->get();
            $securityExternals = SecurityExternal::select('id')->where('user_id',$userId)->get();
            $securities = Security::select('id')->where('user_id',$userId)->get();
            $externals = Vulnerability::select('id')->where('type','eksternal')->get();
            $internals = Vulnerability::select('id')->where('type','internal')->get();
            $programs = SecurityProgram::where('user_id',$userId)->where('year',$date[0])->get();

            $report = MonthlyReport::create([
                'user_id' => $userId,
                'unit_id' => $unitId,
                'report_date' => $request->report_date,
                'send_status' => false,
            ]);

            ReportEmployee::create([
                'monthly_report_id' => $report->id,
                'user_id' => $userId,
            ]);
            if ($administrations) {
                foreach ($administrations as $administration) {
                    FormAttribute::create([
                        'monthly_report_id' => $report->id,
                        'user_id' => $userId,
                        'attribute_id' => $administration->id
                    ]);
                }
            }
            if ($attributes) {
                foreach ($attributes as $attribute) {
                    FormAttribute::create([
                        'monthly_report_id' => $report->id,
                        'user_id' => $userId,
                        'attribute_id' => $attribute->id
                    ]);
                }
            }
            if ($securities) {
                foreach ($securities as $security) {
                    SecurityForm::create([
                        'monthly_report_id' => $report->id,
                        'user_id' => $userId,
                        'security_id' => $security->id
                    ]);
                }
            }
            if ($persons) {
                foreach ($persons as $person) {
                    MonthlyResponsiblePerson::create([
                        'monthly_report_id' => $report->id,
                        'user_id' => $userId,
                        'responsible_person_id' => $person->id
                    ]);
                }
            }
            if ($agreements) {
                foreach ($agreements as $agreement) {
                    MonthlyAgreementExternal::create([
                        'monthly_report_id' => $report->id,
                        'user_id' => $userId,
                        'agreement_external_id' => $agreement->id
                    ]);
                }
            }
            if ($securityExternals) {
                foreach ($securityExternals as $item) {
                    MonthlySecurityExternal::create([
                        'monthly_report_id' => $report->id,
                        'user_id' => $userId,
                        'security_external_id' => $item->id
                    ]);
                }
            }
            if ($externals) {
                foreach ($externals as $external) {
                    ExternalVulnerability::create([
                        'monthly_report_id' => $report->id,
                        'user_id' => $userId,
                        'vulnerability_id' => $external->id
                    ]);
                }
            }
            if ($internals) {
                foreach ($internals as $internal) {
                    InternalVulnerability::create([
                        'monthly_report_id' => $report->id,
                        'user_id' => $userId,
                        'vulnerability_id' => $internal->id
                    ]);
                }
            }  
           
            if ($programs) {
               
                foreach($programs as $program) {
                    $monthlyProgram = MonthlySecurityProgram::create([
                        'monthly_report_id' => $report->id,
                        'user_id' => $userId,
                        'program_id' => $program->id
                    ]);
                    $mainPrograms = MainSecurityProgram::where('program_id', $program->id)->where('user_id', $userId)->get();
                    foreach ($mainPrograms as $item) {
                        MonthlyMainSecurityProgram::create([
                            'monthly_report_id' => $report->id,
                            'user_id' => $userId,
                            'monthly_program_id' => $monthlyProgram->id,
                            'program_id' => $program->id,
                            'main_program_id' => $item->id
                        ]);
                    }
                    
                }
            }

            DB::commit();
            Alert::success('Tambah Berhasil', 'Laporan bulanan berhasil dibuat!');
            return redirect()->route('user.monthly-audit.index');
            
        } catch (\Throwable $th) {

            DB::rollback();
            Alert::error('Tambah Gagal', 'Laporan bulanan gagal dibuat!');
            return redirect()->route('user.monthly-audit.index');
        }
    }

    public function edit(){
        return view('user.monthly-audit.edit');
    }

    public function destroy($monthlyId){
        try {

            DB::beginTransaction();
            
            MonthlyReport::where('id', $monthlyId)->delete();
            ReportEmployee::where('monthly_report_id', $monthlyId)->delete();
            FormAttribute::where('monthly_report_id', $monthlyId)->delete();
            SecurityForm::where('monthly_report_id', $monthlyId)->delete();
            ExternalVulnerability::where('monthly_report_id', $monthlyId)->delete();
            InternalVulnerability::where('monthly_report_id', $monthlyId)->delete();
            MonthlySecurityProgram::where('monthly_report_id', $monthlyId)->delete();
            MonthlyMainSecurityProgram::where('monthly_report_id', $monthlyId)->delete();
            MonthlyResponsiblePerson::where('monthly_report_id', $monthlyId)->delete();
            OutsourceEmployee::where('monthly_report_id', $monthlyId)->delete();
            MonthlySecurityExternal::where('monthly_report_id', $monthlyId)->delete();
            SecurityPerson::where('monthly_report_id', $monthlyId)->delete();
            MonthlyAgreementExternal::where('monthly_report_id', $monthlyId)->delete();
            AghtData::where('monthly_report_id', $monthlyId)->delete();
            ForeignWorker::where('monthly_report_id', $monthlyId)->delete();

            DB::commit();
            Alert::success('Berhasil Dihapus', 'Laporan bulanan berhasil dihapus!');
            return redirect()->route('user.monthly-audit.index');
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
            Alert::error('Gagal dihapus', 'Laporan bulanan gagal dihapus!');
            return redirect()->route('user.monthly-audit.index');
        }
    }

    public function sendReport($monthlyId){

        try {

            DB::beginTransaction();
            $report = MonthlyReport::where('id', $monthlyId)->first();
            $report->send_status = true;
            $report->send_date = now();
            $report->save();

            DB::commit();
            Alert::success('Berhasil Dikirim', 'Laporan bulanan berhasil dikirim!');
            return redirect()->route('user.monthly-audit.index');
        } catch (\Throwable $th) {
            DB::rollback();
            Alert::error('Gagal Dikirim', 'Laporan bulanan gagal dikirim!');
            return redirect()->route('user.monthly-audit.index');
        }
    }
    public function show($monthlyId){

        $data['monthlyId'] = $monthlyId;
        $data['monthlyReport'] = MonthlyReport::where('id', $monthlyId)->select('report_date')->first();
        $data['employee'] = ReportEmployee::where('monthly_report_id', $monthlyId)->first(); 
        $data['outsources'] = OutsourceEmployee::where('monthly_report_id', $monthlyId)->latest()->get();
        $security = SecurityForm::join('securities', 'security_forms.security_id','securities.id')->where('monthly_report_id', $monthlyId);
        $data['securityKomandan'] = (clone $security)->where('securities.position', 'Komandan')->get()->count();
        $data['securityAnggota'] = (clone $security)->where('securities.position', 'Anggota')->get()->count();
        $data['securityChief'] = (clone $security)->where('securities.position', 'Chief')->get()->count();
        $data['security'] = (clone $security)->get()->count();
        $securityExternal = MonthlySecurityExternal::join('security_externals','security_externals.id','monthly_security_externals.security_external_id')->where('monthly_report_id', $monthlyId);
        $data['securityPolri'] = (clone $securityExternal)->where('note', 'Polri')->get()->count();
        $data['securityTNI'] = (clone $securityExternal)->where('note', 'TNI')->get()->count();
        $data['securityExternal'] = (clone $securityExternal)->get()->count();

        $foreign = ForeignWorker::where('monthly_report_id', $monthlyId);
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


        $data['persons'] = MonthlyResponsiblePerson::with('person')->where('monthly_report_id', $monthlyId)->get();
        $data['securities'] = MonthlySecurityExternal::with('security')->where('monthly_report_id', $monthlyId)->get();
        $data['agreements'] = MonthlyAgreementExternal::with('agreement')->where('monthly_report_id', $monthlyId)->get();
        
        $data['securityForms'] = SecurityForm::with('security')->where('monthly_report_id', $monthlyId)->get();
        
        $data['aghts'] = AghtData::where('monthly_report_id', $monthlyId)->get();

        $attribute = FormAttribute::join('attributes', 'attributes.id', 'form_attributes.attribute_id')
        ->select('form_attributes.*', 'attributes.name', 'attributes.status_ownership', 'attributes.unit', 'attributes.standard_contract')->where('monthly_report_id', $monthlyId);
    
        $data['attributes'] = (clone $attribute)->where('attributes.type_attribute', 'Attribute')->get();
        $data['administrations'] = (clone $attribute)->where('attributes.type_attribute', 'Administrasi')->get();
        $data['saranas'] = (clone $attribute)->where('attributes.type_attribute', 'Sarana')->get();
        $data['foreignWorkers'] = ForeignWorker::where('monthly_report_id', $monthlyId)->get();
        $data['programs'] = MonthlySecurityProgram::with('securityProgram','programs')->where('monthly_report_id',$monthlyId)->get();

        $data['internals'] = InternalVulnerability::with('vulnerability')->where('monthly_report_id', $monthlyId)->get();
        $data['externals'] = ExternalVulnerability::with('vulnerability')->where('monthly_report_id', $monthlyId)->get();
        $dataBiaya = LaporanBulananBiaya::where('monthly_report_id', $monthlyId)
            ->whereIn('type', ['administrasi', 'pemeliharaan'])
            ->get()
            ->map(function ($item) {
                $item->prosentase_penyerapan = $item->jumlah_anggaran != 0
                    ? $item->penyerapan_anggaran / $item->jumlah_anggaran
                    : 0;
                return $item;
            })
            ->groupBy('type');

        $data['administrasi'] = $dataBiaya->get('administrasi', collect());
        $data['pemeliharaan'] = $dataBiaya->get('pemeliharaan', collect());
        return view('user.monthly-audit.show',$data);
    }


}
