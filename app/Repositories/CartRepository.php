<?php

namespace App\Repositories;

use App\Models\CartItem;
use Illuminate\Support\Collection;
use App\Repositories\Interfaces\CartRepositoryInterface;

class CartRepository implements CartRepositoryInterface
{
    public function getUserCartItems(int $userId): Collection
    {
        return CartItem::with(['product.images', 'product.category'])
            ->where('user_id', $userId)
            ->latest()
            ->get()
            ->filter(function ($item) {
                return $item->product !== null;
            });
    }

    public function calculateTotal(Collection $cartItems): float|int
    {
        return $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });
    }

    public function findUserCartItemByProductId(int $userId, int $productId): ?CartItem
    {
        return CartItem::where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();
    }

    public function create(array $data): CartItem
    {
        return CartItem::create($data);
    }

    public function incrementQuantity(CartItem $cartItem, int $quantity): bool
    {
        return $cartItem->increment('quantity', $quantity) > 0;
    }

    public function updateQuantity(CartItem $cartItem, int $quantity): bool
    {
        return $cartItem->update([
            'quantity' => $quantity,
        ]);
    }

    public function delete(CartItem $cartItem): bool
    {
        return $cartItem->delete();
    }
}
