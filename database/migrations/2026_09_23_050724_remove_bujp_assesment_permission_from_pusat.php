<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RemoveBujpAssesmentPermissionFromPusat extends Migration
{
    public function up()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 'view.assesment.bujp' menggerakkan halaman pengisian assesment milik VENDOR
        // (bujp.assesment.index). Pusat sudah punya 'view.assesment.bujp.admin' untuk
        // melihat/mengelola assesment lewat admin.assesment.index - izin yang satu ini
        // seharusnya cuma untuk role BUJP, bukan Pusat (menyebabkan menu sidebar dobel).
        $pusat = Role::where('name', 'Pusat')->first();
        $permission = Permission::where('name', 'view.assesment.bujp')->first();

        if ($pusat && $permission) {
            $pusat->revokePermissionTo($permission);
        }
    }

    public function down()
    {
        $pusat = Role::where('name', 'Pusat')->first();
        $permission = Permission::where('name', 'view.assesment.bujp')->first();

        if ($pusat && $permission) {
            $pusat->givePermissionTo($permission);
        }
    }
}
