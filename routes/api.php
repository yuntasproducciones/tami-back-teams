<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Auth\AuthController;
use App\Http\Controllers\Api\V1\User\UserController;
use App\Http\Controllers\Api\V1\Products\ProductoController;
<<<<<<< HEAD
use App\Http\Controllers\Api\V1\UsuariosRegistro\UsuariosRegistroController;
=======
use App\Http\Controllers\Api\V1\Cliente\ClienteController;
>>>>>>> 4c583988bce5dae4ed38a58d6804b62294dc4a1b

Route::prefix('v1')->group(function () {

    Route::controller(AuthController::class)->prefix('auth')->group(function () {
        Route::post('/login', 'login');
        Route::post('/logout', 'logout')->middleware('auth:sanctum');
    });


    Route::controller(UserController::class)->prefix('users')->group(function(){
<<<<<<< HEAD
        Route::post('/register', 'register');
        Route::get('/listUsers','listUsers');
        Route::delete('deleteUser/{user}','deleteUser');
        Route::put('updateUser/{user}', 'updateUser');
=======
        
        Route::post('/register', 'register');

        Route::middleware(['auth:sanctum', 'role:ADMIN'])->group(function () {
            Route::get('/listUsers', 'listUsers');
            Route::delete('/deleteUser/{user}', 'deleteUser');
            Route::put('/updateUser/{user}', 'updateUser');
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
>>>>>>> 4c583988bce5dae4ed38a58d6804b62294dc4a1b
    });

    Route::controller(ProductoController::class)->prefix('productos')->group(function(){

        Route::get('/', 'index');
        Route::get('/{id}', 'show');

<<<<<<< HEAD
        Route::group(['middleware' => ['auth:sanctum', 'role:ADMIN|USER', 'permission:ENVIAR']], function () {
=======
        Route::middleware(['auth:sanctum', 'role:ADMIN|USER', 'permission:ENVIAR'])->group(function () {
>>>>>>> 4c583988bce5dae4ed38a58d6804b62294dc4a1b
            Route::post('/', 'store');
            Route::put('/{id}', 'update');
            Route::delete('/{id}', 'destroy');
        });
    });

<<<<<<< HEAD
    Route::controller(UsuariosRegistroController::class)->prefix('userRegistro')->group(function () {
        Route::post('/registroUsuarios','registroUsuarios');
        Route::get('/showRegistroUsuarios', 'showRegistroUsuarios');
        Route::put('/updateRegistroUser/{usuarios_Registro}','updateRegistroUser');
        Route::delete('/destroyRegistroUser/{usuarios_Registro}','destroyRegistroUser');
    });
});
=======
});
>>>>>>> 4c583988bce5dae4ed38a58d6804b62294dc4a1b
