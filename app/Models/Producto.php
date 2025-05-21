<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{

    protected $casts = [
        'especificaciones' => 'array',
    ];

    protected $fillable = [
        'nombre',
        'titulo',
        'subtitulo',
        'lema',
        'descripcion',
        'imagen_principal',
        'stock',
        'precio',
        'seccion',
        // 'mensaje_correo',
        'especificaciones'
    ];

    public $timestamps = true;

    public function dimensiones()
    {
        return $this->hasMany(Dimension::class, 'id_producto');
    }

    public function imagenes()
    {
        return $this->hasMany(ProductoImagen::class, 'producto_id');
    }

    public function productosRelacionados()
    {
        return $this->belongsToMany(Producto::class, 'producto_relacionados', 'id_producto', 'id_relacionado');
    }
}
