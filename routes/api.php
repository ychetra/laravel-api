<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

// Add this at the top of your routes/api.php file
Route::get('/test', function() {
    return response()->json(['message' => 'API is working!']);
});

// Public routes
Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);

// Protected routes
Route::middleware('auth:api')->group(function () {
    // Auth routes
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::get('me', [AuthController::class, 'me']);

    // Product routes
    Route::apiResource('products', ProductController::class);
});

// Health check endpoint
Route::get('/health', function() {
    return response()->json(['status' => 'ok']);
});
