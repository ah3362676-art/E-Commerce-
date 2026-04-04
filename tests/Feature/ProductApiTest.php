<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_api_returns_only_active_products(): void
    {
        $category = Category::factory()->create();

        Product::factory()->create([
            'category_id' => $category->id,
            'title' => 'Active Product',
            'slug' => 'active-product',
            'is_active' => true,
        ]);

        Product::factory()->create([
            'category_id' => $category->id,
            'title' => 'Inactive Product',
            'slug' => 'inactive-product',
            'is_active' => false,
        ]);

        $response = $this->getJson('/api/products');

        $response->assertStatus(200);

        $response->assertJsonFragment([    // 💡 assertJsonFragment = "دور على جزء من JSON"
            'title' => 'Active Product',
        ]);

        $response->assertJsonMissing([    //👉 ممنوع المنتج ده يظهر
            'title' => 'Inactive Product',
        ]);
    }

    public function test_api_can_show_single_product(): void
    {
        $category = Category::factory()->create();

        $product = Product::factory()->create([
            'category_id' => $category->id,
            'title' => 'iPhone 15',
            'slug' => 'iphone-15',
            'is_active' => true,
        ]);

        $response = $this->getJson('/api/products/' . $product->id);

        $response->assertStatus(200);

        $response->assertJsonFragment([
            'title' => 'iPhone 15',
            'slug' => 'iphone-15',
        ]);
    }

    public function test_api_returns_404_for_missing_product(): void
    {
        $response = $this->getJson('/api/products/999999');

        $response->assertStatus(404);
    }
}
