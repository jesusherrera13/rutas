<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RutaCasilla extends Model
{
    use HasFactory;

    protected $table = "rutas_casillas";

    protected $fillable = [
        'id_casilla',
        'id_ruta',
    	'status',
    ];
}
