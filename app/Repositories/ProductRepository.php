<?php

namespace App\Repositories;

use App\Models\Product;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ProductRepository implements ProductRepositoryInterface
{
    public function getFilteredPaginatedProducts(array $filters, int $perPage = 10): LengthAwarePaginator
    {
        $query = Product::with(['category', 'images']);

        if (!empty($filters['search'])) {
            $query->where('title', 'like', '%' . $filters['search'] . '%');
        }

        if (!empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        return $query->latest()->paginate($perPage)->withQueryString();
    }

    public function findWithRelations(Product $product): Product
    {
        return $product->load(['category', 'images']);
    }

    public function findForEdit(int $id): Product
    {
        return Product::with('images')->findOrFail($id);
    }

    public function create(array $data): Product
    {
        return Product::create($data);
    }

    public function update(Product $product, array $data): bool
    {
        return $product->update($data);
    }

    public function delete(Product $product): bool
    {
        return $product->delete();
    }
}
