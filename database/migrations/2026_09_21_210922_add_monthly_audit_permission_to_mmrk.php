<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AddMonthlyAuditPermissionToMmrk extends Migration
{
    public function up()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permission = Permission::firstOrCreate(['name' => 'view.monthly.audit.mmrk', 'guard_name' => 'web']);
        $role = Role::where('name', 'MMRK')->first();
        if ($role) {
            $role->givePermissionTo($permission);
        }
    }

    public function down()
    {
        Permission::where('name', 'view.monthly.audit.mmrk')->delete();
    }
}
