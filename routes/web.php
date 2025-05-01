<?php

use Illuminate\Support\Facades\Route;
use App\Models\Category;
use App\Models\Product;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test-categories', function () {
    $categories = Category::all();
    return response()->json([
        'success' => true,
        'categories' => $categories
    ]);
});

Route::get('/test-products', function () {
    $products = Product::all();
    return response()->json([
        'success' => true,
        'products' => $products
    ]);
});

Route::get('/test-relationships', function () {
    try {
        $categories = Category::with('products')->get();
        
        // Filter out categories with no products
        $categoriesWithProducts = $categories->filter(function ($category) {
            return $category->products->count() > 0;
        });
        
        return response()->json([
            'success' => true,
            'data' => $categoriesWithProducts->values()
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
    }
});
