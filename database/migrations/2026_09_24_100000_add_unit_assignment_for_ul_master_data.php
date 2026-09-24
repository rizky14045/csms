<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AddUnitAssignmentForUlMasterData extends Migration
{
    private $tables = ['securities', 'responsible_persons', 'security_externals', 'agreement_externals', 'security_programs'];

    public function up()
    {
        foreach ($this->tables as $table) {
            if (!Schema::hasColumn($table, 'unit_id')) {
                Schema::table($table, function (Blueprint $t) {
                    $t->unsignedBigInteger('unit_id')->nullable()->index();
                });
            }

            DB::statement("UPDATE {$table} SET unit_id = u.unit_id FROM users u WHERE {$table}.user_id = u.id AND {$table}.unit_id IS NULL");
        }

        DB::statement("UPDATE attributes SET unit_id = u.unit_id FROM users u WHERE attributes.user_id = u.id AND attributes.unit_id IS NULL");

        Schema::table('attributes', function (Blueprint $t) {
            if (!Schema::hasColumn('attributes', 'parent_attribute_id')) {
                $t->unsignedBigInteger('parent_attribute_id')->nullable()->index();
            }
            if (!Schema::hasColumn('attributes', 'contract_total')) {
                $t->integer('contract_total')->nullable();
            }
        });

        // UL tidak boleh mengubah attribute (hanya dikelola unit induk).
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        $ul = Role::where('name', 'UL')->first();
        if ($ul) {
            foreach (['create.attribute.unit', 'edit.attribute.unit', 'delete.attribute.unit'] as $name) {
                $p = Permission::where('name', $name)->first();
                if ($p) {
                    $ul->revokePermissionTo($p);
                }
            }
        }
    }

    public function down()
    {
        Schema::table('attributes', function (Blueprint $t) {
            $t->dropColumn(['parent_attribute_id', 'contract_total']);
        });

        foreach ($this->tables as $table) {
            Schema::table($table, function (Blueprint $t) {
                $t->dropColumn('unit_id');
            });
        }

        $ul = Role::where('name', 'UL')->first();
        if ($ul) {
            foreach (['create.attribute.unit', 'edit.attribute.unit', 'delete.attribute.unit'] as $name) {
                $p = Permission::where('name', $name)->first();
                if ($p) {
                    $ul->givePermissionTo($p);
                }
            }
        }
    }
}
