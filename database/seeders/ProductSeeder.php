<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // First, let's clear existing products to avoid duplicates
        DB::table('products')->truncate();
        
        $products = [
            [
                'name' => 'iPhone 15 Pro',
                'description' => 'Latest Apple smartphone with A16 chip',
                'price' => 999.99,
                'createdate' => now()->format('Y-m-d'),
                'image_path' => 'products/iphone-15-pro.jpg',
                'category_id' => 2, // Smartphones
            ],
            [
                'name' => 'Samsung Galaxy S24',
                'description' => 'Latest Samsung flagship with Snapdragon processor',
                'price' => 899.99,
                'createdate' => now()->format('Y-m-d'),
                'image_path' => 'products/samsung-galaxy-s24.jpg',
                'category_id' => 2, // Smartphones
            ],
            [
                'name' => 'MacBook Pro M3',
                'description' => 'Powerful Apple laptop with M3 chip',
                'price' => 1499.99,
                'createdate' => now()->format('Y-m-d'),
                'image_path' => 'products/macbook-pro-m3.jpg',
                'category_id' => 3, // Laptops
            ],
            [
                'name' => 'Dell XPS 15',
                'description' => 'Premium Windows laptop with Intel Core i9',
                'price' => 1299.99,
                'createdate' => now()->format('Y-m-d'),
                'image_path' => 'products/dell-xps-15.jpg',
                'category_id' => 3, // Laptops
            ],
            [
                'name' => 'Men\'s Casual T-Shirt',
                'description' => 'Comfortable cotton t-shirt for men',
                'price' => 19.99,
                'createdate' => now()->format('Y-m-d'),
                'image_path' => 'products/mens-casual-t-shirt.jpg',
                'category_id' => 7, // Men's Clothing
            ],
            [
                'name' => 'Women\'s Summer Dress',
                'description' => 'Lightweight summer dress for women',
                'price' => 39.99,
                'createdate' => now()->format('Y-m-d'),
                'image_path' => 'products/womens-summer-dress.jpg',
                'category_id' => 8, // Women's Clothing
            ],
            [
                'name' => 'Coffee Maker',
                'description' => 'Programmable coffee maker with thermal carafe',
                'price' => 79.99,
                'createdate' => now()->format('Y-m-d'),
                'image_path' => 'products/coffee-maker.jpg',
                'category_id' => 9, // Home & Kitchen
            ],
            [
                'name' => 'Yoga Mat',
                'description' => 'Non-slip yoga mat with carrying strap',
                'price' => 29.99,
                'createdate' => now()->format('Y-m-d'),
                'image_path' => 'products/yoga-mat.jpg',
                'category_id' => 10, // Sports & Outdoors
            ],
        ];

        foreach ($products as $product) {
            DB::table('products')->insert([
                'name' => $product['name'],
                'description' => $product['description'],
                'image_path' => $product['image_path'],
                'price' => $product['price'],
                'createdate' => $product['createdate'],
                'category_id' => $product['category_id'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
} 