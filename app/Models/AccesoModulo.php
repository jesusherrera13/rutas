<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccesoModulo extends Model
{
    use HasFactory;

    protected $table = "accesos_modulos";

    protected $fillable = [
    	'id_usuario',
        'id_modulo',
        'status',
    ];
}
