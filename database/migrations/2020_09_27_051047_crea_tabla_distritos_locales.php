<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreaTablaDistritosLocales extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('distritos_locales', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('no_distrito');
            // $table->unsignedBigInteger('id_distrito_federal'); // Requiere ser unsignedBigInteger como id foráneo
            $table->string('descripcion', 45);
            $table->unsignedTinyInteger('status')->default(1)->nullable();
            // $table->foreign('id_distrito_federal')->references('id')->on('distritos_federales');
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('user_id_create')->nullable();
            $table->unsignedBigInteger('user_id_update')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('distritos_locales');
    }
}
