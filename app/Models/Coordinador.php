<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coordinador extends Model
{
    use HasFactory;

    protected $table = "coordinadores";

    protected $fillable = [
        'id_contacto',
    	'status',
    ];
}
