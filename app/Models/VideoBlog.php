<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoBlog extends Model
{
    use HasFactory;

    protected $fillable = [
        'url_video',
<<<<<<< HEAD
        'titulo_video',
        'id_blog'
    ];

    public $timestamps = true;

    public function blog()
    {
        return $this->belongsTo(Blog::class, 'id_blog', 'id');
    }
=======
        'titulo_video'
    ];

    public $timestamps = true;
>>>>>>> cb7a0679ccd3cd7e181b9be26c196fdead5f8e83
}
