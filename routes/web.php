<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GameController;
use App\Http\Controllers\GameAdController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\User\GameController as UserGameController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\User\OrderController;

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

    // Panel usuario
    Route::get('/perfil', [ProfileController::class, 'index'])->name('profile.index');
    Route::post('/perfil', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/perfil/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::post('/perfil/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar');

    Route::get('/mis-anuncios', [UserGameController::class, 'index'])->name('games.index');
    Route::get('/mis-anuncios/create', [UserGameController::class, 'create'])->name('games.create');
    Route::post('/mis-anuncios', [UserGameController::class, 'store'])->name('games.store');

    Route::get('/mis-anuncios/{id}/edit', [UserGameController::class, 'edit'])->name('games.edit');
    Route::put('/mis-anuncios/{id}', [UserGameController::class, 'update'])->name('games.update');
    Route::delete('/mis-anuncios/{id}', [UserGameController::class, 'destroy'])->name('games.destroy');

    Route::get('/mis-pedidos', [OrderController::class, 'index'])->name('orders.index');
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
    Route::get('/users/create', [UserController::class, 'create'])->name('admin.users.create');
    Route::post('/users', [UserController::class, 'store'])->name('admin.users.store');
});