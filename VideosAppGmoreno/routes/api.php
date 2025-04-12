<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Multimedia\ApiMultimediaController;
use App\Http\Controllers\Multimedia\MultimediaController;
use App\Http\Controllers\Users\UsersController;
use App\Http\Controllers\Videos\VideosController;
use App\Http\Controllers\Videos\VideosManageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'auth'], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);

    Route::group(['middleware' => 'auth:sanctum'], function() {
        Route::get('logout', [AuthController::class, 'logout']);
    });
});

Route::get('/videos', [VideosController::class, 'index']);
Route::get('/videos/{video}', [VideosController::class, 'show'])->where('video', '[0-9]+');
Route::get('/multimedia', [MultimediaController::class, 'index']);
Route::get('/multimedia/{video}', [MultimediaController::class, 'show'])->where('video', '[0-9]+');

Route::middleware(['auth:sanctum', 'verified', 'role:video-manager|super-admin'])->group(function () {
    Route::get('/videos/manage', [VideosManageController::class, 'index']);
    Route::post('/videos/manage/create', [VideosManageController::class, 'store']);
    Route::get('/videos/manage/show/{video}', [VideosManageController::class, 'show']);
    Route::get('/videos/manage/edit/{video}', [VideosManageController::class, 'edit']);
    Route::put('/videos/manage/update/{video}', [VideosManageController::class, 'update']);
    Route::delete('/videos/manage/destroy/{video}', [VideosManageController::class, 'destroy']);
});

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/multimedia/manage', [ApiMultimediaController::class, 'index']);
    Route::post('/multimedia/manage/create', [ApiMultimediaController::class, 'store']);
    Route::get('/multimedia/manage/show/{video}', [ApiMultimediaController::class, 'show']);
    Route::get('/multimedia/manage/edit/{video}', [ApiMultimediaController::class, 'edit']);
    Route::put('/multimedia/manage/update/{video}', [ApiMultimediaController::class, 'update']);
    Route::delete('/multimedia/manage/destroy/{video}', [ApiMultimediaController::class, 'destroy']);
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/profile', [UsersController::class, 'show']);
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

