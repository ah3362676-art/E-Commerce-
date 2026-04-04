<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       $query = Product::with('category',"images");
       if(request()->filled('search')){
        $query->where("title","like","%".request()->search."%");
       }
       if(request()->filled('category_id')){
        $query->where("category_id", request()->category_id);
       }
       $products = $query->latest()->paginate(10);

       $categories = Category::all();

        return view('products.index', compact('products', 'categories'));
    }

     public function show(Product $product)
    {
         $product->load(["category","images"]);
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
        $data = $request->safe()->except('images');  // هات كل البيانات معدا الصور عشن هي ف جدول تاني
        $product=Product::create($data);
        if($request->hasfile("images")){
            foreach($request->file("images") as $image){
                $path = $image->store('products', 'public');   // اسم الفولدلار واسم الديسك
                $product->images()->create([
                    'image' => $path
                    ]);
            }
        }

        return redirect()
        ->route('products.index')
        ->with('success', 'Product created successfully.');
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
// $product->load('images');
         $product = Product::with('images')->findOrFail($id);
         $categories = Category::where('is_active', true)->orderBy('name')->get();
         return view('products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request,product $product)
    {
        $data = $request->safe()->except('images');
        $product->update($data);
        if($request->hasfile("images")){
            foreach($request->file("images") as $image){
                $path = $image->store('products', 'public');
                $product->images()->create([
                    'image' => $path
                    ]);
            }
        }
        return redirect()
        ->route('products.index')
        ->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */

    public function destroy(Product $product)
    {
        foreach ($product->images as $image) {
            if ($image->image && Storage::disk('public')->exists($image->image)) {
                Storage::disk('public')->delete($image->image);
            }
        }

        $product->delete();

        return redirect()
            ->route('products.index')
            ->with('success', 'Product deleted successfully.');
    }

}
