<?php

use App\Http\Controllers\VideosManageController;
use App\Http\Controllers\VideosController;
use Illuminate\Support\Facades\Route;

// Part publica de l'aplicació
Route::get('/', [VideosController::class, 'index'])->name('videos.index');
Route::get('/{video}', [VideosController::class, 'show'])->name('videos.show')->where('video', '[0-9]+');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
