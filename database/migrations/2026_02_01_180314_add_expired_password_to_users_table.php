<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExpiredPasswordToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('session_id')->nullable();
            $table->timestamp('session_expired_date')->nullable();
            $table->timestamp('expired_password_date')->nullable();
            $table->boolean('is_password_change')->default(false);
            $table->integer('access_failed_count')->nullable();
            $table->timestamp('locked_until')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}
