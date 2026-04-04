<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductImageRequest;
use App\Http\Requests\UpdateProductImageRequest;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Support\Facades\Storage;

class ProductImageController extends Controller
{
    public function index()
    {
        $images = ProductImage::with('product')->latest()->paginate(10);

        return view('product-images.index', compact('images'));
    }

    public function create()
    {
        $products = Product::orderBy('title')->get();

        return view('product-images.create', compact('products'));
    }

    public function store(StoreProductImageRequest $request)
    {
        $data = $request->validated();

        $data['image'] = $request->file('image')->store('products', 'public');

        ProductImage::create($data);

        return redirect()
            ->route('product-images.index')
            ->with('success', 'Product image created successfully.');
    }

    public function edit(ProductImage $productImage)
    {
        $products = Product::orderBy('title')->get();

        return view('product-images.edit', compact('productImage', 'products'));
    }

    public function update(UpdateProductImageRequest $request, ProductImage $productImage)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            if ($productImage->image && Storage::disk('public')->exists($productImage->image)) {
                Storage::disk('public')->delete($productImage->image);
            }

            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $productImage->update($data);

        return redirect()
            ->route('product-images.index')
            ->with('success', 'Product image updated successfully.');
    }

    public function destroy(ProductImage $productImage)
    {
        if ($productImage->image && Storage::disk('public')->exists($productImage->image)) {
            Storage::disk('public')->delete($productImage->image);
        }

        $productImage->delete();

        return redirect()
            ->route('product-images.index')
            ->with('success', 'Product image deleted successfully.');
    }
}
