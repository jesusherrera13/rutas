<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreaTablaContactos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contactos', function (Blueprint $table) {
            $table->id();
            // $table->unsignedSmallInteger('no_seccion');
            $table->unsignedBigInteger('id_seccion');
            $table->string('nombre', 45);
            $table->string('apellido1', 45)->nullable();
            $table->string('apellido2', 45)->nullable();
            $table->string('telefono', 45)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('localidad', 45)->nullable();
            $table->string('referente', 45)->nullable();
            $table->string('direccion', 100)->nullable();
            $table->unsignedBigInteger('id_asentamiento')->nullable();
            $table->unsignedBigInteger('id_referente')->default(1)->nullable();
            $table->unsignedTinyInteger('status')->default(1)->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('user_id_create')->nullable();
            $table->unsignedBigInteger('user_id_update')->nullable();
            $table->foreign('id_seccion')->references('id')->on('secciones');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contactos');
    }
}
