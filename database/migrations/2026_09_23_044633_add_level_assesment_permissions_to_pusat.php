<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AddLevelAssesmentPermissionsToPusat extends Migration
{
    public function up()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $pusat = Role::where('name', 'Pusat')->first();
        if (!$pusat) {
            return;
        }

        foreach (['view.level.assesment', 'create.level.assesment', 'edit.level.assesment', 'delete.level.assesment'] as $name) {
            $permission = Permission::where('name', $name)->first();
            if ($permission) {
                $pusat->givePermissionTo($permission);
            }
        }
    }

    public function down()
    {
        $pusat = Role::where('name', 'Pusat')->first();
        if (!$pusat) {
            return;
        }

        foreach (['view.level.assesment', 'create.level.assesment', 'edit.level.assesment', 'delete.level.assesment'] as $name) {
            $permission = Permission::where('name', $name)->first();
            if ($permission) {
                $pusat->revokePermissionTo($permission);
            }
        }
    }
}
