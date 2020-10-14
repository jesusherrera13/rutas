<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreaTablaMunicipios extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('municipios', function (Blueprint $table) {
            $table->id();
            $table->char('id_pais', 3);
            $table->char('id_estado', 3);
            $table->char('id_municipio', 3);
            $table->string('descripcion', 100);
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
        Schema::dropIfExists('municipios');
    }
}
