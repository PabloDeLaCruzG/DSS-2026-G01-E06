<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\GameAdController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\User\GameController as UserGameController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\User\ReviewController;
use App\Http\Controllers\User\OrderController;
use App\Http\Controllers\User\ProfessionalProfileController;
use App\Http\Controllers\Admin\ProfessionalProfileController as AdminProfessionalProfileController;
use App\Http\Controllers\Admin\GameController as AdminGameController;
use App\Http\Controllers\FavoriteController;


// Autenticación
Route::middleware('guest')->group(function () {
    Route::get('/login',    [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',   [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register',[AuthController::class, 'register']);
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');


// Ruta para la Home (Catálogo Global)
Route::get('/', [GameController::class, 'index'])->name('home');

// Ruta para el detalle del juego (Comparador de precios)
Route::get('/games/{id}', [GameController::class, 'show'])->name('games.show');
// Ruta para poner y quitar reseñas
Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store')->middleware('auth');
Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy')->middleware('auth');
// Ruta para el perfil público de un usuario
Route::get('/users/{user}', [App\Http\Controllers\User\ProfileController::class, 'show'])->name('users.show');
// Ruta para la venta de un juego (Creador de GameAd)
Route::get('/sell', [GameAdController::class, 'create'])->name('games.sell');
// Ruta para los juegos favoritos
Route::get('/mis-favoritos', [FavoriteController::class, 'index'])->name('favorites.index');
Route::post('/favorites/{game}/toggle', [FavoriteController::class, 'toggle'])->name('favorites.toggle');

// Rutas del Carrito (requieren autenticación)
Route::middleware('auth')->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
    Route::post('/cart/coupon', [CartController::class, 'applyCoupon'])->name('cart.coupon');
    Route::delete('/cart/coupon', [CartController::class, 'removeCoupon'])->name('cart.coupon.remove');
    Route::delete('/cart/{orderItem}', [CartController::class, 'remove'])->name('cart.remove');

    Route::get('/perfil', [ProfileController::class, 'index'])->name('profile.index');
    Route::post('/perfil', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/perfil/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::post('/perfil/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar');
    Route::delete('/perfil', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/mis-anuncios', [UserGameController::class, 'index'])->name('games.index');
    Route::get('/mis-anuncios/create', [UserGameController::class, 'create'])->name('games.create');
    Route::post('/mis-anuncios', [UserGameController::class, 'store'])->name('games.store');

    Route::get('/mis-anuncios/{id}/edit', [UserGameController::class, 'edit'])->name('games.edit');
    Route::put('/mis-anuncios/{id}', [UserGameController::class, 'update'])->name('games.update');
    Route::delete('/mis-anuncios/{id}', [UserGameController::class, 'destroy'])->name('games.destroy');

    Route::get('/mis-pedidos', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/perfil/profesional', [ProfessionalProfileController::class, 'create'])->name('professional.create');
    Route::post('/perfil/profesional', [ProfessionalProfileController::class, 'store'])->name('professional.store');
});

    
// Ruta para el panel de Admin (requiere auth + rol admin)
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
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

    Route::get('/professionals', [AdminProfessionalProfileController::class, 'index'])->name('admin.professionals.index');
    Route::post('/professionals/{profile}/verify', [AdminProfessionalProfileController::class, 'verify'])->name('admin.professionals.verify');
    Route::post('/professionals/{profile}/reject', [AdminProfessionalProfileController::class, 'reject'])->name('admin.professionals.reject');

    // Catálogo de juegos
    Route::get('/games', [AdminGameController::class, 'index'])->name('admin.games.index');
    Route::get('/games/create', [AdminGameController::class, 'create'])->name('admin.games.create');
    Route::post('/games', [AdminGameController::class, 'store'])->name('admin.games.store');
    Route::get('/games/{game}/edit', [AdminGameController::class, 'edit'])->name('admin.games.edit');
    Route::put('/games/{game}', [AdminGameController::class, 'update'])->name('admin.games.update');
    Route::delete('/games/{game}', [AdminGameController::class, 'destroy'])->name('admin.games.destroy');
    Route::post('/games/{game}/toggle-publish', [AdminGameController::class, 'togglePublish'])->name('admin.games.toggle-publish');
});