<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CasillaRepresentante extends Model
{
    use HasFactory;

    protected $table = "casillas_representantes";

    protected $fillable = [
    	'id_casilla',
        'id_contacto',
        'id_representante_tipo',
    	'status',
    ];
}
