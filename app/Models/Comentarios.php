<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Comentarios extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_coment';

    protected $fillable = [
        'comentarios'
    ];

    public $timestamps = false;

    public function secciones(): HasMany
    {
        return $this->hasMany(Comentarios::class,'id_coment','id_coment');
    }
}
