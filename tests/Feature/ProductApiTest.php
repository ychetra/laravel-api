<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_get_all_products(): void
    {
        Product::factory(3)->create();

        $response = $this->getJson('/api/products');

        $response->assertStatus(200)    
                 ->assertJsonStructure([
                     'success',
                     'data' => [
                         '*' => ['id', 'name', 'description', 'price', 'createdate', 'created_at', 'updated_at']
                     ]
                 ]);
    }

    public function test_can_create_product(): void
    {
        $productData = [
            'name' => 'Test Product',
            'description' => 'This is a test product',
            'price' => 99.99,
            'createdate' => '2023-01-01'
        ];

        $response = $this->postJson('/api/products', $productData);

        $response->assertStatus(201)
                 ->assertJsonStructure([
                     'success',
                     'data' => ['id', 'name', 'description', 'price', 'createdate', 'created_at', 'updated_at']
                 ]);
                 
        $this->assertDatabaseHas('products', ['name' => 'Test Product']);
    }

    public function test_can_update_product(): void
    {
        $product = Product::factory()->create();
        
        $updateData = [
            'name' => 'Updated Product',
            'price' => 149.99
        ];

        $response = $this->putJson("/api/products/{$product->id}", $updateData);

        $response->assertStatus(200)
                 ->assertJsonPath('data.name', 'Updated Product')
                 ->assertJsonPath('data.price', '149.99');
                 
        $this->assertDatabaseHas('products', ['id' => $product->id, 'name' => 'Updated Product']);
    }

    public function test_can_delete_product(): void
    {
        $product = Product::factory()->create();

        $response = $this->deleteJson("/api/products/{$product->id}");

        $response->assertStatus(200)
                 ->assertJson(['success' => true]);
                 
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }
} 