<div class="space-y-5">
    <div>
        <label for="product_id" class="block mb-1 text-sm font-medium text-gray-700">Product</label>
        <select
            name="product_id"
            id="product_id"
            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
        >
            <option value="">Select Product</option>
            @foreach($products as $product)
                <option value="{{ $product->id }}"
                    {{ old('product_id', $productImage->product_id ?? '') == $product->id ? 'selected' : '' }}>
                    {{ $product->title }}
                </option>
            @endforeach
        </select>

        @error('product_id')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="image" class="block mb-1 text-sm font-medium text-gray-700">Image</label>
        <input
            type="file"
            name="image"
            id="image"
            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
        >

        @error('image')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    @isset($productImage)
        @if($productImage->image)
            <div>
                <p class="mb-2 text-sm font-medium text-gray-700">Current Image</p>
                <img
                    src="{{ asset('storage/' . $productImage->image) }}"
                    alt="Product Image"
                    class="h-28 w-28 rounded-lg object-cover border"
                >
            </div>
        @endif
    @endisset
</div>
