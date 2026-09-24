<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CreateBudgetMastersTable extends Migration
{
    protected $permissions = ['view.budget.master', 'create.budget.master', 'edit.budget.master', 'delete.budget.master'];

    protected $roles = ['Admin', 'Pusat', 'Unit', 'UL'];

    public function up()
    {
        Schema::create('budget_masters', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('unit_id')->index();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('type')->nullable(); // pemeliharaan | administrasi
            $table->string('kode_aktifitas')->nullable();
            $table->string('kode_prk')->nullable();
            $table->string('deskripsi_kegiatan')->nullable();
            $table->decimal('jumlah_anggaran', 20, 2)->nullable();
            $table->decimal('penyerapan_anggaran', 20, 2)->nullable();
            $table->string('keterangan')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Baris laporan adalah salinan mandiri; source_id hanya jejak asal master (bukan relasi hidup).
        Schema::table('laporan_bulanan_biaya', function (Blueprint $table) {
            $table->unsignedBigInteger('source_id')->nullable()->index();
        });

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        foreach ($this->permissions as $name) {
            $permission = Permission::firstOrCreate(['name' => $name, 'guard_name' => 'web']);
            foreach ($this->roles as $roleName) {
                $role = Role::where('name', $roleName)->first();
                if ($role) {
                    $role->givePermissionTo($permission);
                }
            }
        }
    }

    public function down()
    {
        Schema::table('laporan_bulanan_biaya', function (Blueprint $table) {
            $table->dropColumn('source_id');
        });
        Schema::dropIfExists('budget_masters');

        Permission::whereIn('name', $this->permissions)->delete();
    }
}
