<?php

namespace App\Repositories\Interfaces;

use App\Models\CartItem;
use Illuminate\Support\Collection;

interface CartRepositoryInterface
{
    public function getUserCartItems(int $userId): Collection;

    public function calculateTotal(Collection $cartItems): float|int;

    public function findUserCartItemByProductId(int $userId, int $productId): ?CartItem;

    public function create(array $data): CartItem;

    public function incrementQuantity(CartItem $cartItem, int $quantity): bool;

    public function updateQuantity(CartItem $cartItem, int $quantity): bool;

    public function delete(CartItem $cartItem): bool;
}
