<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // First clear existing categories
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('categories')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        
        // Main categories
        $mainCategories = [
            [
                'name' => 'Men',
                'slug' => 'men',
                'description' => 'Men\'s clothing and accessories',
            ],
            [
                'name' => 'Women',
                'slug' => 'women',
                'description' => 'Women\'s clothing and accessories',
            ],
            [
                'name' => 'Kids',
                'slug' => 'kids',
                'description' => 'Kids\' clothing and accessories',
            ],
        ];
        
        $mainCategoryIds = [];
        
        // Insert main categories first
        foreach ($mainCategories as $category) {
            $id = DB::table('categories')->insertGetId([
                'name' => $category['name'],
                'slug' => $category['slug'],
                'description' => $category['description'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            $mainCategoryIds[$category['slug']] = $id;
        }
        
        // Define subcategories
        $subCategories = [
            // Men's subcategories
            [
                'name' => 'Topwear',
                'slug' => 'men-topwear',
                'description' => 'Men\'s shirts, t-shirts, and tops',
                'parent_id' => $mainCategoryIds['men'],
            ],
            [
                'name' => 'Bottomwear',
                'slug' => 'men-bottomwear',
                'description' => 'Men\'s pants, jeans, and shorts',
                'parent_id' => $mainCategoryIds['men'],
            ],
            [
                'name' => 'Winterwear',
                'slug' => 'men-winterwear',
                'description' => 'Men\'s jackets, sweaters, and winter clothing',
                'parent_id' => $mainCategoryIds['men'],
            ],
            
            // Women's subcategories
            [
                'name' => 'Topwear',
                'slug' => 'women-topwear',
                'description' => 'Women\'s shirts, t-shirts, and tops',
                'parent_id' => $mainCategoryIds['women'],
            ],
            [
                'name' => 'Bottomwear',
                'slug' => 'women-bottomwear',
                'description' => 'Women\'s pants, jeans, skirts, and shorts',
                'parent_id' => $mainCategoryIds['women'],
            ],
            [
                'name' => 'Winterwear',
                'slug' => 'women-winterwear',
                'description' => 'Women\'s jackets, sweaters, and winter clothing',
                'parent_id' => $mainCategoryIds['women'],
            ],
            
            // Kids' subcategories
            [
                'name' => 'Topwear',
                'slug' => 'kids-topwear',
                'description' => 'Kids\' shirts, t-shirts, and tops',
                'parent_id' => $mainCategoryIds['kids'],
            ],
            [
                'name' => 'Bottomwear',
                'slug' => 'kids-bottomwear',
                'description' => 'Kids\' pants, jeans, and shorts',
                'parent_id' => $mainCategoryIds['kids'],
            ],
        ];
        
        // Insert subcategories
        foreach ($subCategories as $category) {
            DB::table('categories')->insert([
                'name' => $category['name'],
                'slug' => $category['slug'],
                'description' => $category['description'],
                'parent_id' => $category['parent_id'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
} 