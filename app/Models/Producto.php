<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
<<<<<<< HEAD
=======
use Illuminate\Database\Eloquent\Relations\HasMany;
>>>>>>> 4c583988bce5dae4ed38a58d6804b62294dc4a1b

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
<<<<<<< HEAD
        'seccion'
=======
        'seccion',
        'mensaje_correo'
>>>>>>> 4c583988bce5dae4ed38a58d6804b62294dc4a1b
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
<<<<<<< HEAD
=======

    public function interesados(): HasMany
    {
        return $this->hasMany(Interesado::class, 'producto_id', 'id');
    }
>>>>>>> 4c583988bce5dae4ed38a58d6804b62294dc4a1b
}
