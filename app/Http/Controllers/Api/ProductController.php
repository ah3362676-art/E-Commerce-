<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['category', 'images'])
            ->where('is_active', true)
            ->latest()
            ->get();
        return ProductResource::collection($products);
    }

    public function show(Product $product)
    {
        $product->load(['category', 'images']);

        return new ProductResource($product);
    }
}
