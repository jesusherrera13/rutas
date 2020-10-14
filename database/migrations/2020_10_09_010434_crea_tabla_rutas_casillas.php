<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreaTablaRutasCasillas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rutas_casillas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_casilla')->nullable();
            $table->unsignedBigInteger('id_ruta')->nullable();
            $table->unsignedTinyInteger('status')->default(1)->nullable();
            $table->foreign('id_casilla')->references('id')->on('casillas');
            $table->foreign('id_ruta')->references('id')->on('rutas');
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
        Schema::dropIfExists('rutas_casillas');
    }
}
