<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;

/**
 * Bootstrap awal untuk instalasi baru (database kosong).
 *
 * Riwayat migrasi di project ini hanya berisi PERUBAHAN atas role & permission yang sudah
 * ada (banyak migrasi memakai Role::where('name', ...)->first() secara kondisional), bukan
 * pembuatan awal. Pada instalasi dari database kosong, tabel roles/permissions akan kosong
 * total dan seluruh menu/aksi di aplikasi (yang dijaga oleh @can(...) atau middleware
 * 'can:...') tidak akan terlihat/bisa diakses oleh siapa pun, termasuk akun admin bawaan.
 *
 * Seeder ini HANYA dijalankan sekali pada instalasi baru dengan database kosong:
 *   php artisan db:seed --class=InstallBootstrapSeeder
 *
 * Yang dilakukan:
 * 1. Membuat seluruh permission yang dipakai kode (hasil pemindaian @can()/middleware can:).
 * 2. Membuat role 'Admin' dan memberinya SEMUA permission tersebut, supaya akun admin bisa
 *    langsung membuka menu Role & Permission untuk mengatur permission role lain
 *    (Pusat, Unit, MMRK, BUJP, Auditor, UL, Auditor External) sesuai kebijakan masing-masing.
 * 3. Membuat role lain sebagai baris kosong (tanpa permission) supaya tersedia sebagai
 *    pilihan role, dan diberi permission-nya lewat menu Role & Permission setelah login.
 * 4. Menetapkan role 'Admin' ke user admin@admin.com (dibuat oleh UsersTableSeeder).
 *
 * TIDAK menyalin data master (Unit, dsb.) - itu tetap harus diisi manual atau lewat
 * restore database yang sudah ada.
 */
