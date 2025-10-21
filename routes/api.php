<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\OrderController;

Route::prefix('v1')->group(function () {
    // Public order creation (guest or auth). Throttle to prevent abuse.
    Route::post('orders', [OrderController::class, 'store'])->middleware('throttle:10,1');

    // Authenticated endpoints for users to list / view their orders
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('orders', [OrderController::class, 'index']);
        Route::get('orders/{order}', [OrderController::class, 'show']);
    });
});
