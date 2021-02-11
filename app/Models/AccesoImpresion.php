<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccesoImpresion extends Model
{
    use HasFactory;

    protected $table = "accesos_impresion";

    protected $fillable = [
    	'id_usuario',
        'id_formato',
        'status',
    ];
}
