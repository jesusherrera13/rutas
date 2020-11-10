<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccesoFederal extends Model
{
    use HasFactory;

    protected $table = "accesos_distritos_federales";

    protected $fillable = [
    	'id_usuario',
        'id_distrito_federal',
        'status',
    ];
}
