<?php

namespace App\Repositories\Interfaces;

use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ProductRepositoryInterface
{
    public function getFilteredPaginatedProducts(array $filters, int $perPage = 10): LengthAwarePaginator;

    public function findWithRelations(Product $product): Product;

    public function findForEdit(int $id): Product;

    public function create(array $data): Product;

    public function update(Product $product, array $data): bool;

    public function delete(Product $product): bool;
}
