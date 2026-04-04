<div class="space-y-5">
    <div>
        <label for="category_id" class="block mb-1 text-sm font-medium text-gray-700">Category</label>
        <select
            name="category_id"
            id="category_id"
            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
        >
            <option value="">Select Category</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}"
                    {{ old('category_id', $product->category_id ?? '') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
        @error('category_id')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="title" class="block mb-1 text-sm font-medium text-gray-700">Title</label>
        <input
            type="text"
            name="title"
            id="title"
            value="{{ old('title', $product->title ?? '') }}"
            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
        >
        @error('title')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="slug" class="block mb-1 text-sm font-medium text-gray-700">Slug</label>
        <input
            type="text"
            name="slug"
            id="slug"
            value="{{ old('slug', $product->slug ?? '') }}"
            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
        >
        @error('slug')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="description" class="block mb-1 text-sm font-medium text-gray-700">Description</label>
        <textarea
            name="description"
            id="description"
            rows="4"
            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
        >{{ old('description', $product->description ?? '') }}</textarea>
        @error('description')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
        <div>
            <label for="price" class="block mb-1 text-sm font-medium text-gray-700">Price</label>
            <input
                type="number"
                step="0.01"
                name="price"
                id="price"
                value="{{ old('price', $product->price ?? '') }}"
                class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
            >
            @error('price')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="stock" class="block mb-1 text-sm font-medium text-gray-700">Stock</label>
            <input
                type="number"
                name="stock"
                id="stock"
                value="{{ old('stock', $product->stock ?? '') }}"
                class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
            >
            @error('stock')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div>
        <label for="is_active" class="block mb-1 text-sm font-medium text-gray-700">Status</label>
        <select
            name="is_active"
            id="is_active"
            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
        >
            <option value="1" {{ old('is_active', $product->is_active ?? 1) == 1 ? 'selected' : '' }}>Active</option>
            <option value="0" {{ old('is_active', $product->is_active ?? 1) == 0 ? 'selected' : '' }}>Inactive</option>
        </select>
        @error('is_active')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="images" class="block mb-1 text-sm font-medium text-gray-700">Product Images</label>
        <input
            type="file"
            name="images[]"
            id="images"
            multiple
            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
        >
        @error('images')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
        @error('images.*')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    @isset($product)
        @if($product->images->count())
            <div>
                <p class="mb-3 text-sm font-medium text-gray-700">Current Images</p>

                <div class="grid grid-cols-2 gap-4 md:grid-cols-4">
                    @foreach($product->images as $image)
                        <div class="overflow-hidden rounded-lg border bg-white p-2">
                            <img
                                src="{{ asset('storage/' . $image->image) }}"
                                alt="{{ $product->title }}"
                                class="h-24 w-full rounded object-cover"
                            >
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    @endisset
</div>
