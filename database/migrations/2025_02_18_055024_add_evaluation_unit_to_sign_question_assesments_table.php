<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEvaluationUnitToSignQuestionAssesmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sign_question_assesments', function (Blueprint $table) {
            $table->integer('evaluation_unit')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sign_question_assesments', function (Blueprint $table) {
            $table->dropColumn('evaluation_unit');
        });
    }
}
