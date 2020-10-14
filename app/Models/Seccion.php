<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class Seccion extends Model
{
    use HasFactory;

    protected $table = "secciones";

    protected $fillable = [
    	'no_seccion',
        'id_distrito_local',
        'id_distrito_federal',
    	'status',
    ];
}
