<?php

use App\Http\Controllers\VideosManageController;
use App\Http\Controllers\VideosController;
use Illuminate\Support\Facades\Route;

// Part publica de l'aplicació
Route::get('/', [VideosController::class, 'index'])->name('videos.index');
Route::get('/{video}', [VideosController::class, 'show'])->name('videos.show')->where('video', '[0-9]+');

// Part privada de l'aplicació
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

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
