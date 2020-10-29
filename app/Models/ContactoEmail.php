<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactoEmail extends Model
{
    use HasFactory;

    protected $table = "contactos_emails";

    protected $fillable = [
    	'id_contacto',
        'email',
        'status',
    ];
}
