<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreaTablaSecciones extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('secciones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('no_seccion');
            // $table->unsignedSmallInteger('no_distrito_federal');
            // $table->unsignedSmallInteger('no_distrito_local');
            $table->unsignedBigInteger('id_distrito_federal');
            $table->unsignedBigInteger('id_distrito_local');
            $table->unsignedTinyInteger('status')->default(1)->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('user_id_create')->nullable();
            $table->unsignedBigInteger('user_id_update')->nullable();
            $table->foreign('id_distrito_federal')->references('id')->on('distritos_federales');
            $table->foreign('id_distrito_local')->references('id')->on('distritos_locales');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('secciones');
    }
}
