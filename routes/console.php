<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Log;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('test:categories', function () {
    try {
        $categories = Category::with('products')->get();
        $this->info('Total number of categories: ' . $categories->count());
        
        // Filter categories with products
        $categoriesWithProducts = $categories->filter(function ($category) {
            return $category->products->count() > 0;
        });
        
        $this->info('Number of categories with products: ' . $categoriesWithProducts->count());
        
        foreach ($categoriesWithProducts as $category) {
            $this->line("Category: {$category->name} (ID: {$category->id})");
            $this->line("  Products count: " . $category->products->count());
            foreach ($category->products as $product) {
                $this->line("  - {$product->name} (ID: {$product->id})");
            }
        }
        
        $this->info('Success! The relationship is working.');
    } catch (\Exception $e) {
        $this->error('Error: ' . $e->getMessage());
        $this->error($e->getTraceAsString());
    }
})->purpose('Test the category-product relationship');

Artisan::command('test:all-categories', function () {
    try {
        $categories = Category::with('products')->get();
        $this->info('Total number of categories: ' . $categories->count());
        
        foreach ($categories as $category) {
            $this->line("Category: {$category->name} (ID: {$category->id})");
            $this->line("  Products count: " . $category->products->count());
            
            if ($category->products->count() > 0) {
                foreach ($category->products as $product) {
                    $this->line("  - {$product->name} (ID: {$product->id})");
                }
            }
        }
        
        $this->info('Success! All categories displayed.');
    } catch (\Exception $e) {
        $this->error('Error: ' . $e->getMessage());
        $this->error($e->getTraceAsString());
    }
})->purpose('Display all categories with or without products');
