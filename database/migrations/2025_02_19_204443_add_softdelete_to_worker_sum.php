<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSoftdeleteToWorkerSum extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('responsible_persons', function (Blueprint $table) {
            $table->softDeletes();
       });
       Schema::table('security_externals', function (Blueprint $table) {
            $table->softDeletes();
       });
       Schema::table('agreement_externals', function (Blueprint $table) {
            $table->softDeletes();
       });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
