<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Blog;

class BlogImagenes extends Model
{
    //
    protected $fillable = [
        'ruta_imagen',
        'texto_alt',
    ];

    public $timestamps = true;
    public function blog(): BelongsTo
    {
        return $this->belongsTo(Blog::class, 'blog_id');
    }   
}
