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
        'apellido1',
        'apellido2',
        'telefono',
        'email',
        'id_asentamiento',
        'id_referente',
        'id_coordinador',
    	'status',
    ];
}
