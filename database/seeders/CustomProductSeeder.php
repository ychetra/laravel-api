<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CustomProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure products directory exists
        Storage::disk('public')->makeDirectory('products', 0755, true);
        
        // Sample products data
        $products = [
            // Electronics (Category ID: 1)
            [
                'name' => 'Wireless Headphones',
                'description' => 'Premium wireless headphones with noise cancellation',
                'price' => 129.99,
                'createdate' => now()->format('Y-m-d'),
                'image_path' => 'products/wireless-headphones.jpg',
                'category_id' => 1,
            ],
            [
                'name' => 'Smart Speaker',
                'description' => 'Voice-controlled smart speaker with premium sound',
                'price' => 89.99,
                'createdate' => now()->format('Y-m-d'),
                'image_path' => 'products/smart-speaker.jpg',
                'category_id' => 1,
            ],
            
            // Smartphones (Category ID: 2)
            [
                'name' => 'Google Pixel 8',
                'description' => 'Latest Google smartphone with advanced camera',
                'price' => 799.99,
                'createdate' => now()->format('Y-m-d'),
                'image_path' => 'products/google-pixel-8.jpg',
                'category_id' => 2,
            ],
            [
                'name' => 'iPhone 15',
                'description' => 'Apple iPhone 15 with A16 chip and improved camera',
                'price' => 999.99,
                'createdate' => now()->format('Y-m-d'),
                'image_path' => 'products/iphone-15.jpg',
                'category_id' => 2,
            ],
            
            // Laptops (Category ID: 3)
            [
                'name' => 'Dell XPS 13',
                'description' => 'Ultra-thin laptop with InfinityEdge display',
                'price' => 1299.99,
                'createdate' => now()->format('Y-m-d'),
                'image_path' => 'products/dell-xps-13.jpg',
                'category_id' => 3,
            ],
            [
                'name' => 'MacBook Air M2',
                'description' => 'Lightweight MacBook with M2 chip and stunning display',
                'price' => 1199.99,
                'createdate' => now()->format('Y-m-d'),
                'image_path' => 'products/macbook-air-m2.jpg',
                'category_id' => 3,
            ],
            
            // Clothing (Category ID: 4)
            [
                'name' => 'Denim Jacket',
                'description' => 'Classic denim jacket with modern fit',
                'price' => 59.99,
                'createdate' => now()->format('Y-m-d'),
                'image_path' => 'products/denim-jacket.jpg',
                'category_id' => 4,
            ],
            [
                'name' => 'Winter Coat',
                'description' => 'Warm winter coat with water-resistant shell',
                'price' => 129.99,
                'createdate' => now()->format('Y-m-d'),
                'image_path' => 'products/winter-coat.jpg',
                'category_id' => 4,
            ],
            
            // Men's Clothing (Category ID: 7)
            [
                'name' => 'Men\'s Polo Shirt',
                'description' => 'Cotton polo shirt with classic design',
                'price' => 34.99,
                'createdate' => now()->format('Y-m-d'),
                'image_path' => 'products/mens-polo-shirt.jpg',
                'category_id' => 7,
            ],
            [
                'name' => 'Men\'s Jeans',
                'description' => 'Slim-fit jeans with stretch denim',
                'price' => 49.99,
                'createdate' => now()->format('Y-m-d'),
                'image_path' => 'products/mens-jeans.jpg',
                'category_id' => 7,
            ],
            
            // Women's Clothing (Category ID: 8)
            [
                'name' => 'Women\'s Blouse',
                'description' => 'Elegant blouse with floral pattern',
                'price' => 39.99,
                'createdate' => now()->format('Y-m-d'),
                'image_path' => 'products/womens-blouse.jpg',
                'category_id' => 8,
            ],
            [
                'name' => 'Women\'s Skirt',
                'description' => 'A-line skirt with comfortable elastic waistband',
                'price' => 45.99,
                'createdate' => now()->format('Y-m-d'),
                'image_path' => 'products/womens-skirt.jpg',
                'category_id' => 8,
            ],
            
            // Home & Kitchen (Category ID: 9)
            [
                'name' => 'Blender',
                'description' => 'High-powered blender for smoothies and more',
                'price' => 79.99,
                'createdate' => now()->format('Y-m-d'),
                'image_path' => 'products/blender.jpg',
                'category_id' => 9,
            ],
            [
                'name' => 'Toaster Oven',
                'description' => 'Compact toaster oven with multiple cooking functions',
                'price' => 89.99,
                'createdate' => now()->format('Y-m-d'),
                'image_path' => 'products/toaster-oven.jpg',
                'category_id' => 9,
            ],
            [
                'name' => 'Cookware Set',
                'description' => '10-piece non-stick cookware set',
                'price' => 149.99,
                'createdate' => now()->format('Y-m-d'),
                'image_path' => 'products/cookware-set.jpg',
                'category_id' => 9,
            ],
            [
                'name' => 'Cutlery Set',
                'description' => '24-piece stainless steel cutlery set',
                'price' => 59.99,
                'createdate' => now()->format('Y-m-d'),
                'image_path' => 'products/cutlery-set.jpg',
                'category_id' => 9,
            ],
            
            // Sports & Outdoors (Category ID: 10)
            [
                'name' => 'Basketball',
                'description' => 'Official size and weight basketball',
                'price' => 29.99,
                'createdate' => now()->format('Y-m-d'),
                'image_path' => 'products/basketball.jpg',
                'category_id' => 10,
            ],
            [
                'name' => 'Tennis Racket',
                'description' => 'Professional tennis racket with cover',
                'price' => 89.99,
                'createdate' => now()->format('Y-m-d'),
                'image_path' => 'products/tennis-racket.jpg',
                'category_id' => 10,
            ],
            [
                'name' => 'Camping Tent',
                'description' => '4-person waterproof camping tent',
                'price' => 129.99,
                'createdate' => now()->format('Y-m-d'),
                'image_path' => 'products/camping-tent.jpg',
                'category_id' => 10,
            ],
            [
                'name' => 'Hiking Backpack',
                'description' => 'Durable 40L hiking backpack with multiple compartments',
                'price' => 79.99,
                'createdate' => now()->format('Y-m-d'),
                'image_path' => 'products/hiking-backpack.jpg',
                'category_id' => 10,
            ],
        ];

        // First clean up existing products
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('products')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        
        $this->command->info('Inserting 20 products...');
        
        // Insert products without images first
        foreach ($products as $product) {
            try {
                // Insert the product without an image
                DB::table('products')->insert([
                    'name' => $product['name'],
                    'description' => $product['description'],
                    'image_path' => null, // We'll skip the image for now
                    'price' => $product['price'],
                    'createdate' => $product['createdate'],
                    'category_id' => $product['category_id'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                
                $this->command->info("Inserted product: {$product['name']}");
            } catch (\Exception $e) {
                $this->command->error("Error with product {$product['name']}: " . $e->getMessage());
            }
        }
        
        $this->command->info('All products inserted successfully!');
    }
}
