<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ruta extends Model
{
    use HasFactory;

    protected $table = "rutas";

    protected $fillable = [
    	'descripcion',
        'id_distrito_federal',
        'id_distrito_local',
        'id_rg',
    	'status',
    ];
}
