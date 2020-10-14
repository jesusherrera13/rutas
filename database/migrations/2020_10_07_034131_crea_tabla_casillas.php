<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreaTablaCasillas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('casillas', function (Blueprint $table) {
            $table->id();
            // $table->unsignedSmallInteger('no_seccion');
            $table->unsignedBigInteger('id_seccion');
            $table->char('id_tipo_casilla', 1);
            $table->unsignedTinyInteger('no_casilla')->nullable();
            // $table->unsignedSmallInteger('no_distrito_federal');
            // $table->unsignedSmallInteger('no_distrito_local');
            $table->unsignedBigInteger('id_asentamiento');
            $table->unsignedTinyInteger('status')->default(1)->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('user_id_create')->nullable();
            $table->unsignedBigInteger('user_id_update')->nullable();
            $table->foreign('id_seccion')->references('id')->on('secciones');
            $table->foreign('id_asentamiento')->references('id')->on('asentamientos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('casillas');
    }
}
