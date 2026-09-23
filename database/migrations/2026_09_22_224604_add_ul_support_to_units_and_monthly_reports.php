<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AddUlSupportToUnitsAndMonthlyReports extends Migration
{
    public function up()
    {
        Schema::table('units', function (Blueprint $t) {
            $t->unsignedBigInteger('parent_unit_id')->nullable();
        });

        Schema::table('monthly_reports', function (Blueprint $t) {
            // UL mengirim ke unit induk dulu; send_status/send_date tetap dipakai
            // khusus untuk pengiriman unit/pusat ke Pusat/MMRK.
            $t->boolean('sent_to_parent')->default(false);
            $t->date('sent_to_parent_date')->nullable();
        });

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $ul = Role::firstOrCreate(['name' => 'UL', 'guard_name' => 'web']);

        // UL hanya boleh mengisi Laporan Bulanan. Rute monthly-audit sendiri tidak
        // digerbang oleh permission (hanya kepemilikan data), tapi tombol "Tambah"
        // di tiap tab mengarah ke controller master data berikut yang DIGERBANG
        // oleh permission - jadi UL perlu persis izin ini, tidak lebih.
        $names = [
            'view.security.unit', 'create.security.unit', 'edit.security.unit', 'delete.security.unit',
            'view.attribute.unit', 'create.attribute.unit', 'edit.attribute.unit', 'delete.attribute.unit',
            'view.workersum.unit', 'create.workersum.unit', 'edit.workersum.unit', 'delete.workersum.unit',
            'view.responsible.person.unit', 'create.responsible.person.unit', 'edit.responsible.person.unit', 'delete.responsible.person.unit',
            'view.security.external.unit', 'create.security.external.unit', 'edit.security.external.unit', 'delete.security.external.unit',
            'view.agreement.external.unit', 'create.agreement.external.unit', 'edit.agreement.external.unit', 'delete.agreement.external.unit',
            'view.security.program.unit', 'create.security.program.unit', 'edit.security.program.unit', 'delete.security.program.unit',
            'view.main.security.program.unit', 'create.main.security.program.unit', 'edit.main.security.program.unit', 'delete.main.security.program.unit',
        ];

        foreach ($names as $name) {
            $permission = Permission::where('name', $name)->first();
            if ($permission) {
                $ul->givePermissionTo($permission);
            }
        }
    }

    public function down()
    {
        Schema::table('monthly_reports', function (Blueprint $t) {
            $t->dropColumn(['sent_to_parent', 'sent_to_parent_date']);
        });

        Schema::table('units', function (Blueprint $t) {
            $t->dropColumn('parent_unit_id');
        });
    }
}
