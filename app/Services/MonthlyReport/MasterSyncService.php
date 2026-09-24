<?php

namespace App\Services\MonthlyReport;

use App\Models\AgreementExternal;
use App\Models\Attribute;
use App\Models\ExternalVulnerability;
use App\Models\FormAttribute;
use App\Models\InternalVulnerability;
use App\Models\MainSecurityProgram;
use App\Models\MonthlyAgreementExternal;
use App\Models\MonthlyMainSecurityProgram;
use App\Models\MonthlyReport;
use App\Models\MonthlyResponsiblePerson;
use App\Models\MonthlySecurityExternal;
use App\Models\MonthlySecurityProgram;
use App\Models\ResponsiblePerson;
use App\Models\Security;
use App\Models\SecurityExternal;
use App\Models\SecurityForm;
use App\Models\SecurityProgram;
use App\Models\Vulnerability;
use App\Services\Concerns\AllocatesIds;
use Illuminate\Support\Facades\DB;

/**
 * Menyalin data master ke laporan bulanan.
 * - mode "duplicate": baris master diduplikasi (user_id null, source_id = id master)
 *   sehingga laporan punya salinan sendiri yang bisa diedit/dihapus tanpa memengaruhi master.
 * - mode "reference": baris laporan hanya menunjuk ke master (data global/admin).
 * Master yang sudah dihapus (soft delete) tidak pernah ikut disalin.
 */
class MasterSyncService
{
    use AllocatesIds;

    public static function sections()
    {
        return [
            'security' => [
                'label' => 'Personil Satpam', 'mode' => 'duplicate',
                'master' => Security::class, 'row' => SecurityForm::class, 'fk' => 'security_id',
                'scope' => fn($q, $r) => \App\Services\Unit\UnitScope::applyReportMaster($q, $r),
            ],
            'attribute' => [
                'label' => 'Atribut/Sarana', 'mode' => 'duplicate',
                'master' => Attribute::class, 'row' => FormAttribute::class, 'fk' => 'attribute_id',
                'scope' => fn($q, $r) => \App\Services\Unit\UnitScope::applyReportMaster($q, $r)->where(function ($w) {
                    $w->whereNull('type_attribute')->orWhere('type_attribute', '!=', 'Administrasi');
                }),
                'nullify' => ['unit_id'],
            ],
            'administration' => [
                'label' => 'Administrasi', 'mode' => 'reference',
                'master' => Attribute::class, 'row' => FormAttribute::class, 'fk' => 'attribute_id',
                'scope' => fn($q, $r) => \App\Services\Unit\UnitScope::applyReportAdministration($q->where('type_attribute', 'Administrasi'), $r),
            ],
            'person' => [
                'label' => 'Penanggung Jawab', 'mode' => 'duplicate',
                'master' => ResponsiblePerson::class, 'row' => MonthlyResponsiblePerson::class, 'fk' => 'responsible_person_id',
                'scope' => fn($q, $r) => \App\Services\Unit\UnitScope::applyReportMaster($q, $r),
            ],
            'agreement' => [
                'label' => 'Perjanjian Eksternal', 'mode' => 'duplicate',
                'master' => AgreementExternal::class, 'row' => MonthlyAgreementExternal::class, 'fk' => 'agreement_external_id',
                'scope' => fn($q, $r) => \App\Services\Unit\UnitScope::applyReportMaster($q, $r),
            ],
            'security_external' => [
                'label' => 'Pengamanan Eksternal', 'mode' => 'duplicate',
                'master' => SecurityExternal::class, 'row' => MonthlySecurityExternal::class, 'fk' => 'security_external_id',
                'scope' => fn($q, $r) => \App\Services\Unit\UnitScope::applyReportMaster($q, $r),
            ],
            'vulnerability_internal' => [
                'label' => 'Kerawanan Internal', 'mode' => 'reference',
                'master' => Vulnerability::class, 'row' => InternalVulnerability::class, 'fk' => 'vulnerability_id',
                'scope' => fn($q, $uid) => $q->where('type', 'internal'),
            ],
            'vulnerability_external' => [
                'label' => 'Kerawanan Eksternal', 'mode' => 'reference',
                'master' => Vulnerability::class, 'row' => ExternalVulnerability::class, 'fk' => 'vulnerability_id',
                'scope' => fn($q, $uid) => $q->where('type', 'eksternal'),
            ],
            'program' => ['label' => 'Program Keamanan', 'mode' => 'program'],
        ];
    }

    public static function config($section)
    {
        return self::sections()[$section] ?? null;
    }

    protected function table($class)
    {
        return (new $class)->getTable();
    }

    protected function excludedIds($report, $section)
    {
        return DB::table('monthly_report_exclusions')
            ->where('monthly_report_id', $report->id)->where('section', $section)
            ->pluck('master_id')->all();
    }

    /** id master yang sudah ada di laporan (langsung, atau lewat salinan). */
    protected function coveredMasterIds($cfg, $report)
    {
        $rowFks = $cfg['row']::where('monthly_report_id', $report->id)->pluck($cfg['fk'])->all();
        $covered = $rowFks;

        if ($cfg['mode'] === 'duplicate' && $rowFks) {
            $sources = $cfg['master']::withTrashed()->whereIn('id', $rowFks)->whereNotNull('source_id')->pluck('source_id')->all();
            $covered = array_merge($covered, $sources);
        }

        return $covered;
    }

