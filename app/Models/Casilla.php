<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Casilla extends Model
{
    use HasFactory;

    protected $table = "casillas";

    protected $fillable = [
    	'id_seccion',
        'id_tipo_casilla',
        'no_casilla',
        // 'no_distrito_federal',
        // 'no_distrito_local',
        'id_asentamiento',
    	'status',
    ];
}
