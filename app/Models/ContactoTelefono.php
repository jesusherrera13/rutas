<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactoTelefono extends Model
{
    use HasFactory;

    protected $table = "contactos_telefonos";

    protected $fillable = [
    	'id_contacto',
        'no_telefono',
        'status',
    ];
}
