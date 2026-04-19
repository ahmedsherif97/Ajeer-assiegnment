<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\BookingController;
use App\Http\Controllers\Api\V1\CartController;
use App\Http\Controllers\Api\V1\CatalogController;
use App\Http\Controllers\Api\V1\PaymentController;
use App\Http\Controllers\Api\V1\SubscriptionController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/services', [CatalogController::class, 'services']);
    Route::get('/packages', [CatalogController::class, 'packages']);

    Route::post('/payments/process', [PaymentController::class, 'process']);

    Route::middleware('auth:sanctum')->group(function () {

        Route::post('/subscriptions/trial', [SubscriptionController::class, 'startTrial']);

        Route::post('/cart/add', [CartController::class, 'add']);

        Route::post('/bookings/checkout', [BookingController::class, 'checkout']);
    });
});
