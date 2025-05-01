<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SiteSettingController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

// Add this at the top of your routes/api.php file
Route::get('/test', function() {
    return response()->json(['message' => 'API is working!']);
});

// Public routes
Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);

// Public category routes for testing
Route::get('public/categories', [CategoryController::class, 'index']);
Route::get('public/categories/with-products', [CategoryController::class, 'indexWithProducts']);
Route::get('public/categories/{category}', [CategoryController::class, 'show']);

// Protected routes
Route::middleware('auth:api')->group(function () {
    // Auth routes
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::get('me', [AuthController::class, 'me']);

    // Product routes
    Route::apiResource('products', ProductController::class);

    // Category routes
    Route::apiResource('categories', CategoryController::class);
    Route::get('categories-with-products', [CategoryController::class, 'indexWithProducts']);

    // Site Settings routes
    Route::get('settings', [SiteSettingController::class, 'index']);
    Route::post('settings', [SiteSettingController::class, 'update']);
    
    // Order routes
    Route::apiResource('orders', OrderController::class);
    
    // Admin routes
    Route::middleware('admin')->group(function () {
        Route::get('admin/orders', [OrderController::class, 'adminIndex']);
        Route::put('admin/orders/{id}', [OrderController::class, 'adminUpdate']);
    });
});

// Health check endpoint
Route::get('/health', function() {
    return response()->json(['status' => 'ok']);
});
