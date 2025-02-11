<?php

use App\Models\User;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VideosController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/video/{id}', [VideosController::class, 'show'])->name('videos.show');
Route::get('/video-tested-by', [VideosController::class, 'testedBy']);
Route::get('/user-tested-by', [User::class, 'testedBy']);

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
