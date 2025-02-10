<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Contacto extends Model
{
    use HasFactory;

    protected $table = 'contacto';

    protected $primaryKey = 'contacto_id';

    protected $fillable = [
        'nombre',
        'apellido',
        'telefono',
        'email',
        'seccion',
        'fecha_creacion'
    ];
    
    public $timestamps = false;

}
