<div class="space-y-5">
    <div>
        <label for="name" class="block mb-1 text-sm font-medium text-gray-700">Name</label>
        <input
            type="text"
            name="name"
            id="name"
            value="{{ old('name', $category->name ?? '') }}"
            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
        >
        @error('name')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="slug" class="block mb-1 text-sm font-medium text-gray-700">Slug</label>
        <input
            type="text"
            name="slug"
            id="slug"
            value="{{ old('slug', $category->slug ?? '') }}"
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
        >{{ old('description', $category->description ?? '') }}</textarea>
        @error('description')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="is_active" class="block mb-1 text-sm font-medium text-gray-700">Status</label>
        <select
            name="is_active"
            id="is_active"
            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
        >
            <option value="1" {{ old('is_active', $category->is_active ?? 1) == 1 ? 'selected' : '' }}>Active</option>
            <option value="0" {{ old('is_active', $category->is_active ?? 1) == 0 ? 'selected' : '' }}>Inactive</option>
        </select>
        @error('is_active')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>
</div>
