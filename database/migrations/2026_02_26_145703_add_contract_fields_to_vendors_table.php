<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddContractFieldsToVendorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vendors', function (Blueprint $table) {
            $table->string('contract_number')->nullable()->after('address');

            $table->date('start_date')->nullable()
                  ->after('contract_number');

            $table->date('end_date')->nullable()
                  ->after('start_date');
            $table->foreignId('user_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vendors', function (Blueprint $table) {
            $table->dropColumn([
                'contract_number',
                'start_date',
                'end_date'
            ]);
        });
    }
}
