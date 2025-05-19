<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
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
        'mensaje_correo'
    ];

    public $timestamps = true;

    public function especificaciones()
    {
        return $this->hasMany(Especificacion::class, 'id_producto');
    }

    public function dimensiones()
    {
        return $this->hasMany(Dimension::class, 'id_producto');
    }

    public function imagenes()
    {
        return $this->hasMany(ImagenProducto::class, 'id_producto');
    }

    public function productosRelacionados()
    {
        return $this->belongsToMany(Producto::class, 'producto_relacionados', 'id_producto', 'id_relacionado');
    }

    public function blogs()
    {
        return $this->hasMany(Blog::class, 'producto_id', 'id');
    }
}
