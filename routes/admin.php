<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\HomeController;

// Grupo de rutas del admin
Route::group([
    'middleware' => ['admin', 'auth']
], function () {
    // Gestión de posts
    Route::get('/posts', [HomeController::class, 'index']);
    Route::post('/posts/store', [HomeController::class, 'store']);
    Route::post('/posts/{post}/update', [HomeController::class, 'update']);
    Route::delete('/posts/{post}/delete', [HomeController::class, 'destroy']);
});