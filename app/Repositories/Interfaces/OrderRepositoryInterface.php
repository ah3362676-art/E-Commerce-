<?php

namespace App\Repositories\Interfaces;

use App\Models\Order;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface OrderRepositoryInterface
{
    public function getUserOrdersPaginated(int $userId, int $perPage = 10): LengthAwarePaginator;

    public function getAdminOrdersPaginated(int $perPage = 10): LengthAwarePaginator;

    public function create(array $data): Order;

    public function update(Order $order, array $data): bool;
}
