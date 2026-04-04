<?php

namespace Tests\Unit;

use App\Models\User;
use Tests\TestCase;

class UserTest extends TestCase
{
    public function test_user_is_admin_returns_true_for_admin_role(): void
    {
        $user = new User([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'role' => 'admin',
            'password' => '123456789',
        ]);

        $this->assertTrue($user->isAdmin());
    }

    public function test_user_is_admin_returns_false_for_normal_user(): void
    {
        $user = new User([
            'name' => 'User',
            'email' => 'user@example.com',
            'role' => 'user',
            'password' => '12345678',
        ]);

        $this->assertFalse($user->isAdmin());
    }
}
