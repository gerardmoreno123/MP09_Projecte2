<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VideosController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/video/{id}', [VideosController::class, 'index'])->name('videos.index');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
