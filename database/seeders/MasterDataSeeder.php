<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

/**
 * Master data / data yang diperlukan di awal, diambil dari database yang sedang berjalan
 * (role & permission + units dari database lokal pengujian; struktur Assessment, template
 * Maturity & KPI, tipe Fasum, Vulnerability, Province, City dari database dev — karena
 * tabel-tabel itu kosong di database lokal pengujian saat seeder ini dibuat).
 *
 * TIDAK berisi data transaksional (laporan, assessment/maturity/kpi yang sudah diisi unit,
 * dsb.) dan TIDAK berisi data per-unit (Attribute, Satpam, dan semacamnya milik unit
 * tertentu) — murni data referensi/template yang dipakai bersama oleh seluruh sistem.
 *
 * ID asli dipertahankan (bukan auto-increment baru) supaya relasi antar tabel (misalnya
 * sub_area_id -> area_id, atau units.province_id -> provinces.id) tetap benar. Aman
 * dijalankan berulang: setiap tabel dilewati kalau sudah terisi.
 *
 *   php artisan db:seed --class="Database\Seeders\MasterDataSeeder"
 *
 * Catatan: role 'Auditor' sengaja TIDAK disertakan sebagai role aktif karena pada database
 * sumber, role tersebut saat ini soft-deleted (tidak dipakai lagi oleh sistem yang berjalan).
 */
class MasterDataSeeder extends Seeder
{
    protected function dataPath(string $file): string
    {
        return __DIR__ . '/data/' . $file;
    }

    protected function readJson(string $file)
    {
        return json_decode(File::get($this->dataPath($file)), true);
    }

    /** Insert dengan id asli dipertahankan, lalu samakan sequence Postgres supaya insert berikutnya tidak bentrok. */
    protected function insertPreservingIds(string $table, array $rows): void
    {
        if (empty($rows)) {
            return;
        }

        $now = now();
        $rows = array_map(fn($row) => $row + ['created_at' => $now, 'updated_at' => $now], $rows);

        foreach (array_chunk($rows, 500) as $chunk) {
            DB::table($table)->insert($chunk);
        }

        DB::statement("select setval(pg_get_serial_sequence('{$table}', 'id'), (select max(id) from {$table}))");
    }

    protected function isEmpty(string $table): bool
    {
        return DB::table($table)->count() === 0;
    }

    public function run()
    {
        // Geometry Province/City cukup besar (puluhan MB) untuk dibaca+decode sekaligus.
        ini_set('memory_limit', '1024M');
        set_time_limit(0);

        $this->seedUnits();
        $this->seedRolesPermissions();
        $this->seedAssessmentMaster();
        $this->seedMaturityKpiMaster();
        $this->seedFasumTypes();
        $this->seedVulnerabilities();
        $this->seedAttributePresets();
        $this->seedProvincesAndCities();
    }

    protected function seedUnits()
    {
        if (!$this->isEmpty('units')) {
            $this->command->info('units sudah terisi, dilewati.');
            return;
        }

        $units = $this->readJson('units.json');
        $this->insertPreservingIds('units', $units);
        $this->command->info('units: ' . count($units) . ' baris.');
    }

    protected function seedRolesPermissions()
    {
        $data = $this->readJson('roles_permissions.json');
        $now = now();

        foreach ($data['roles'] as $name) {
            DB::table('roles')->where('name', $name)->exists()
                ?: DB::table('roles')->insert(['name' => $name, 'guard_name' => 'web', 'created_at' => $now, 'updated_at' => $now]);
        }

        foreach ($data['permissions'] as $name) {
            DB::table('permissions')->where('name', $name)->exists()
                ?: DB::table('permissions')->insert(['name' => $name, 'guard_name' => 'web', 'created_at' => $now, 'updated_at' => $now]);
        }

        $roleIds = DB::table('roles')->pluck('id', 'name');
        $permIds = DB::table('permissions')->pluck('id', 'name');
        $existingPairs = DB::table('role_has_permissions')->get()
            ->map(fn($r) => $r->role_id . '-' . $r->permission_id)->flip();

        $pairRows = [];
        foreach ($data['role_permissions'] as $pair) {
            $roleId = $roleIds[$pair['role']] ?? null;
            $permId = $permIds[$pair['permission']] ?? null;
            if (!$roleId || !$permId || isset($existingPairs[$roleId . '-' . $permId])) {
                continue;
            }
            $pairRows[] = ['role_id' => $roleId, 'permission_id' => $permId];
        }

        if ($pairRows) {
            foreach (array_chunk($pairRows, 500) as $chunk) {
                DB::table('role_has_permissions')->insert($chunk);
            }
        }

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $this->command->info('roles: ' . count($data['roles']) . ', permissions: ' . count($data['permissions']) . ', role_has_permissions baru: ' . count($pairRows));
    }

