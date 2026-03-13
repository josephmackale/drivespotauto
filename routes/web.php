<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [ShopController::class, 'home'])->name('home');

Route::get('/shop', [ShopController::class, 'shop'])->name('shop');

Route::get('/product/{slug}', [ProductController::class, 'show'])->name('product.show');

Route::get('/cart', [CartController::class, 'index'])->name('cart');

Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');

Route::get('/auth-check', function () {
    return response()->json([
        'check' => auth()->check(),
        'user' => auth()->user(),
        'session_id' => session()->getId(),
    ]);
});