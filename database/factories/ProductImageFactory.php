<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;

class ProductImageFactory extends Factory
{
    public function definition(): array
    {
        $sourceImages = [
            public_path('images/products/1.jpg'),
            public_path('images/products/2.jpg'),
            public_path('images/products/3.jpg'),
            public_path('images/products/4.jpg'),
            public_path('images/products/5.jpg'),
            public_path('images/products/6.jpg'),
            public_path('images/products/7.jpg'),
            public_path('images/products/8.jpg'),
            public_path('images/products/9.jpg'),
            public_path('images/products/10.jpg'),
        ];

        $selectedImage = fake()->randomElement($sourceImages);

        return [
            'product_id' => Product::inRandomOrder()->value('id') ?? Product::factory(),
            'image' => Storage::disk('public')->putFile('products', new File($selectedImage)),
        ];
    }
}
