<?php
// Modelos del framework
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// Modelos de los controladores
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HomeController;

// Rutas de autenticación
Auth::routes([
    'register'  => false,
    'reset'     => false
]);

Route::get('/admin/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/admin/login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// Grupo de rutas de los usuarios visitantes
Route::group([
    // 'middleware' => 'guest'
], function() {
    Route::get('/', [HomeController::class, 'show']);
    Route::get('/post', [HomeController::class, 'show']);
    Route::get('/comment', [HomeController::class, 'show']);
    Route::get('/analytic', [HomeController::class, 'show']);
    Route::get('/post/{post}', [HomeController::class, 'show']);
    Route::get('/all/posts', [HomeController::class, 'index']);
    Route::get('/comments/approved/{post}', [HomeController::class, 'allApproved']);
    Route::post('/comment/new', [HomeController::class, 'setComment']);
    Route::post('/post/visit/new', [HomeController::class, 'countVisit']);
});

// Grupo de rutas usuarios autenticados
Route::group([
    'middleware' => 'auth'
], function() {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::get('/home', [HomeController::class, 'show'])->name('home');
});
