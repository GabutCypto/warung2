<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\KartuController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductTransactionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TopupController;
use Illuminate\Support\Facades\Route;

Route::get('/', [FrontController::class, 'index'])->name('front.index');
Route::get('/search', [FrontController::class, 'search'])->name('front.search');
Route::get('/details/{product:slug}', [FrontController::class, 'details'])->name('front.product.details');
Route::get('/category/{category}', [FrontController::class, 'category'])->name('front.product.category');

// Dashboard route
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Cart routes for buyer only
    Route::resource('carts', CartController::class)->middleware('role:buyer');
    Route::post('/cart/add/{productId}', [CartController::class, 'store'])->middleware('role:buyer')->name('carts.store');

    // ProductTransaction for owner or buyer
    Route::resource('product_transactions', ProductTransactionController::class)->middleware('role:owner|buyer');

    // User kartus
    Route::prefix('user')
        ->name('user.')
        ->middleware('role:owner|buyer')
        ->group(function () {
            Route::resource('kartu', KartuController::class);
        });

    // Printing kartu
    Route::get('/kartu/{kartu}/print', [KartuController::class, 'print'])->name('user.kartu.print');
});

// Admin Routes (For Owner Only)
Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('products', ProductController::class)->middleware('role:owner');
    Route::resource('categories', CategoryController::class)->middleware('role:owner');
});

// Topup Routes
Route::middleware(['auth'])->group(function () {
    // Routes for creating and managing topups for all authenticated users (both owner and buyer)
    Route::get('topups/create', [TopupController::class, 'create'])->name('topups.create');
    Route::post('topups', [TopupController::class, 'store'])->name('topups.store');
    Route::get('topups', [TopupController::class, 'index'])->name('topups.index');
    Route::put('topups/{topup}', [TopupController::class, 'update'])->name('topups.update');
    Route::get('topups/{topup}', [TopupController::class, 'show'])->name('topups.show');
});
    
    
require __DIR__.'/auth.php';