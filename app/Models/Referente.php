<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Referente extends Model
{
    use HasFactory;

    protected $table = "referentes";

    protected $fillable = [
        'id_contacto',
    	'status',
    ];
}
