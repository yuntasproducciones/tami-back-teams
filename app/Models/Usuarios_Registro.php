<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Usuarios_Registro extends Model
{
    use HasFactory;

    protected $table = 'usuarios_registro';

    protected $primaryKey = 'usuRegis_id';

    protected $fillable = [
        'name',
        'email',
        'celular',
        'fecha',
        'sec_id',
    ];

    public $timestamps = false;

    public function secciones(): HasMany
    {
        return $this->hasMany(Seccion::class, 'sec_id', 'sec_id');
    }
}
