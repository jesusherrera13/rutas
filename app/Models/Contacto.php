<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contacto extends Model
{
    use HasFactory;

    protected $table = "contactos";

    protected $fillable = [
    	'id_seccion',
        'nombre',
        'apellido1',
        'apellido2',
        'telefono',
        'clave_elector',
        'email',
        'id_asentamiento',
        'direccion',
        'id_referente',
        'id_coordinador',
    	'status',
    ];
}
