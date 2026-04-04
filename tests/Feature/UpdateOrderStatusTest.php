<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateOrderStatusTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_update_order_status(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $order = Order::factory()->create([
            'status' => 'pending',
        ]);

        $response = $this->actingAs($admin)->put(route('admin.orders.update', $order), [
            'status' => 'shipped',
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'shipped',
        ]);
    }

    public function test_normal_user_cannot_update_order_status(): void
    {
        $user = User::factory()->create([
            'role' => 'user',
        ]);

        $order = Order::factory()->create([
            'status' => 'pending',
        ]);

        $response = $this->actingAs($user)->put(route('admin.orders.update', $order), [
            'status' => 'shipped',
        ]);

        $response->assertStatus(403);
    }

    public function test_guest_cannot_update_order_status(): void
    {
        $order = Order::factory()->create([
            'status' => 'pending',
        ]);

        $response = $this->put(route('admin.orders.update', $order), [
            'status' => 'shipped',
        ]);

        $response->assertRedirect(route('login'));
    }
}
