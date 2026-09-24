<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class GrantPusatViewQuestionAndLevelAssesment extends Migration
{
    /**
     * Segitiga expand di master data Assesment hanya dirender jika role punya
     * view.question.assesment (kategori -> indikator) dan view.level.assesment
     * (indikator -> level). Tanpa itu tombolnya berupa teks biasa yang tidak bisa diklik.
     */
    protected $permissions = ['view.question.assesment', 'view.level.assesment'];

    public function up()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $pusat = Role::where('name', 'Pusat')->first();
        if (!$pusat) {
            return;
        }

        foreach ($this->permissions as $name) {
            $permission = Permission::firstOrCreate(['name' => $name, 'guard_name' => 'web']);
            $pusat->givePermissionTo($permission);
        }
    }

    public function down()
    {
        // tidak dicabut: izin ini bisa saja sudah dimiliki Pusat sebelum migrasi ini
    }
}
