<x-app-layout>
    <div class="py-8 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- SUCCESS MESSAGE --}}
            @if(session('success'))
                <div class="mb-4 rounded-lg bg-green-100 px-4 py-3 text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            {{-- HEADER --}}
            <div class="mb-8 flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">Products</h1>
                    <p class="text-sm text-gray-500">Discover and manage all products in your store</p>
                </div>

                @if(auth()->check() && auth()->user()->role === 'admin')
                    <a
                        href="{{ route('products.create') }}"
                        class="rounded-lg bg-indigo-600 px-5 py-2.5 text-white font-medium hover:bg-indigo-700 transition"
                    >
                        Add Product
                    </a>
                @endif
            </div>

            {{-- SEARCH + FILTER --}}
            <div class="mb-6">
                <form
                    action="{{ route('products.index') }}"
                    method="GET"
                    class="flex flex-wrap items-center gap-3"
                >
                    {{-- Search Input --}}
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Search by product title..."
                        class="w-full md:w-72 rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                        oninput="resetSearchIfEmpty(this)"
                    >

                    {{-- Search Button --}}
                    <button
                        type="submit"
                        class="rounded-lg bg-blue-600 px-4 py-2 text-white hover:bg-blue-700 transition"
                    >
                        Search
                    </button>

                    {{-- Category --}}
                    <select
                        name="category_id"
                        class="w-full md:w-56 rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                    >
                        <option value="">All Categories</option>

                        @foreach($categories as $category)
                            <option
                                value="{{ $category->id }}"
                                {{ request('category_id') == $category->id ? 'selected' : '' }}
                            >
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>

                    {{-- Filter Button --}}
                    <button
                        type="submit"
                        class="rounded-lg bg-green-600 px-4 py-2 text-white hover:bg-green-700 transition"
                    >
                        Filter
                    </button>

                    {{-- Reset --}}
                    @if(request('search') || request('category_id'))
                        <a
                            href="{{ route('products.index') }}"
                            class="rounded-lg bg-gray-200 px-4 py-2 text-gray-700 hover:bg-gray-300 transition"
                        >
                            Reset
                        </a>
                    @endif
                </form>
            </div>

            {{-- PRODUCTS GRID --}}
            @forelse($products as $product)
                @if($loop->first)
                    <div id="product_grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @endif

                <div
                    id="product-{{ $product->id }}"
                    class="bg-white rounded-2xl shadow hover:shadow-lg transition overflow-hidden flex flex-col"
                >
                    {{-- IMAGE --}}
                    <div class="h-56 bg-gray-100 flex items-center justify-center overflow-hidden">
                        @if($product->images->first())
                            <img
                                id="product-image-{{ $product->id }}"
                                src="{{ asset('storage/' . $product->images->first()->image) }}"
                                alt="{{ $product->title }}"
                                class="h-full w-full object-cover"
                            >
                        @else
                            <div class="text-gray-400 text-sm">No Image</div>
                        @endif
                    </div>

                    {{-- CONTENT --}}
                    <div class="p-4 flex flex-col flex-1">

                        {{-- CATEGORY --}}
                        <p
                            class="text-xs text-gray-400 mb-1"
                            id="product-category-{{ $product->id }}"
                        >
                            {{ $product->category->name ?? 'No Category' }}
                        </p>

                        {{-- TITLE --}}
                        <a
                            href="{{ route('products.show', $product->id) }}"
                            id="product-title-{{ $product->id }}"
                            class="text-lg font-semibold text-gray-800 mb-2 line-clamp-2 min-h-[56px]"
                        >
                            {{ $product->title }}
                        </a>

                        {{-- PRICE --}}
                        <div class="mb-2">
                            <span
                                id="product-price-{{ $product->id }}"
                                class="text-2xl font-bold text-gray-900"
                            >
                                ${{ number_format($product->price, 2) }}
                            </span>
                        </div>

                        {{-- STOCK --}}
                        <div class="mb-3 text-sm">
                            @if($product->stock > 0)
                                <span
                                    id="product-stock-{{ $product->id }}"
                                    class="text-green-600 font-medium"
                                >
                                    In Stock ({{ $product->stock }})
                                </span>
                            @else
                                <span
                                    id="product-stock-{{ $product->id }}"
                                    class="text-red-600 font-medium"
                                >
                                    Out of Stock
                                </span>
                            @endif
                        </div>

                        {{-- STATUS --}}
                        <div class="mb-4" id="product-status-{{ $product->id }}">
                            @if($product->is_active&& $product->stock > 0)
                                <span class="inline-block bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-medium">
                                    Active
                                </span>
                            @else
                                <span class="inline-block bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-medium">
                                    Inactive
                                </span>
                            @endif
                        </div>

                        {{-- ACTIONS --}}
                        <div class="mt-auto space-y-2">

                            {{-- ADD TO CART --}}

                            <form action="{{ route('cart.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <input type="hidden" name="quantity" value="1">

                                @if(auth()->check() && auth()->user()->role === 'user'&& $product->is_active && $product->stock > 0)
                                    <button
                                        id="add-to-cart-{{ $product->id }}"
                                        type="submit"
                                        class="w-full rounded-full bg-yellow-400 px-4 py-2 text-sm font-medium text-gray-900 hover:bg-yellow-500 transition flex items-center justify-center gap-2"
                                    >
                                        Add to Cart
                                    </button>
                                @endif
                            </form>

                            @if(auth()->check() && auth()->user()->role === 'admin')
                                <div class="flex gap-2">
                                    <a
                                        href="{{ route('products.edit', $product->id) }}"
                                        class="flex-1 rounded-lg bg-blue-600 px-3 py-2 text-center text-sm font-medium text-white hover:bg-blue-700 transition"
                                    >
                                        Edit
                                    </a>

                                    <form
                                        action="{{ route('products.destroy', $product) }}"
                                        method="POST"
                                        class="flex-1"
                                        onsubmit="return confirm('هل أنت متأكد أنك تريد حذف هذا المنتج؟')"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="w-full rounded-lg bg-red-600 px-3 py-2 text-sm font-medium text-white hover:bg-red-700 transition"
                                        >
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                @if($loop->last)
                    </div>
                @endif
            @empty
                <div class="rounded-xl bg-white p-10 text-center text-gray-500 shadow">
                    No products found.
                </div>
            @endforelse

            {{-- PAGINATION --}}
            <div class="mt-8">
                {{ $products->links() }}
            </div>

        </div>
    </div>

    <script>
        function resetSearchIfEmpty(input) {
            if (input.value.trim() === '') {
                window.location.href = "{{ route('products.index') }}";
            }
        }
    </script>
</x-app-layout>
