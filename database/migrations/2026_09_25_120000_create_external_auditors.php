<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CreateExternalAuditors extends Migration
{
    protected $managePermissions = ['view.external.auditor', 'create.external.auditor', 'edit.external.auditor', 'delete.external.auditor'];

    public function up()
    {
        Schema::create('external_auditors', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->unique();
            $table->unsignedBigInteger('unit_id')->index();
            $table->date('expired_at');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Akses per data audit dipisah dari tabel `auditors` karena tabel itu
        // dibangun ulang setiap Pusat mengubah tim auditor.
        Schema::create('external_auditor_audits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('audit_smp_data_id')->index();
            $table->timestamps();

            $table->unique(['user_id', 'audit_smp_data_id']);
        });

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $external = Role::firstOrCreate(['name' => 'Auditor External', 'guard_name' => 'web']);
        $external->givePermissionTo(Permission::firstOrCreate(['name' => 'view.audit.smp.score.auditor', 'guard_name' => 'web']));

        foreach ($this->managePermissions as $name) {
            $permission = Permission::firstOrCreate(['name' => $name, 'guard_name' => 'web']);
            foreach (['Unit', 'Pusat', 'Admin'] as $roleName) {
                $role = Role::where('name', $roleName)->first();
                if ($role) {
                    $role->givePermissionTo($permission);
                }
            }
        }
    }

    public function down()
    {
        Schema::dropIfExists('external_auditor_audits');
        Schema::dropIfExists('external_auditors');

        Permission::whereIn('name', $this->managePermissions)->delete();
        Role::where('name', 'Auditor External')->delete();
    }
}
