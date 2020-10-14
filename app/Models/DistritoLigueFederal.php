<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class DistritoLigueFederal extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $table = "distritos_ligue_federal";

    protected $fillable = [
        // 'no_distrito_local',
        // 'no_distrito_federal',
    	'id_distrito_local',
        'id_distrito_federal',
    	// 'status',
    ];

    public function distritosFederales()
    {
        return $this->belongsTo('App\DistritoLocal');
    }
}
