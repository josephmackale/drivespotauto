<?php

use Illuminate\Support\Facades\Route;

Route::prefix('vehicle-selector')->group(function () {
    Route::get('/makes', [\App\Http\Controllers\VehicleSelectorController::class, 'makes']);
    Route::get('/generations', [\App\Http\Controllers\VehicleSelectorController::class, 'generations']);
    Route::get('/engines', [\App\Http\Controllers\VehicleSelectorController::class, 'engines']);
});