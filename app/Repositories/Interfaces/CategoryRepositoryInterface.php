<?php

namespace App\Repositories\Interfaces;

use App\Models\Category;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface CategoryRepositoryInterface
{
    public function getAllPaginated(int $perPage = 10): LengthAwarePaginator;

    public function findById(int $id): Category;

    public function create(array $data): Category;

    public function update(Category $category, array $data): bool;

    public function delete(Category $category): bool;
}
