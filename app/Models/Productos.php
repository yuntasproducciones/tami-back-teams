<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Productos extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'prod_id';

    protected $fillable = [
        'imagen_principal',
        'imagen_sec_1',
        'imagen_sec_2',
        'imagen_sec_3',
        'descripcion',
        'cat_id'
    ];
    
    public $timestamps = false;

    public function categorias(): BelongsTo
    {
        return $this->belongsTo(Categorias::class,  'cat_id', 'cat_id');
    }

    public function detalles_productos(): HasMany
    {
        return $this->hasMany(Detalles_Productos::class, 'prod_id','prod_id');
    }
}
