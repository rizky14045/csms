<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLdapEmailToUsersTable extends Migration
{
    /** Email asli user LDAP (kolom `email` untuk user LDAP dipakai sebagai username/NID). */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('ldap_email')->nullable();
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('ldap_email');
        });
    }
}
