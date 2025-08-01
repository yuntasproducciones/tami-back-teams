<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\ProductoImagen;
use App\Models\Dimension;
use App\Http\Controllers\Api\V1\Blog\BlogController;

class Producto extends Model
{
    protected $casts = [
        'meta_data' => 'array',
        'especificaciones' => 'array',
    ];


    protected $fillable = [
        'nombre',
        'link',
        'titulo',
        'link',
        'subtitulo',
        'stock',
        'precio',
        'seccion',
        'descripcion',
        'meta_data',
        'especificaciones',
        // 'imagenes'
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

    public function blogs()
    {
        return $this->hasMany(Blog::class, 'producto_id', 'id');
    }
    public function especificaciones(): HasMany
    {
        return $this->hasMany(Especificacion::class, 'producto_id');
    }

}
