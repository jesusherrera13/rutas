<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccesoLocal extends Model
{
    use HasFactory;

    protected $table = "accesos_distritos_locales";

    protected $fillable = [
    	'id_usuario',
        'id_distrito_local',
        'status',
    ];
}
