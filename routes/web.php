<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GameController;

Route::get('/', function () {
    return view('welcome');
});

// Esta es la ruta para probar tu vista de detalle:
Route::get('/games/{id}', [GameController::class, 'show'])->name('games.show');
