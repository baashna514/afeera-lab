<?php

use App\Http\Controllers\Api\PaymentApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public API Routes (no auth required)
|--------------------------------------------------------------------------
*/

// Simple test endpoint - check if API is working
Route::get('/test', [PaymentApiController::class, 'test']);

// Login - get Bearer token
Route::post('/login', [PaymentApiController::class, 'login']);

/*
|--------------------------------------------------------------------------
| Protected API Routes (Sanctum auth required)
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {
    // Get authenticated user
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Logout - revoke token
    Route::post('/logout', [PaymentApiController::class, 'logout']);

    // Payments API - date filter supported
    Route::get('/payments', [PaymentApiController::class, 'payments']);
});
