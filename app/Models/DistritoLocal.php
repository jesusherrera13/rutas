<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class DistritoLocal extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $table = "distritos_locales";

    protected $fillable = [
    	'descripcion',
        'no_distrito',
        // 'id_distrito_federal',
    	'status',
    ];

    public function distritosFederales()
    {
        return $this->hasMany('App\Models\DistritoLigueFederal', 'id_distrito_local');
    }
}
