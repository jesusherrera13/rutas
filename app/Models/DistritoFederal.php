<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class DistritoFederal extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $table = "distritos_federales";

    protected $fillable = [
    	'descripcion',
        'no_distrito',
    	'status',
    ];
}
