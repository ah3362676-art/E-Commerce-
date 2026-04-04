<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_via_api(): void
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Mohamed',
            'email' => 'mohamed@example.com',
            'password' => '12345678',
            'password_confirmation' => '12345678',
        ]);

        $response->assertStatus(201);

        $response->assertJsonStructure([   // بنقول للسيرفر ابعتلنا البياتت دة لما ينجد الريجستير
            'message',
            'token',
            'user' => [
                'id',
                'name',
                'email',
                'role',
                'created_at',
                'updated_at',
            ],
        ]);

        $this->assertDatabaseHas('users', [     // بنتاكد انوة اتسيف الداتا بيس
            'email' => 'mohamed@example.com',
            'role' => 'user',
        ]);
    }

    public function test_user_can_login_via_api(): void
    {
        $user = User::factory()->create([
            'email' => 'mohamed@example.com',
            'password' => bcrypt('12345678'),
            'role' => 'user',
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'mohamed@example.com',
            'password' => '12345678',
        ]);

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'message',
            'token',
            'user' => [
                'id',
                'name',
                'email',
                'role',
                'created_at',
                'updated_at',
            ],
        ]);
    }

    public function test_user_cannot_login_with_wrong_password(): void
    {
        $user = User::factory()->create([
            'email' => 'mohamed@example.com',
            'password' => bcrypt('12345678'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'mohamed@example.com',
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email']);
    }

    public function test_authenticated_user_can_logout_via_api(): void
    {
        $user = User::factory()->create();

        $token = $user->createToken('api-token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/logout');

        $response->assertStatus(200);

        $response->assertJson([
            'message' => 'Logged out successfully.',
        ]);
    }

    public function test_guest_cannot_logout_via_api(): void
    {
        $response = $this->postJson('/api/logout');

        $response->assertStatus(401);
    }
}
