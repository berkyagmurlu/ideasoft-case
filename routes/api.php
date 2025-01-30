<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\DiscountController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::prefix('v1')->group(function () {
    // Auth routes
    Route::post('auth/login', [AuthController::class, 'login']);
    Route::post('auth/register', [AuthController::class, 'register']);

    // Protected routes
    Route::middleware('auth:api')->group(function () {
        // Auth routes
        Route::prefix('auth')->group(function () {
            Route::post('logout', [AuthController::class, 'logout']);
            Route::post('refresh', [AuthController::class, 'refresh']);
            Route::get('me', [AuthController::class, 'me']);
        });

        // Category routes
        Route::apiResource('categories', CategoryController::class);

        // Product routes
        Route::apiResource('products', ProductController::class);
        Route::get('categories/{category}/products', [ProductController::class, 'byCategory']);

        // Order routes
        Route::prefix('orders')->group(function () {
            Route::get('/', [OrderController::class, 'index']);
            Route::post('/', [OrderController::class, 'store']);
            Route::get('/{id}', [OrderController::class, 'show']);
            Route::patch('/{id}/status', [OrderController::class, 'updateStatus']);
        });

        // Discount routes
        Route::apiResource('discounts', DiscountController::class);
        Route::get('orders/{order}/discounts', [DiscountController::class, 'calculateDiscount']);
    });
});
