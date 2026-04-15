<?php

namespace App\Repositories;

use App\Models\Order;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Repositories\Interfaces\OrderRepositoryInterface;

class OrderRepository implements OrderRepositoryInterface
{


    public function getUserOrdersPaginated(int $userId, int $perPage = 10): LengthAwarePaginator
    {
        return Order::with('items.product')
            ->where('user_id', $userId)
            ->latest()
            ->paginate($perPage);
    }

    public function getAdminOrdersPaginated(int $perPage = 10): LengthAwarePaginator
    {
        return Order::with(['user', 'items.product'])
            ->latest()
            ->paginate($perPage);
    }

    public function create(array $data): Order
    {
        return Order::create($data);
    }

    public function update(Order $order, array $data): bool
    {
        return $order->update($data);
    }
}