class InstallBootstrapSeeder extends Seeder
{
    /** Permission yang benar-benar dipakai kode (hasil pemindaian, 177 nama). */
    protected array $permissions = [
            'create.agreement.external.unit',
            'create.assesment.bujp',
            'create.assesment.bujp.admin',
            'create.assesment.bujp.unit',
            'create.attribute',
            'create.attribute.unit',
            'create.audit.smp.admin',
            'create.audit.smp.score.admin',
            'create.audit.smp.score.unit',
            'create.budget.master',
            'create.category.assesment',
            'create.criteria.audit.smp.admin',
            'create.element.audit.smp.admin',
            'create.evidence.audit.smp.admin',
            'create.external.auditor',
            'create.fasum.user',
            'create.fasumtype.admin',
            'create.kpi.area',
            'create.kpi.level',
            'create.kpi.note',
            'create.kpi.subarea',
            'create.level.assesment',
            'create.main.security.program.unit',
            'create.marturity.area',
            'create.marturity.level',
            'create.marturity.note',
            'create.marturity.subarea',
            'create.marturity.unit',
            'create.permission',
            'create.question.assesment',
            'create.responsible.person.unit',
            'create.role',
            'create.securepedia.admin',
            'create.security.external.unit',
            'create.security.kpi.unit',
            'create.security.program.unit',
            'create.security.unit',
            'create.unit.admin',
            'create.user',
            'create.user.vendor',
            'create.vulnerability',
            'delete.agreement.external.unit',
            'delete.assesment.bujp',
            'delete.assesment.bujp.admin',
            'delete.assesment.bujp.unit',
            'delete.attribute',
            'delete.attribute.unit',
            'delete.audit.smp.admin',
            'delete.audit.smp.score.admin',
            'delete.budget.master',
            'delete.category.assesment',
            'delete.criteria.audit.smp.admin',
            'delete.element.audit.smp.admin',
            'delete.evidence.audit.smp.admin',
            'delete.external.auditor',
            'delete.fasum.user',
            'delete.fasumtype.admin',
            'delete.kpi.area',
            'delete.kpi.level',
            'delete.kpi.note',
            'delete.kpi.subarea',
            'delete.level.assesment',
            'delete.main.security.program.unit',
            'delete.marturity.area',
            'delete.marturity.level',
            'delete.marturity.note',
            'delete.marturity.subarea',
            'delete.marturity.unit',
            'delete.permission',
            'delete.question.assesment',
            'delete.responsible.person.unit',
            'delete.role',
            'delete.securepedia.admin',
            'delete.security.external.unit',
            'delete.security.kpi.unit',
            'delete.security.program.unit',
            'delete.security.unit',
            'delete.user',
            'delete.vulnerability',
            'edit.agreement.external.unit',
            'edit.assesment.bujp',
            'edit.assesment.bujp.admin',
            'edit.assesment.bujp.unit',
            'edit.attribute',
            'edit.attribute.unit',
            'edit.audit.smp.admin',
            'edit.audit.smp.score.admin',
            'edit.audit.smp.score.unit',
            'edit.budget.master',
            'edit.category.assesment',
            'edit.criteria.audit.smp.admin',
            'edit.element.audit.smp.admin',
            'edit.email.setting',
            'edit.evidence.audit.smp.admin',
            'edit.external.auditor',
            'edit.fasum.user',
            'edit.fasumtype.admin',
            'edit.kpi.area',
            'edit.kpi.level',
            'edit.kpi.note',
            'edit.kpi.subarea',
            'edit.level.assesment',
            'edit.main.security.program.unit',
            'edit.marturity.area',
            'edit.marturity.level',
            'edit.marturity.note',
            'edit.marturity.subarea',
            'edit.marturity.unit',
            'edit.permission',
            'edit.question.assesment',
            'edit.responsible.person.unit',
            'edit.role',
            'edit.securepedia.admin',
            'edit.security.external.unit',
            'edit.security.kpi.unit',
            'edit.security.program.unit',
            'edit.security.unit',
            'edit.unit.admin',
            'edit.user',
            'edit.vulnerability',
            'send.assesment.bujp',
            'send.assesment.bujp.unit',
            'send.audit.smp.score.unit',
            'send.kpi.mmrk',
            'send.marturity.mmrk',
            'send.marturity.unit',
            'send.security.kpi.unit',
            'validate.kpi.admin',
            'validate.marturity.admin',
            'view.assesment.bujp',
            'view.assesment.bujp.admin',
            'view.assesment.bujp.unit',
            'view.attribute',
            'view.attribute.unit',
            'view.audit.smp.admin',
            'view.audit.smp.score.admin',
            'view.audit.smp.score.auditor',
            'view.audit.smp.score.unit',
            'view.budget.master',
            'view.category.assesment',
            'view.criteria.audit.smp.admin',
            'view.element.audit.smp.admin',
            'view.email.setting',
            'view.evidence.audit.smp.admin',
            'view.external.auditor',
            'view.fasum.dashboard',
            'view.fasum.user',
            'view.fasumtype.admin',
            'view.kpi.area',
            'view.kpi.level',
            'view.kpi.mmrk',
            'view.kpi.note',
            'view.kpi.subarea',
            'view.level.assesment',
            'view.main.security.program.unit',
            'view.marturity.admin',
            'view.marturity.area',
            'view.marturity.level',
            'view.marturity.mmrk',
            'view.marturity.note',
            'view.marturity.subarea',
            'view.marturity.unit',
            'view.monthly.audit.mmrk',
            'view.permission',
            'view.question.assesment',
            'view.role',
            'view.securepedia.admin',
            'view.securepedia.user',
            'view.security.kpi.admin',
            'view.security.kpi.unit',
            'view.security.program.unit',
            'view.security.unit',
            'view.unit.admin',
            'view.user',
            'view.user.vendor',
            'view.vulnerability',
            'view.workersum.unit',
    ];

    /** Role yang dikenal kode; hanya 'Admin' yang otomatis diberi semua permission. */
    protected array $roles = ['Admin', 'Pusat', 'Unit', 'MMRK', 'BUJP', 'Auditor', 'UL', 'Auditor External'];

    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        foreach ($this->permissions as $name) {
            Permission::firstOrCreate(['name' => $name, 'guard_name' => 'web']);
        }

        foreach ($this->roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
        }

        Role::where('name', 'Admin')->first()->syncPermissions($this->permissions);

        $admin = User::where('email', 'admin@admin.com')->first();
        if ($admin && !$admin->hasRole('Admin')) {
            $admin->assignRole('Admin');
        }

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    }
}
