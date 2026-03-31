<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GameController;
use App\Http\Controllers\GameAdController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\CartController;

// Ruta para la Home (Catálogo Global)
Route::get('/', [GameController::class, 'index'])->name('home');

// Ruta para el detalle del juego (Comparador de precios)
Route::get('/games/{id}', [GameController::class, 'show'])->name('games.show');
// Ruta para la venta de un juego (Creador de GameAd)
Route::get('/sell', [GameAdController::class, 'create'])->name('games.sell');

// Rutas del Carrito (requieren autenticación)
Route::middleware('auth')->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::delete('/cart/{orderItem}', [CartController::class, 'remove'])->name('cart.remove');
});

// Ruta para el panel de Admin
Route::prefix('admin')->group(function () {
    // Listado y creación
    Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('admin.users.create');
    Route::post('/users', [UserController::class, 'store'])->name('admin.users.store');

    // Mostrar perfil
    Route::get('/users/{user}', [UserController::class, 'show'])->name('admin.users.show');

    // Editar / actualizar
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('admin.users.update');

    // Eliminar
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');

    
    Route::post('/users/{user}/ban', [UserController::class, 'ban'])->name('admin.users.ban');
    Route::post('/users/{user}/unban', [UserController::class, 'unban'])->name('admin.users.unban');
});