<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Seccion extends Model
{
    protected $table = 'seccion';

    use HasFactory;

    protected $primaryKey = 'sec_id';

    protected $fillable = [
        'seccion',
        'id_coment'
    ];

    public $timestamps = false;

    public function usuarios_registro(): BelongsTo
    {
        return $this->belongsTo(Usuarios_Registro::class, 'sec_id','sec_id');
    }

    public function comentarios(): HasMany
    {
        return $this->hasMany(Comentarios::class,'id_coment','id_coment');
    }
}
