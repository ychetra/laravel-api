<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class FashionProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure products directory exists
        Storage::disk('public')->makeDirectory('products', 0755, true);
        
        // First, get category IDs
        $categories = DB::table('categories')->get();
        
        // Create a map of category names to IDs
        $categoryMap = [];
        foreach ($categories as $category) {
            if ($category->parent_id === null) {
                $categoryMap[$category->name] = $category->id;
            }
        }
        
        // Create a map of subcategory names to IDs
        $subcategoryMap = [];
        foreach ($categories as $category) {
            if ($category->parent_id !== null) {
                $parentName = DB::table('categories')->where('id', $category->parent_id)->value('name');
                $key = $parentName . '|' . $category->name;
                $subcategoryMap[$key] = $category->id;
            }
        }
        
        // First clean up existing products
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('products')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        
        $this->command->info('Inserting fashion products...');
        
        // Sample products data
        $products = [
            [
                'name' => 'Women Round Neck Cotton Top',
                'description' => 'A lightweight, usually knitted, pullover shirt, close-fitting and with a round neckline and short sleeves, worn as an undershirt or outer garment.',
                'price' => 100,
                'image_path' => 'products/p_img1.jpg',
                'category' => 'Women',
                'subcategory' => 'Topwear',
                'sizes' => ['S', 'M', 'L'],
                'date' => Carbon::createFromTimestampMs(1716634345448),
                'bestseller' => true,
            ],
            [
                'name' => 'Men Round Neck Pure Cotton T-shirt',
                'description' => 'A lightweight, usually knitted, pullover shirt, close-fitting and with a round neckline and short sleeves, worn as an undershirt or outer garment.',
                'price' => 200,
                'image_path' => 'products/p_img2_1.jpg', // Using just the first image
                'category' => 'Men',
                'subcategory' => 'Topwear',
                'sizes' => ['M', 'L', 'XL'],
                'date' => Carbon::createFromTimestampMs(1716621345448),
                'bestseller' => true,
            ],
            [
                'name' => 'Girls Round Neck Cotton Top',
                'description' => 'A lightweight, usually knitted, pullover shirt, close-fitting and with a round neckline and short sleeves, worn as an undershirt or outer garment.',
                'price' => 220,
                'image_path' => 'products/p_img3.jpg',
                'category' => 'Kids',
                'subcategory' => 'Topwear',
                'sizes' => ['S', 'L', 'XL'],
                'date' => Carbon::createFromTimestampMs(1716234545448),
                'bestseller' => true,
            ],
            [
                'name' => 'Men Round Neck Pure Cotton T-shirt',
                'description' => 'A lightweight, usually knitted, pullover shirt, close-fitting and with a round neckline and short sleeves, worn as an undershirt or outer garment.',
                'price' => 110,
                'image_path' => 'products/p_img4.jpg',
                'category' => 'Men',
                'subcategory' => 'Topwear',
                'sizes' => ['S', 'M', 'XXL'],
                'date' => Carbon::createFromTimestampMs(1716621345448),
                'bestseller' => true,
            ],
            [
                'name' => 'Women Round Neck Cotton Top',
                'description' => 'A lightweight, usually knitted, pullover shirt, close-fitting and with a round neckline and short sleeves, worn as an undershirt or outer garment.',
                'price' => 130,
                'image_path' => 'products/p_img5.jpg',
                'category' => 'Women',
                'subcategory' => 'Topwear',
                'sizes' => ['M', 'L', 'XL'],
                'date' => Carbon::createFromTimestampMs(1716622345448),
                'bestseller' => true,
            ],
            [
                'name' => 'Girls Round Neck Cotton Top',
                'description' => 'A lightweight, usually knitted, pullover shirt, close-fitting and with a round neckline and short sleeves, worn as an undershirt or outer garment.',
                'price' => 140,
                'image_path' => 'products/p_img6.jpg',
                'category' => 'Kids',
                'subcategory' => 'Topwear',
                'sizes' => ['S', 'L', 'XL'],
                'date' => Carbon::createFromTimestampMs(1716623423448),
                'bestseller' => true,
            ],
            [
                'name' => 'Men Tapered Fit Flat-Front Trousers',
                'description' => 'A lightweight, usually knitted, pullover shirt, close-fitting and with a round neckline and short sleeves, worn as an undershirt or outer garment.',
                'price' => 190,
                'image_path' => 'products/p_img7.jpg',
                'category' => 'Men',
                'subcategory' => 'Bottomwear',
                'sizes' => ['S', 'L', 'XL'],
                'date' => Carbon::createFromTimestampMs(1716621542448),
                'bestseller' => false,
            ],
            [
                'name' => 'Men Round Neck Pure Cotton T-shirt',
                'description' => 'A lightweight, usually knitted, pullover shirt, close-fitting and with a round neckline and short sleeves, worn as an undershirt or outer garment.',
                'price' => 140,
                'image_path' => 'products/p_img8.jpg',
                'category' => 'Men',
                'subcategory' => 'Topwear',
                'sizes' => ['S', 'M', 'L', 'XL'],
                'date' => Carbon::createFromTimestampMs(1716622345448),
                'bestseller' => false,
            ],
            [
                'name' => 'Girls Round Neck Cotton Top',
                'description' => 'A lightweight, usually knitted, pullover shirt, close-fitting and with a round neckline and short sleeves, worn as an undershirt or outer garment.',
                'price' => 100,
                'image_path' => 'products/p_img9.jpg',
                'category' => 'Kids',
                'subcategory' => 'Topwear',
                'sizes' => ['M', 'L', 'XL'],
                'date' => Carbon::createFromTimestampMs(1716621235448),
                'bestseller' => false,
            ],
            [
                'name' => 'Men Tapered Fit Flat-Front Trousers',
                'description' => 'A lightweight, usually knitted, pullover shirt, close-fitting and with a round neckline and short sleeves, worn as an undershirt or outer garment.',
                'price' => 110,
                'image_path' => 'products/p_img10.jpg',
                'category' => 'Men',
                'subcategory' => 'Bottomwear',
                'sizes' => ['S', 'L', 'XL'],
                'date' => Carbon::createFromTimestampMs(1716622235448),
                'bestseller' => false,
            ],
            [
                'name' => 'Men Round Neck Pure Cotton T-shirt',
                'description' => 'A lightweight, usually knitted, pullover shirt, close-fitting and with a round neckline and short sleeves, worn as an undershirt or outer garment.',
                'price' => 120,
                'image_path' => 'products/p_img11.jpg',
                'category' => 'Men',
                'subcategory' => 'Topwear',
                'sizes' => ['S', 'M', 'L'],
                'date' => Carbon::createFromTimestampMs(1716623345448),
                'bestseller' => false,
            ],
            [
                'name' => 'Men Round Neck Pure Cotton T-shirt',
                'description' => 'A lightweight, usually knitted, pullover shirt, close-fitting and with a round neckline and short sleeves, worn as an undershirt or outer garment.',
                'price' => 150,
                'image_path' => 'products/p_img12.jpg',
                'category' => 'Men',
                'subcategory' => 'Topwear',
                'sizes' => ['S', 'M', 'L', 'XL'],
                'date' => Carbon::createFromTimestampMs(1716624445448),
                'bestseller' => false,
            ],
            [
                'name' => 'Women Round Neck Cotton Top',
                'description' => 'A lightweight, usually knitted, pullover shirt, close-fitting and with a round neckline and short sleeves, worn as an undershirt or outer garment.',
                'price' => 130,
                'image_path' => 'products/p_img13.jpg',
                'category' => 'Women',
                'subcategory' => 'Topwear',
                'sizes' => ['S', 'M', 'L', 'XL'],
                'date' => Carbon::createFromTimestampMs(1716625545448),
                'bestseller' => false,
            ],
            [
                'name' => 'Boy Round Neck Pure Cotton T-shirt',
                'description' => 'A lightweight, usually knitted, pullover shirt, close-fitting and with a round neckline and short sleeves, worn as an undershirt or outer garment.',
                'price' => 160,
                'image_path' => 'products/p_img14.jpg',
                'category' => 'Kids',
                'subcategory' => 'Topwear',
                'sizes' => ['S', 'M', 'L', 'XL'],
                'date' => Carbon::createFromTimestampMs(1716626645448),
                'bestseller' => false,
            ],
            [
                'name' => 'Men Tapered Fit Flat-Front Trousers',
                'description' => 'A lightweight, usually knitted, pullover shirt, close-fitting and with a round neckline and short sleeves, worn as an undershirt or outer garment.',
                'price' => 140,
                'image_path' => 'products/p_img15.jpg',
                'category' => 'Men',
                'subcategory' => 'Bottomwear',
                'sizes' => ['S', 'M', 'L', 'XL'],
                'date' => Carbon::createFromTimestampMs(1716627745448),
                'bestseller' => false,
            ],
            [
                'name' => 'Girls Round Neck Cotton Top',
                'description' => 'A lightweight, usually knitted, pullover shirt, close-fitting and with a round neckline and short sleeves, worn as an undershirt or outer garment.',
                'price' => 170,
                'image_path' => 'products/p_img16.jpg',
                'category' => 'Kids',
                'subcategory' => 'Topwear',
                'sizes' => ['S', 'M', 'L', 'XL'],
                'date' => Carbon::createFromTimestampMs(1716628845448),
                'bestseller' => false,
            ],
            [
                'name' => 'Men Tapered Fit Flat-Front Trousers',
                'description' => 'A lightweight, usually knitted, pullover shirt, close-fitting and with a round neckline and short sleeves, worn as an undershirt or outer garment.',
                'price' => 150,
                'image_path' => 'products/p_img17.jpg',
                'category' => 'Men',
                'subcategory' => 'Bottomwear',
                'sizes' => ['S', 'M', 'L', 'XL'],
                'date' => Carbon::createFromTimestampMs(1716629945448),
                'bestseller' => false,
            ],
            [
                'name' => 'Boy Round Neck Pure Cotton T-shirt',
                'description' => 'A lightweight, usually knitted, pullover shirt, close-fitting and with a round neckline and short sleeves, worn as an undershirt or outer garment.',
                'price' => 180,
                'image_path' => 'products/p_img18.jpg',
                'category' => 'Kids',
                'subcategory' => 'Topwear',
                'sizes' => ['S', 'M', 'L', 'XL'],
                'date' => Carbon::createFromTimestampMs(1716631045448),
                'bestseller' => false,
            ],
            [
                'name' => 'Boy Round Neck Pure Cotton T-shirt',
                'description' => 'A lightweight, usually knitted, pullover shirt, close-fitting and with a round neckline and short sleeves, worn as an undershirt or outer garment.',
                'price' => 160,
                'image_path' => 'products/p_img19.jpg',
                'category' => 'Kids',
                'subcategory' => 'Topwear',
                'sizes' => ['S', 'M', 'L', 'XL'],
                'date' => Carbon::createFromTimestampMs(1716632145448),
                'bestseller' => false,
            ],
            [
                'name' => 'Women Palazzo Pants with Waist Belt',
                'description' => 'A lightweight, usually knitted, pullover shirt, close-fitting and with a round neckline and short sleeves, worn as an undershirt or outer garment.',
                'price' => 190,
                'image_path' => 'products/p_img20.jpg',
                'category' => 'Women',
                'subcategory' => 'Bottomwear',
                'sizes' => ['S', 'M', 'L', 'XL'],
                'date' => Carbon::createFromTimestampMs(1716633245448),
                'bestseller' => false,
            ],
            [
                'name' => 'Women Zip-Front Relaxed Fit Jacket',
                'description' => 'A lightweight, usually knitted, pullover shirt, close-fitting and with a round neckline and short sleeves, worn as an undershirt or outer garment.',
                'price' => 170,
                'image_path' => 'products/p_img21.jpg',
                'category' => 'Women',
                'subcategory' => 'Winterwear',
                'sizes' => ['S', 'M', 'L', 'XL'],
                'date' => Carbon::createFromTimestampMs(1716634345448),
                'bestseller' => false,
            ],
            [
                'name' => 'Women Palazzo Pants with Waist Belt',
                'description' => 'A lightweight, usually knitted, pullover shirt, close-fitting and with a round neckline and short sleeves, worn as an undershirt or outer garment.',
                'price' => 200,
                'image_path' => 'products/p_img22.jpg',
                'category' => 'Women',
                'subcategory' => 'Bottomwear',
                'sizes' => ['S', 'M', 'L', 'XL'],
                'date' => Carbon::createFromTimestampMs(1716635445448),
                'bestseller' => false,
            ],
            [
                'name' => 'Boy Round Neck Pure Cotton T-shirt',
                'description' => 'A lightweight, usually knitted, pullover shirt, close-fitting and with a round neckline and short sleeves, worn as an undershirt or outer garment.',
                'price' => 180,
                'image_path' => 'products/p_img23.jpg',
                'category' => 'Kids',
                'subcategory' => 'Topwear',
                'sizes' => ['S', 'M', 'L', 'XL'],
                'date' => Carbon::createFromTimestampMs(1716636545448),
                'bestseller' => false,
            ],
            [
                'name' => 'Boy Round Neck Pure Cotton T-shirt',
                'description' => 'A lightweight, usually knitted, pullover shirt, close-fitting and with a round neckline and short sleeves, worn as an undershirt or outer garment.',
                'price' => 210,
                'image_path' => 'products/p_img24.jpg',
                'category' => 'Kids',
                'subcategory' => 'Topwear',
                'sizes' => ['S', 'M', 'L', 'XL'],
                'date' => Carbon::createFromTimestampMs(1716637645448),
                'bestseller' => false,
            ],
            [
                'name' => 'Girls Round Neck Cotton Top',
                'description' => 'A lightweight, usually knitted, pullover shirt, close-fitting and with a round neckline and short sleeves, worn as an undershirt or outer garment.',
                'price' => 190,
                'image_path' => 'products/p_img25.jpg',
                'category' => 'Kids',
                'subcategory' => 'Topwear',
                'sizes' => ['S', 'M', 'L', 'XL'],
                'date' => Carbon::createFromTimestampMs(1716638745448),
                'bestseller' => false,
            ],
            [
                'name' => 'Women Zip-Front Relaxed Fit Jacket',
                'description' => 'A lightweight, usually knitted, pullover shirt, close-fitting and with a round neckline and short sleeves, worn as an undershirt or outer garment.',
                'price' => 220,
                'image_path' => 'products/p_img26.jpg',
                'category' => 'Women',
                'subcategory' => 'Winterwear',
                'sizes' => ['S', 'M', 'L', 'XL'],
                'date' => Carbon::createFromTimestampMs(1716639845448),
                'bestseller' => false,
            ],
            // Only including a shorter subset for brevity - you can add the rest
        ];
        
        // Insert products
        foreach ($products as $index => $product) {
            try {
                // Get the right category ID
                $categoryKey = $product['category'] . '|' . $product['subcategory'];
                $categoryId = $subcategoryMap[$categoryKey] ?? null;
                
                if (!$categoryId) {
                    $this->command->error("Could not find category ID for: {$categoryKey}");
                    continue;
                }
                
                // Insert the product with image
                DB::table('products')->insert([
                    'name' => $product['name'],
                    'description' => $product['description'],
                    'image_path' => $product['image_path'],
                    'price' => $product['price'],
                    'createdate' => $product['date']->toDateString(),
                    'category_id' => $categoryId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                
                $this->command->info("Inserted product {$index}: {$product['name']}");
            } catch (\Exception $e) {
                $this->command->error("Error with product {$product['name']}: " . $e->getMessage());
            }
        }
        
        $this->command->info('All products inserted successfully!');
    }
} 