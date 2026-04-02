<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GameController;
use App\Http\Controllers\GameAdController;
use App\Http\Controllers\Admin\UserController;

// Ruta para la Home (Catálogo Global)
Route::get('/', [GameController::class, 'index'])->name('home');

// Ruta para el detalle del juego (Comparador de precios)
Route::get('/games/{id}', [GameController::class, 'show'])->name('games.show');
// Ruta para la venta de un juego (Creador de GameAd)
Route::get('/sell', [GameAdController::class, 'create'])->name('games.sell');

//Ruta para el panel de Admin
Route::prefix('admin')->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::post('/users/{user}/ban', [UserController::class, 'ban'])->name('admin.users.ban');
    Route::post('/users/{user}/unban', [UserController::class, 'unban'])->name('admin.users.unban');
});