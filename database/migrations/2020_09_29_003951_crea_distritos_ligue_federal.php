<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreaDistritosLigueFederal extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('distritos_ligue_federal', function (Blueprint $table) {
            $table->id();
            // $table->unsignedSmallInteger('no_distrito_federal');
            // $table->unsignedSmallInteger('no_distrito_local');
            $table->unsignedBigInteger('id_distrito_federal'); // Requiere ser unsignedBigInteger como id foráneo
            $table->unsignedBigInteger('id_distrito_local'); // Requiere ser unsignedBigInteger como id foráneo
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('user_id_create');
            $table->unsignedBigInteger('user_id_update')->nullable();
            $table->foreign('id_distrito_local')->references('id')->on('distritos_locales');
            $table->foreign('id_distrito_federal')->references('id')->on('distritos_federales');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('distritos_ligue_federal');
    }
}
