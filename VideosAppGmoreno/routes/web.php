<?php

use App\Http\Controllers\UsersController;
use App\Http\Controllers\UsersManageController;
use App\Http\Controllers\VideosManageController;
use App\Http\Controllers\VideosController;
use Illuminate\Support\Facades\Route;

// Part publica de l'aplicació
Route::get('/', [VideosController::class, 'index'])->name('videos.index');
Route::get('/{video}', [VideosController::class, 'show'])->name('videos.show')->where('video', '[0-9]+');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/users', [UsersController::class, 'index'])->name('users.index'); // Llista d'usuaris
    Route::get('/users/{user}', [UsersController::class, 'show'])->name('users.show')->where('user', '[0-9]+'); // Mostrar un usuari
});

// Part de gestió de vídeos
Route::middleware(['auth', 'verified', 'role:video-manager|super-admin'])->group(function () {
    // CRUD de vídeos
    Route::prefix('videos/manage')->name('videos.manage.')->group(function () {
        Route::get('/', [VideosManageController::class, 'index'])->name('index'); // Llista

        Route::get('/create', [VideosManageController::class, 'create'])->name('create'); // Formulari de creació
        Route::post('/', [VideosManageController::class, 'store'])->name('store'); // Guardar el vídeo creat

        Route::get('/{video}', [VideosManageController::class, 'show'])->name('show'); // Mostrar un vídeo

        Route::get('/{video}/edit', [VideosManageController::class, 'edit'])->name('edit'); // Formulari d'edició
        Route::put('/{video}', [VideosManageController::class, 'update'])->name('update'); // Guardar el vídeo editat

        Route::get('/{video}/delete', [VideosManageController::class, 'delete'])->name('delete'); // Confirmació d'eliminació
        Route::delete('/{video}', [VideosManageController::class, 'destroy'])->name('destroy'); // Eliminar el vídeo
    });
});

// Part de gestió d'usuaris
Route::middleware(['auth', 'verified', 'role:user-manager|super-admin'])->group(function () {
    Route::prefix('users/manage')->name('users.manage.')->group(function () {
        Route::get('/', [UsersManageController::class, 'index'])->name('index'); // Llista

        Route::get('/create', [UsersManageController::class, 'create'])->name('create'); // Formulari de creació
        Route::post('/', [UsersManageController::class, 'store'])->name('store'); // Guardar l'usuari creat

        Route::get('/{user}', [UsersManageController::class, 'show'])->name('show'); // Mostrar un usuari

        Route::get('/{user}/edit', [UsersManageController::class, 'edit'])->name('edit'); // Formulari d'edició
        Route::put('/{user}', [UsersManageController::class, 'update'])->name('update'); // Guardar l'usuari editat

        Route::get('/{user}/delete', [UsersManageController::class, 'delete'])->name('delete'); // Confirmació d'eliminació
        Route::delete('/{user}', [UsersManageController::class, 'destroy'])->name('destroy'); // Eliminar l'usuari
    });
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
