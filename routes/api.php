<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\V1\Auth\AuthController;
use App\Http\Controllers\Api\V1\Blog\BlogController;
use App\Http\Controllers\Api\V1\User\UserController;
<<<<<<< HEAD
use App\Http\Controllers\Api\V1\Products\ProductoController;
use App\Http\Controllers\Api\V1\Cliente\ClienteController;
=======
use App\Http\Controllers\Api\V1\Productos\ProductoController;
use App\Http\Controllers\Api\V1\Cliente\ClienteController;


>>>>>>> cb7a0679ccd3cd7e181b9be26c196fdead5f8e83

Route::prefix('v1')->group(function () {

    
    Route::controller(AuthController::class)->prefix('auth')->group(function () {
        Route::post('/login', 'login');
        Route::post('/logout', 'logout')->middleware(['auth:sanctum', 'role:ADMIN|USER']);
    });


    Route::controller(UserController::class)->prefix('users')->group(function(){
        
<<<<<<< HEAD
        Route::post('/', 'store');

        Route::middleware(['auth:sanctum', 'role:ADMIN'])->group(function () {
=======
        Route::middleware(['auth:sanctum', 'role:ADMIN'])->group(function () {
            Route::post('/', 'store');
>>>>>>> cb7a0679ccd3cd7e181b9be26c196fdead5f8e83
            Route::get('/', 'index');
            Route::delete('/{id}', 'destroy');
            Route::put('/{id}', 'update');
        });
    });

    Route::controller(ClienteController::class)->prefix('clientes')->group(function () {
        Route::post('/', 'store');
        
        Route::middleware(['auth:sanctum', 'role:ADMIN'])->group(function () {
            Route::get('/', 'index');
            Route::get('/{id}', 'show');
            Route::put('/{id}', 'update');
            Route::delete('/{id}', 'destroy');            
        });
    });

    Route::controller(ProductoController::class)->prefix('productos')->group(function(){

        Route::get('/', 'index');
        Route::get('/{id}', 'show');

        Route::middleware(['auth:sanctum', 'role:ADMIN|USER', 'permission:ENVIAR'])->group(function () {
            Route::post('/', 'store');
            Route::put('/{id}', 'update');
            Route::delete('/{id}', 'destroy');
        });
    });

<<<<<<< HEAD
    Route::controller(BlogController::class)->prefix('blogs')->group(function () {
        Route::get('/', 'index');
        Route::get('/{id}', 'show');

        Route::middleware(['auth:sanctum', 'role:ADMIN'])->group(function () {
            Route::post('/', 'store');
            Route::put('/{id}', 'update');
            Route::delete('/{id}', 'destroy');
        });
    });

=======
>>>>>>> cb7a0679ccd3cd7e181b9be26c196fdead5f8e83
});
