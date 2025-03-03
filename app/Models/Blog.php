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

}
