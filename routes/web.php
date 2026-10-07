<?php
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\PerfumeController;
use App\Http\Middleware\AdminOnly;
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

    // Customer: cart, checkout, orders
    Route::get('/cart', [CartController::class, 'show'])->name('cart');
    Route::post('/cart/{perfume}', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/{perfume}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{perfume}', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/checkout', [CartController::class, 'checkout'])->name('checkout');
    Route::get('/orders', [CartController::class, 'orders'])->name('orders');
    Route::patch('/orders/{order}/cancel', [CartController::class, 'cancel'])->name('orders.cancel');

    Route::redirect('/stock', '/admin/stock'); // old address

    // Admin only
    Route::middleware(AdminOnly::class)->prefix('admin')->group(function () {
        Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/orders', [AdminController::class, 'orders'])->name('admin.orders');
        Route::patch('/orders/{order}', [AdminController::class, 'setStatus'])->name('admin.orders.status');
        Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
        Route::patch('/users/{user}', [AdminController::class, 'setRole'])->name('admin.users.role');

        Route::get('/stock', [PerfumeController::class, 'stock'])->name('stock');
        Route::post('/stock', [PerfumeController::class, 'store'])->name('stock.store');
        Route::put('/stock/{perfume}', [PerfumeController::class, 'update'])->name('stock.update');
        Route::patch('/stock/{perfume}/adjust', [PerfumeController::class, 'adjust'])->name('stock.adjust');
        Route::delete('/stock/{perfume}', [PerfumeController::class, 'destroy'])->name('stock.destroy');
    });
});
