<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // First seed users
        $this->call(UserSeeder::class);
        
        // Then seed categories with our fashion categories
        $this->call(CategorySeeder::class);
        
        // Then seed fashion products
        $this->call(FashionProductSeeder::class);
        
        // Finally seed site settings
        $this->call(SiteSettingsSeeder::class);
    }
}
