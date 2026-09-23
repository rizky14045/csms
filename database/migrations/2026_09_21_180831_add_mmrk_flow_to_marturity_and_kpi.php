<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AddMmrkFlowToMarturityAndKpi extends Migration
{
    public function up()
    {
        // 0 draft, 1 dikirim ke MMRK, 2 dikirim ke Pusat (terkunci), 3 validasi selesai
        foreach (['marturities', 'kpis'] as $table) {
            Schema::table($table, function (Blueprint $t) {
                $t->unsignedTinyInteger('status')->default(0);
            });

            // Data lama yang sudah terkirim dianggap selesai
            DB::table($table)->where('send_status', true)->update(['status' => 3]);
        }

        Schema::create('marturity_file_checks', function (Blueprint $t) {
            $t->id();
            $t->unsignedBigInteger('marturity_id');
            $t->unsignedBigInteger('level_id');
            $t->string('filename');
            $t->unsignedBigInteger('checked_by')->nullable();
            $t->timestamps();
            $t->unique(['level_id', 'filename']);
        });

        Schema::create('kpi_level_checks', function (Blueprint $t) {
            $t->id();
            $t->unsignedBigInteger('kpi_id');
            $t->unsignedBigInteger('level_id')->unique();
            $t->unsignedBigInteger('checked_by')->nullable();
            $t->timestamps();
        });

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $mmrk = Role::firstOrCreate(['name' => 'MMRK', 'guard_name' => 'web']);
        foreach (['view.marturity.mmrk', 'send.marturity.mmrk', 'view.kpi.mmrk', 'send.kpi.mmrk'] as $name) {
            $mmrk->givePermissionTo(Permission::firstOrCreate(['name' => $name, 'guard_name' => 'web']));
        }

        $map = [
            'view.marturity.admin'     => 'validate.marturity.admin',
            'view.security.kpi.admin'  => 'validate.kpi.admin',
        ];
        foreach ($map as $existing => $new) {
            $perm = Permission::firstOrCreate(['name' => $new, 'guard_name' => 'web']);
            $source = Permission::where('name', $existing)->first();
            if ($source) {
                foreach ($source->roles as $role) {
                    $role->givePermissionTo($perm);
                }
            }
        }
    }

    public function down()
    {
        Schema::dropIfExists('kpi_level_checks');
        Schema::dropIfExists('marturity_file_checks');

        foreach (['marturities', 'kpis'] as $table) {
            Schema::table($table, function (Blueprint $t) {
                $t->dropColumn('status');
            });
        }
    }
}
