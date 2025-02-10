<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Categorias extends Model
{
    use HasFactory;

    protected $primaryKey = 'cat_id';

    protected $fillable = [
        'nombre'
    ];

    public $timestamps = false;

    public function productos(): HasMany
    {
        return $this->HasMany(Productos::class, 'cat_id', 'cat_id');
    }

    public function categorias(): BelongsTo
    {
        return $this->belongsTo(User::class,  'cat_id', 'cat_id');
    }
}
