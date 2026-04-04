<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminOrderAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_access_admin_orders_page(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.orders.index'));

        $response->assertOk();
    }

    public function test_normal_user_cannot_access_admin_orders_page(): void
    {
        $user = User::factory()->create([
            'role' => 'user',
        ]);

        $response = $this->actingAs($user)->get(route('admin.orders.index'));

        $response->assertForbidden();
    }

    public function test_guest_cannot_access_admin_orders_page(): void
    {
        $response = $this->get(route('admin.orders.index'));

        $response->assertRedirect(route('login'));
    }


}
