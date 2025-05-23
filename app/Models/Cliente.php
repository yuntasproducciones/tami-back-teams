<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cliente extends Model
{
    protected $table = "clientes";
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'celular',
    ];

    public $timestamps = true;
}