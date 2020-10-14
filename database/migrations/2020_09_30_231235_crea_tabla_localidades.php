<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreaTablaLocalidades extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('localidades', function (Blueprint $table) {
            $table->id();
            $table->char('id_pais', 3);
            $table->char('id_estado', 3);
            $table->char('id_municipio', 3);
            $table->char('id_localidad', 4);
            $table->string('descripcion', 100);
            $table->char('id_ambito', 4)->nullable();
            $table->unsignedTinyInteger('status')->default(1)->nullable();
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
        Schema::dropIfExists('localidades');
    }
}
