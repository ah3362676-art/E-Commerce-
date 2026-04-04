<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $product = $this->route('product');
        $productId = is_object($product) ? $product->id : $product;

        return [
            'category_id' => ['required', 'exists:categories,id'],
            'title' => ['required', 'string', 'max:255'],
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('products', 'slug')->ignore($productId),
            ],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'is_active' => ['required', 'boolean'],
            'images' => ['nullable', 'array'],
            'images.*' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'category_id.required' => 'The category field is required.',
            'category_id.exists' => 'The selected category is invalid.',

            'title.required' => 'The product title is required.',
            'title.string' => 'The product title must be a string.',
            'title.max' => 'The product title must not exceed 255 characters.',

            'slug.required' => 'The product slug is required.',
            'slug.string' => 'The product slug must be a string.',
            'slug.max' => 'The product slug must not exceed 255 characters.',
            'slug.unique' => 'The product slug must be unique.',

            'description.string' => 'The product description must be a string.',

            'price.required' => 'The product price is required.',
            'price.numeric' => 'The product price must be a number.',
            'price.min' => 'The product price must be at least 0.',

            'stock.required' => 'The product stock is required.',
            'stock.integer' => 'The product stock must be an integer.',
            'stock.min' => 'The product stock must be at least 0.',

            'is_active.required' => 'The product active status is required.',
            'is_active.boolean' => 'The product active status must be true or false.',

            'images.array' => 'The images must be an array.',
            'images.*.image' => 'Each image must be an image file.',
            'images.*.mimes' => 'Each image must be a file of type: jpg, jpeg, png, webp.',
            'images.*.max' => 'Each image may not be greater than 2048 kilobytes.',
        ];
    }
}
