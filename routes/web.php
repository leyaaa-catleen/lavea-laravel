<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PerfumeController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'home')->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:6,1');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:10,1');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/collection', [PerfumeController::class, 'collection'])->name('collection');
    Route::get('/stock', [PerfumeController::class, 'stock'])->name('stock');
    Route::post('/stock', [PerfumeController::class, 'store'])->name('stock.store');
    Route::put('/stock/{perfume}', [PerfumeController::class, 'update'])->name('stock.update');
    Route::patch('/stock/{perfume}/adjust', [PerfumeController::class, 'adjust'])->name('stock.adjust');
    Route::delete('/stock/{perfume}', [PerfumeController::class, 'destroy'])->name('stock.destroy');
});
