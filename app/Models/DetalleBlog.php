<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleBlog extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo_blog',
        'subtitulo_beneficio',
        'id_blog'
    ];

    public $timestamps = true;
<<<<<<< HEAD

    public function blog()
    {
        return $this->belongsTo(Blog::class, 'id_blog', 'id');
    }
=======
>>>>>>> cb7a0679ccd3cd7e181b9be26c196fdead5f8e83
}
