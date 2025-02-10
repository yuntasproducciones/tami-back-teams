<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, HasRoles, Notifiable;

    protected $primaryKey = 'user_id';

    protected $fillable = [
        'name',
        'email',
        'celular',
        'seccion',
        'fecha',
        'cat_id',
        'password'
    ];

    public $timestamps = false;

    public function categorias(): HasMany
    {
        return $this->hasMany(Categorias::class,  'cat_id', 'cat_id');
    }
    
}
