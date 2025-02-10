<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Category\CategoriasController;
use App\Http\Controllers\Contact\ContactoController;
use App\Http\Controllers\ProductsDetails\Detalles_ProductosController;
use App\Http\Controllers\Products\ProductosController;
use App\Http\Controllers\User\UserController;

Route::controller(AuthController::class)->prefix('auth')->group(function(){
    Route::post('/login', 'login');
    Route::post('/logout', 'logout')->middleware('auth:sanctum');
});

Route::controller(CategoriasController::class)->prefix('category')->group(function(){

});

Route::controller(ContactoController::class)->prefix('contact')->group(function () {
    Route::group(['middleware' => ['auth:sanctum', 'role:ADMIN|USER', 'permission:ENVIAR']], function () {
        Route::get('/show', 'showAll');
        Route::post('/create', 'createContact');
        Route::put('/update/{contacto}', 'updateContact');
        Route::delete('/delete/{contacto}', 'destroyContact');
    });
});

Route::controller(Detalles_ProductosController::class)->prefix('details')->group(function(){

});


Route::controller(ProductosController::class)->prefix('products')->group(function(){
    Route::group(['middleware' => ['auth:sanctum', 'role:ADMIN|USER', 'permission:ENVIAR']], function () {
        Route::post('/create', 'createProduct');
        Route::get('/show', 'showAll');
        Route::put('/update/{productos}', 'updateProduct');
        Route::delete('/delete/{productos}', 'destroyProduct');
    });
});

Route::controller(UserController::class)->prefix('users')->group(function(){
    Route::post('/register', 'register');
    Route::get('/listUsers','listUsers');
    Route::delete('deleteUser/{user}','deleteUser');
    Route::put('updateUser/{user}', 'updateUser');
});
