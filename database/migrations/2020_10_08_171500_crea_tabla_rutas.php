<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreaTablaRutas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rutas', function (Blueprint $table) {
            $table->id();
            $table->string('descripcion', 45);
            $table->unsignedBigInteger('id_rg')->nullable();
            // $table->unsignedSmallInteger('no_distrito_federal');
            // $table->unsignedSmallInteger('no_distrito_local');
            $table->unsignedBigInteger('id_distrito_federal'); // Requiere ser unsignedBigInteger como id foráneo
            $table->unsignedBigInteger('id_distrito_local'); // Requiere ser unsignedBigInteger como id foráneo
            $table->unsignedTinyInteger('status')->default(1)->nullable();
            $table->foreign('id_distrito_local')->references('id')->on('distritos_locales');
            $table->foreign('id_distrito_federal')->references('id')->on('distritos_federales');
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('user_id_create');
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
        Schema::dropIfExists('rutas');
    }
}
