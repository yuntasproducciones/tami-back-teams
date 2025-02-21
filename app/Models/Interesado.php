<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Interesado extends Model
{
    use HasFactory;

    protected $fillable = [
        'usuario_id',
        'producto_id'
    ];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuarios_Registro::class, 'usuario_id', 'id');
    }

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class, 'producto_id', 'id');
    }
}
