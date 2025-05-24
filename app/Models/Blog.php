<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Blog extends Model
{
    use Hasfactory;

    protected $fillable = [
        'producto_id',
        'titulo',
        'link',
        'parrafo',
        'descripcion',
        'imagen_principal'
    ];

    public $timestamps = true;

    public function imagenes()
    {
        return $this->hasMany(ImagenBlog::class, 'id_blog'); 
    }

    public function video()
    {
        return $this->hasOne(VideoBlog::class, 'id_blog');
    }

    public function detalle()
    {
        return $this->hasOne(DetalleBlog::class, 'id_blog');
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }   
}
