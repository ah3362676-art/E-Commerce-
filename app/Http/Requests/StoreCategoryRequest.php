<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "name" => "required|string|max:255",
            "description" => "nullable|string",
            "slug" => "required|string|max:255|unique:categories,slug",
            'is_active' => ['required', 'boolean'],

        ];
    }
    public function message(): array
    {
        return [
          "name.required" => "Category name is required",
            "name.string" => "Category name must be a string",
            "name.max" => "Category name must not exceed 255 characters",
            "description.string" => "Description must be a string",
            "slug.required" => "Slug is required",
            "slug.string" => "Slug must be a string",
            "slug.max" => "Slug must not exceed 255 characters",
            "slug.unique" => "Slug must be unique",
            "is_active.required" => "Active status is required",
            "is_active.boolean" => "Active status must be a boolean value",

        ];
    }
}
