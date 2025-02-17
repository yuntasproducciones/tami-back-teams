<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Auth\AuthController;
use App\Http\Controllers\Api\V1\User\UserController;
use App\Http\Controllers\Api\V1\Products\ProductoController;


Route::prefix('v1')->group(function () {

    Route::controller(AuthController::class)->prefix('auth')->group(function () {
        Route::post('/login', 'login');
        Route::post('/logout', 'logout')->middleware('auth:sanctum');
    });


    Route::controller(UserController::class)->prefix('users')->group(function(){
        Route::post('/register', 'register');
        Route::get('/listUsers','listUsers');
        Route::delete('deleteUser/{user}','deleteUser');
        Route::put('updateUser/{user}', 'updateUser');
    });


    Route::controller(ProductoController::class)->prefix('productos')->group(function(){

        Route::get('/', 'index');
        Route::get('/{id}', 'show');

        Route::group(['middleware' => ['auth:sanctum', 'role:ADMIN|USER', 'permission:ENVIAR']], function () {
            Route::post('/', 'store');
            Route::put('/{id}', 'update');
            Route::delete('/{id}', 'destroy');
        });
    });
    
});
