<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreaTablaContactosEmails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contactos_emails', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_contacto');
            $table->string('email', 100)->nullable();
            $table->unsignedTinyInteger('status')->default(1)->nullable();
            $table->foreign('id_contacto')->references('id')->on('contactos');
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
        Schema::dropIfExists('contactos_emails');
    }
}
