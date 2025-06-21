<?php

use App\Http\Controllers\Api\V2\V2ClienteController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Auth\AuthController;
use App\Http\Controllers\Api\V1\Blog\BlogController;
use App\Http\Controllers\Api\V1\User\UserController;
use App\Http\Controllers\Api\V2\V2ProductoController;
use App\Http\Controllers\Api\V1\Cliente\ClienteController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\Api\V1\Email\EmailController;
use App\Http\Controllers\ProductoController as ControllersProductoController;

Route::prefix('v1')->group(function () {

    Route::controller(AuthController::class)->prefix('auth')->group(function () {
        Route::post('/login', 'login');
        Route::post('/logout', 'logout')->middleware(['auth:sanctum', 'role:ADMIN|USER']);
    });

    Route::controller(UserController::class)->prefix('users')->group(function(){

        Route::middleware(['auth:sanctum', 'role:ADMIN'])->group(function () {
            Route::post('/', 'store')->name('users.store');
            Route::get('/', 'index')->name('users.index');
            Route::get('/{id}', 'show');
            Route::delete('/{id}', 'destroy')->name('users.destroy');
            Route::put('/{id}', 'update')->name('users.update'); 
        });
    });
    
    Route::controller(ClienteController::class)->prefix('clientes')->group(function () {
        Route::post('/', 'store');
        Route::get('/', 'getAll');
        Route::get('/{id}', 'show');
        Route::put('/{id}', 'update');
        Route::delete('/{id}', 'destroy');  
        
        Route::middleware(['auth:sanctum', 'role:ADMIN'])->group(function () {
            
        }); 
    });

    Route::controller(BlogController::class)->prefix('blogs')->group(function () {
        Route::get('/', 'index');
        Route::get('/link/{link}', 'showLink');
        Route::get('/{blog}', 'show');
        

        Route::middleware(['auth:sanctum', 'role:ADMIN'])->group(function () {
            
        }); 

        Route::post('/', 'store');
        Route::put('/{blog}', 'update');
        Route::delete('/{blog}', 'destroy');
    });

    Route::controller(PermissionController::class)->prefix("/permisos")->group(function () {
        Route::middleware(["auth:sanctum"])->group(function(){
            Route::get('/', 'index');
            Route::post('/', 'store');
        });
    });

    Route::controller(EmailController::class)->prefix('/emails')->group(function () {
        Route::post('/', 'sendEmail');
    });
});


Route::prefix("v2")->group(function(){
    Route::controller(V2ClienteController::class)->prefix("/clientes")->group(function(){
        Route::middleware(["auth:sanctum", "role:ADMIN"])->group(function(){
            Route::get("/", "index");
            Route::get("/{id}", 'show');
            Route::post("/", "store");
            Route::put("/{id}", "update");
            Route::delete("/{id}", "destroy");
        });
    });
    
    Route::controller(V2ProductoController::class)->prefix('productos')->group(function(){
        Route::get('/', 'paginate');
        Route::get('/all', 'index');
        Route::get('/{id}', 'show');

        Route::middleware(['auth:sanctum', 'role:ADMIN|USER', 'permission:ENVIAR'])->group(function () {
            
        });

        Route::post('/', 'store');
        Route::put('/{id}', 'update');
        Route::delete('/{id}', 'destroy');
        Route::get('/link/{link}', 'showByLink');
    });
});

