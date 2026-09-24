<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateAttributePresetsTable extends Migration
{
    public function up()
    {
        Schema::create('attribute_presets', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        // nama unik tanpa membedakan huruf besar/kecil
        DB::statement('CREATE UNIQUE INDEX attribute_presets_name_unique ON attribute_presets (LOWER(name))');
    }

    public function down()
    {
        Schema::dropIfExists('attribute_presets');
    }
}
