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
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'pgsql') {
            $hasConstraint = DB::selectOne("
                SELECT 1
                FROM pg_constraint c
                JOIN pg_class t ON c.conrelid = t.oid
                WHERE t.relname = 'vendors' AND c.conname = 'vendors_email_unique'
            ");

            if ($hasConstraint) {
                Schema::table('vendors', function (Blueprint $table) {
                    $table->dropUnique('vendors_email_unique');
                });
            }
        } else {
            try {
                Schema::table('vendors', function (Blueprint $table) {
                    $table->dropUnique('vendors_email_unique');
                });
            } catch (\Throwable $e) {
                // Abaikan jika constraint tidak ditemukan
            }
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
