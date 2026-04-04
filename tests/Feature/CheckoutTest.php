<?php

namespace Tests\Feature;

use App\Models\CartItem;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_place_order(): void
    {
        $user = User::factory()->create([
            'role' => 'user',
        ]);

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

        $response = $this->actingAs($user)->post(route('checkout.store'), [
            'phone' => '01012345678',
            'address' => 'Cairo, Egypt',
            'notes' => 'Please call before delivery',
        ]);

        $response->assertRedirect(route('orders.index'));

        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'total' => 200.00,
            'status' => 'pending',
            'phone' => '01012345678',
            'address' => 'Cairo, Egypt',
        ]);

        $this->assertDatabaseHas('order_items', [
            'product_id' => $product->id,
            'quantity' => 2,
            'price' => 100.00,
        ]);

        $this->assertDatabaseMissing('cart_items', [
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock' => 8,
        ]);
    }

    public function test_guest_cannot_place_order(): void
    {
        $response = $this->post(route('checkout.store'), [
            'phone' => '01012345678',
            'address' => 'Cairo, Egypt',
            'notes' => 'Test note',
        ]);

        $response->assertRedirect(route('login'));
    }

    public function test_checkout_fails_when_cart_is_empty(): void
    {
        $user = User::factory()->create([
            'role' => 'user',
        ]);

        $response = $this->actingAs($user)->post(route('checkout.store'), [
            'phone' => '01012345678',
            'address' => 'Cairo, Egypt',
            'notes' => 'Test note',
        ]);

        $response->assertRedirect(route('cart.index'));
        $response->assertSessionHas('error', 'Your cart is empty.');
    }
}
