<?php

namespace App\Http\Controllers;

use App\Events\ProductCreated;
use App\Events\ProductDeleted;
use App\Events\ProductUpdated;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    protected ProductRepositoryInterface $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $filters = [
            'search' => request('search'),
            'category_id' => request('category_id'),
        ];

        $products = $this->productRepository->getFilteredPaginatedProducts($filters, 10);

        $categories = Category::all();

        return view('products.index', compact('products', 'categories'));
    }

    public function show(Product $product)
    {
        $product = $this->productRepository->findWithRelations($product);

        return view('products.show', compact('product'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();

        return view('products.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        $data = $request->safe()->except('images');

        $product = $this->productRepository->create($data);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');

                $product->images()->create([
                    'image' => $path
                ]);
            }
        }
        $product->load(['category', 'images']);
        event(new ProductCreated($product->fresh()));

        return redirect()
            ->route('products.index')
            ->with('success', 'Product created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $product = $this->productRepository->findForEdit($id);

        $categories = Category::where('is_active', true)->orderBy('name')->get();

        return view('products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        $data = $request->safe()->except('images');

        $this->productRepository->update($product, $data);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');

                $product->images()->create([
                    'image' => $path
                ]);
            }
        }
event(new ProductUpdated($product->fresh()));
        return redirect()
            ->route('products.index')
            ->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
   public function destroy(Product $product)
{
    $deletedProduct = [
        'id' => $product->id,
        'title' => $product->title,
    ];

    $deleted = $this->productRepository->delete($product);

    if ($deleted) {
        event(new ProductDeleted($deletedProduct));
    }

    return redirect()
        ->route('products.index')
        ->with('success', 'Product deleted successfully.');
}
}
