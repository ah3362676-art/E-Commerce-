<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_product(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $category = Category::factory()->create();

        $response = $this->actingAs($admin)->post(route('products.store'), [
            'category_id' => $category->id,
            'title' => 'iPhone 15',
            'slug' => 'iphone-15',
            'description' => 'Test description',
            'price' => 25000,
            'stock' => 10,
            'is_active' => 1,
        ]);

        $response->assertRedirect(route('products.index'));

        $this->assertDatabaseHas('products', [
            'category_id' => $category->id,
            'title' => 'iPhone 15',
            'slug' => 'iphone-15',
            'price' => 25000,
            'stock' => 10,
            'is_active' => 1,
        ]);
    }

    public function test_product_creation_requires_valid_data(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $response = $this->actingAs($admin)->post(route('products.store'), [
            'category_id' => '',
            'title' => '',
            'slug' => '',
            'description' => 'Test description',
            'price' => -5,
            'stock' => -1,
            'is_active' => '',
        ]);

        $response->assertSessionHasErrors([
            'category_id',
            'title',
            'slug',
            'price',
            'stock',
            'is_active',
        ]);
    }

    public function test_normal_user_cannot_create_product_if_route_is_admin_only(): void
    {
        $user = User::factory()->create([
            'role' => 'user',
        ]);

        $category = Category::factory()->create();

        $response = $this->actingAs($user)->post(route('products.store'), [
            'category_id' => $category->id,
            'title' => 'Samsung S24',
            'slug' => 'samsung-s24',
            'description' => 'Test description',
            'price' => 20000,
            'stock' => 5,
            'is_active' => 1,
        ]);

        $response->assertStatus(403);
    }
}
