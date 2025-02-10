<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Detalles_Productos extends Model
{
    use HasFactory;
    
    protected $table = 'detalles_productos';

    protected $primaryKey = 'detProd_id';

    protected $fillable = [
        'prod_id',
        'titulo',
        'subtitulo',
        'descripcion',
        'longitud',
        'ancho',
        'altura',
    ];
    
    public $timestamps = false;

    public function productos(): BelongsTo
    {
        return $this->belongsTo(Productos::class, 'prod_id','prod_id');
    }
}
