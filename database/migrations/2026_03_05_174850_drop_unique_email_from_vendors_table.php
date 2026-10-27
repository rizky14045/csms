<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DropUniqueEmailFromVendorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Constraint ini pernah dibuat manual di database lama sebelum migrasi ini ditulis;
        // instalasi baru (dari migrasi kosong) tidak pernah membuatnya, jadi dicek dulu.
        $exists = DB::selectOne(
            "select 1 from pg_constraint where conname = 'vendors_email_unique'"
        );

        if ($exists) {
            Schema::table('vendors', function (Blueprint $table) {
                $table->dropUnique('vendors_email_unique');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vendors', function (Blueprint $table) {
            $table->unique('email');
        });
    }
}