    public function missingMasters($section, MonthlyReport $report)
    {
        $cfg = self::config($section);
        $covered = array_merge($this->coveredMasterIds($cfg, $report), $this->excludedIds($report, $section));

        $query = $cfg['master']::query();
        ($cfg['scope'])($query, $report);
        if ($covered) {
            $query->whereNotIn('id', $covered);
        }

        return $query->orderBy('id')->get();
    }

    /** Tambahkan data master baru ke laporan. Mengembalikan jumlah yang ditambahkan. */
    public function sync($section, MonthlyReport $report)
    {
        $cfg = self::config($section);

        if ($cfg['mode'] === 'program') {
            return $this->syncPrograms($report);
        }

        $masters = $this->missingMasters($section, $report);
        if ($masters->isEmpty()) {
            return 0;
        }

        $this->attach($cfg, $report, $masters);

        return $masters->count();
    }

    /** Buat baris laporan (dan salinan master bila mode duplicate) secara massal. */
    public function attach($cfg, MonthlyReport $report, $masters)
    {
        $now = now();
        $fkIds = [];

        if ($cfg['mode'] === 'duplicate') {
            $masterTable = $this->table($cfg['master']);
            $newIds = $this->allocateIds($masterTable, $masters->count());
            $rows = [];

            foreach ($masters->values() as $i => $master) {
                $attrs = $master->getAttributes();
                unset($attrs['id'], $attrs['deleted_at'], $attrs['deleted_by'], $attrs['updated_by']);
                $attrs['id'] = $newIds[$i];
                $attrs['user_id'] = null;
                $attrs['source_id'] = $master->id;
                $attrs['created_by'] = $report->user_id;
                $attrs['created_at'] = $now;
                $attrs['updated_at'] = $now;
                foreach (($cfg['nullify'] ?? []) as $col) {
                    $attrs[$col] = null;
                }
                $rows[] = $attrs;
                $fkIds[] = $newIds[$i];
            }

            $cfg['master']::insert($rows);
        } else {
            $fkIds = $masters->pluck('id')->all();
        }

        $rowRows = [];
        foreach ($fkIds as $fkId) {
            $rowRows[] = [
                'monthly_report_id' => $report->id,
                'user_id'           => $report->user_id,
                $cfg['fk']          => $fkId,
                'created_at'        => $now,
                'updated_at'        => $now,
            ];
        }

        foreach (array_chunk($rowRows, 500) as $chunk) {
            $cfg['row']::insert($chunk);
        }
    }

    /** Dipakai saat membuat laporan baru: salin semua section sekaligus. */
    public function copyAll(MonthlyReport $report)
    {
        foreach (self::sections() as $section => $cfg) {
            if ($cfg['mode'] === 'program') {
                $this->syncPrograms($report);
                continue;
            }

            $masters = $this->missingMasters($section, $report);
            if ($masters->isNotEmpty()) {
                $this->attach($cfg, $report, $masters);
            }
        }
    }

    public function syncPrograms(MonthlyReport $report)
    {
        $year = explode('-', $report->report_date)[0];
        $added = 0;

        $programs = \App\Services\Unit\UnitScope::applyReportMaster(SecurityProgram::query(), $report)->where('year', $year)->get();
        $existingPrograms = MonthlySecurityProgram::where('monthly_report_id', $report->id)->get()->keyBy('program_id');
        $existingMains = MonthlyMainSecurityProgram::where('monthly_report_id', $report->id)->pluck('main_program_id')->all();

        foreach ($programs as $program) {
            $monthlyProgram = $existingPrograms[$program->id] ?? null;

            if (!$monthlyProgram) {
                $monthlyProgram = MonthlySecurityProgram::create([
                    'monthly_report_id' => $report->id,
                    'user_id'           => $report->user_id,
                    'program_id'        => $program->id,
                ]);
                $added++;
            }

            $mains = MainSecurityProgram::where('program_id', $program->id)->get();
            foreach ($mains as $item) {
                if (in_array($item->id, $existingMains)) {
                    continue;
                }

                MonthlyMainSecurityProgram::create([
                    'monthly_report_id'  => $report->id,
                    'user_id'            => $report->user_id,
                    'monthly_program_id' => $monthlyProgram->id,
                    'program_id'         => $program->id,
                    'main_program_id'    => $item->id,
                    'start_month'        => $item->start_month,
                    'start_week'         => $item->start_week,
                    'end_month'          => $item->end_month,
                    'end_week'           => $item->end_week,
                    // Realisasi mulai kosong; rencana selalu dibaca dari master program.
                    'schedule'           => '[]',
                ]);
                $added++;
            }
        }

        return $added;
    }

    public function exclude($report, $section, $masterId)
    {
        DB::table('monthly_report_exclusions')->updateOrInsert(
            ['monthly_report_id' => $report->id, 'section' => $section, 'master_id' => $masterId],
            ['created_at' => now(), 'updated_at' => now()]
        );
    }
}
