<?php

namespace Tests\Feature;

use App\Models\CartItem;
use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_checkout_via_api(): void
    {
        $user = User::factory()->create();

        $category = Category::factory()->create();

        $product = Product::factory()->create([
            'category_id' => $category->id,
            'price' => 100,
            'stock' => 10,
        ]);

        CartItem::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'quantity' => 2,
        ]);

        $token = $user->createToken('api-token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/checkout', [
                'phone' => '01012345678',
                'address' => 'Cairo, Egypt',
                'notes' => 'Test order',
            ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'total' => 200,
            'status' => 'pending',
        ]);

        $this->assertDatabaseHas('order_items', [
            'product_id' => $product->id,
            'quantity' => 2,
            'price' => 100,
        ]);

        // cart cleared
        $this->assertDatabaseMissing('cart_items', [
            'user_id' => $user->id,
        ]);

        // stock decreased
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock' => 8,
        ]);
    }

    public function test_checkout_fails_if_cart_is_empty(): void
    {
        $user = User::factory()->create();

        $token = $user->createToken('api-token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/checkout', [
                'phone' => '01012345678',
                'address' => 'Cairo, Egypt',
            ]);

        $response->assertStatus(422);
    }

    public function test_authenticated_user_can_view_orders(): void
    {
        $user = User::factory()->create();

        $order = Order::factory()->create([
            'user_id' => $user->id,
        ]);

        $token = $user->createToken('api-token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/orders');

        $response->assertStatus(200);
    }

    public function test_user_cannot_view_other_users_orders(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $order = Order::factory()->create([
            'user_id' => $user2->id,
        ]);

        $token = $user1->createToken('api-token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/orders/' . $order->id);

        $response->assertStatus(403);
    }

    public function test_guest_cannot_access_orders_api(): void
    {
        $response = $this->getJson('/api/orders');

        $response->assertStatus(401);
    }
}
