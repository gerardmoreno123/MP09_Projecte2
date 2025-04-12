<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Series\SeriesController;
use App\Http\Controllers\Series\SeriesManageController;
use App\Http\Controllers\Users\UsersController;
use App\Http\Controllers\Users\UsersManageController;
use App\Http\Controllers\Videos\VideosController;
use App\Http\Controllers\Videos\VideosManageController;
use Illuminate\Support\Facades\Route;

// Part publica de l'aplicació

// Visualització de vídeos
Route::get('/', [VideosController::class, 'index'])->name('videos.index');
Route::get('/{video}', [VideosController::class, 'show'])->name('videos.show')->where('video', '[0-9]+');


Route::middleware(['auth', 'verified'])->group(function () {
    // Visualització d'usuaris
    Route::get('/users', [UsersController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [UsersController::class, 'show'])->name('users.show')->where('user', '[0-9]+');

    // Visualització de sèries
    Route::get('/series', [SeriesController::class, 'index'])->name('series.index');
    Route::get('/series/{serie}', [SeriesController::class, 'show'])->name('series.show')->where('serie', '[0-9]+');

    // Gestio de vídeos per a regular usuaris
    Route::get('/videos/create', [VideosController::class, 'create'])->name('videos.create');
    Route::post('/videos', [VideosController::class, 'store'])->name('videos.store');
    Route::get('/videos/{video}/edit', [VideosController::class, 'edit'])->name('videos.edit')->where('video', '[0-9]+');
    Route::put('/videos/{video}', [VideosController::class, 'update'])->name('videos.update')->where('video', '[0-9]+');
    Route::get('/videos/{video}/delete', [VideosController::class, 'delete'])->name('videos.delete')->where('video', '[0-9]+');
    Route::delete('/videos/{video}', [VideosController::class, 'destroy'])->name('videos.destroy')->where('video', '[0-9]+');
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

// Part de gestió de sèries
Route::middleware(['auth', 'verified', 'role:serie-manager|super-admin'])->group(function () {
    Route::prefix('series/manage')->name('series.manage.')->group(function () {
        Route::get('/', [SeriesManageController::class, 'index'])->name('index'); // Llista

        Route::get('/create', [SeriesManageController::class, 'create'])->name('create'); // Formulari de creació
        Route::post('/', [SeriesManageController::class, 'store'])->name('store'); // Guardar la sèrie creada

        Route::get('/{serie}', [SeriesManageController::class, 'show'])->name('show'); // Mostrar una sèrie

        Route::get('/{serie}/edit', [SeriesManageController::class, 'edit'])->name('edit'); // Formulari d'edició
        Route::put('/{serie}', [SeriesManageController::class, 'update'])->name('update'); // Guardar la sèrie editada

        Route::get('/{serie}/delete', [SeriesManageController::class, 'delete'])->name('delete'); // Confirmació d'eliminació
        Route::delete('/{serie}', [SeriesManageController::class, 'destroy'])->name('destroy'); // Eliminar la sèrie
    });
});


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    'role:video-manager|user-manager|serie-manager|super-admin'
])->prefix('dashboard')->name('dashboard.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('index');
});
