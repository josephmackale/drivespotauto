<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\VehicleSelectorController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Frontend routes for the DriveSpot store
|
*/


/*
|--------------------------------------------------------------------------
| Storefront
|--------------------------------------------------------------------------
*/

Route::get('/', [ShopController::class, 'home'])
    ->name('home');

Route::get('/shop', [ShopController::class, 'shop'])
    ->name('shop');

Route::get('/shop/vehicle/{vehicle_key}', [ShopController::class, 'vehicle'])
    ->name('shop.vehicle');

Route::get('/product/{slug}', [ProductController::class, 'show'])
    ->name('product.show');

Route::post('/shop/vehicle/clear', [ShopController::class, 'clearVehicle'])
    ->name('shop.vehicle.clear');


Route::get('/shop/vehicle/{vehicle_key}/{category_slug}', [ShopController::class, 'vehicleCategory'])
    ->name('shop.vehicle.category');

Route::get('/shop/vehicle/{vehicle_key}/{category_slug}/{subcategory_slug}', [ShopController::class, 'vehicleSubcategory'])
    ->name('shop.vehicle.subcategory');

Route::get('/shop/category/{slug}', [ShopController::class, 'category'])
    ->name('shop.category');

Route::get('/shop/category/{category_slug}/{subcategory_slug}', [ShopController::class, 'subcategory'])
    ->name('shop.subcategory');
    
/*
|--------------------------------------------------------------------------
| Cart & Checkout
|--------------------------------------------------------------------------
*/

Route::get('/cart', [CartController::class, 'index'])
    ->name('cart');

Route::get('/checkout', [CheckoutController::class, 'index'])
    ->name('checkout');


/*
|--------------------------------------------------------------------------
| Vehicle Selector API
|--------------------------------------------------------------------------
|
| Used by Alpine.js vehicle selector
|
*/

Route::prefix('vehicle')->group(function () {

    Route::get('/makes', [VehicleSelectorController::class, 'makes'])
        ->name('vehicle.makes');

    Route::get('/models', [VehicleSelectorController::class, 'models'])
        ->name('vehicle.models');

    Route::get('/generations', [VehicleSelectorController::class, 'generations'])
        ->name('vehicle.generations');

    Route::get('/engines', [VehicleSelectorController::class, 'engines'])
        ->name('vehicle.engines');

});


/*
|--------------------------------------------------------------------------
| Debug / Development
|--------------------------------------------------------------------------
*/

Route::get('/auth-check', function () {
    return response()->json([
        'check' => auth()->check(),
        'user' => auth()->user(),
        'session_id' => session()->getId(),
    ]);
});