    protected function seedAssessmentMaster()
    {
        if (!$this->isEmpty('category_assesments')) {
            $this->command->info('category_assesments sudah terisi, dilewati.');
            return;
        }

        $data = $this->readJson('assessment_master.json');
        $this->insertPreservingIds('category_assesments', $data['categories']);
        $this->insertPreservingIds('question_assesments', $data['questions']);
        $this->insertPreservingIds('level_assesments', $data['levels']);
        $this->command->info('assessment master: ' . count($data['categories']) . ' kategori, ' . count($data['questions']) . ' indikator, ' . count($data['levels']) . ' level.');
    }

    protected function seedMaturityKpiMaster()
    {
        if (!$this->isEmpty('areas')) {
            $this->command->info('areas (template Maturity/KPI) sudah terisi, dilewati.');
            return;
        }

        $data = $this->readJson('maturity_kpi_master.json');
        $this->insertPreservingIds('areas', $data['areas']);
        $this->insertPreservingIds('sub_areas', $data['sub_areas']);
        $this->insertPreservingIds('levels', $data['levels']);
        $this->insertPreservingIds('notes', $data['notes']);
        $this->command->info('template Maturity/KPI: ' . count($data['areas']) . ' area, ' . count($data['sub_areas']) . ' sub area, ' . count($data['levels']) . ' level, ' . count($data['notes']) . ' note.');
    }

    protected function seedFasumTypes()
    {
        if (!$this->isEmpty('fasum_types')) {
            $this->command->info('fasum_types sudah terisi, dilewati.');
            return;
        }

        $rows = $this->readJson('fasum_types.json');
        $this->insertPreservingIds('fasum_types', $rows);
        $this->command->info('fasum_types: ' . count($rows) . ' baris.');
    }

    protected function seedVulnerabilities()
    {
        if (!$this->isEmpty('vulnerabilities')) {
            $this->command->info('vulnerabilities sudah terisi, dilewati.');
            return;
        }

        $rows = $this->readJson('vulnerabilities.json');
        $this->insertPreservingIds('vulnerabilities', $rows);
        $this->command->info('vulnerabilities: ' . count($rows) . ' baris.');
    }

    protected function seedAttributePresets()
    {
        $rows = $this->readJson('attribute_presets.json');

        if (empty($rows)) {
            $this->command->info('attribute_presets: tidak ada data di database sumber (kosong juga di sana), dilewati.');
            return;
        }

        if (!$this->isEmpty('attribute_presets')) {
            $this->command->info('attribute_presets sudah terisi, dilewati.');
            return;
        }

        $this->insertPreservingIds('attribute_presets', $rows);
        $this->command->info('attribute_presets: ' . count($rows) . ' baris.');
    }

    /**
     * Province/City dibaca langsung sebagai baris (id, name, geometry, ...) dengan id asli
     * dipertahankan — BUKAN lewat ProvinceSeeder/CitySeeder bawaan project, karena seeder
     * itu auto-increment id baru (tidak mempertahankan id asli) sehingga units.province_id /
     * units.city_id yang sudah mengacu ke id tertentu bisa salah sasaran.
     */
    protected function seedProvincesAndCities()
    {
        if ($this->isEmpty('provinces')) {
            $provinces = $this->readJson('provinces.json');
            $provinces = array_map(fn($p) => ['id' => $p['id'], 'name' => $p['name'], 'geometry' => json_encode($p['geometry'])], $provinces);
            $this->insertPreservingIds('provinces', $provinces);
            $this->command->info('provinces: ' . count($provinces) . ' baris.');
        } else {
            $this->command->info('provinces sudah terisi, dilewati.');
        }

        if ($this->isEmpty('cities')) {
            $cities = $this->readJson('cities.json');
            $cities = array_map(fn($c) => [
                'id' => $c['id'], 'name' => $c['name'], 'province_id' => $c['province_id'], 'geometry' => json_encode($c['geometry']),
            ], $cities);
            $this->insertPreservingIds('cities', $cities);
            $this->command->info('cities: ' . count($cities) . ' baris.');
        } else {
            $this->command->info('cities sudah terisi, dilewati.');
        }
    }
}
