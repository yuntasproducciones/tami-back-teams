<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Usuarios_Registro extends Model
{
    use HasFactory;

    protected $table = 'usuarios_registro';

    protected $fillable = [
        'name',
        'email',
        'celular',
    ];

    public $timestamps = true;

    public function interesados(): HasMany
    {
        return $this->hasMany(Interesado::class, 'usuario_id', 'id');
    }
}
