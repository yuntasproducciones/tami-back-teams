<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Blog extends Model
{
    use Hasfactory;

    protected $fillable = [
        'titulo',
        'parrafo',
        'imagen_principal'
    ];

    public $timestamps = true;

<<<<<<< HEAD
    public function imagenes()
    {
        return $this->hasMany(ImagenBlog::class, 'id_blog', 'id'); 
    }

    public function video()
    {
        return $this->hasOne(VideoBlog::class, 'id_blog', 'id');
    }

    public function detalle()
    {
        return $this->hasOne(DetalleBlog::class, 'id_blog', 'id');
    }
=======
>>>>>>> cb7a0679ccd3cd7e181b9be26c196fdead5f8e83
}
