<x-app-layout>
    <div class="py-10">
        <div class="max-w-6xl mx-auto px-4 grid grid-cols-1 md:grid-cols-2 gap-10">

            {{-- Images --}}
            <div>
                @if($product->images->count())
                    <img
                        src="{{ asset('storage/' . $product->images->first()->image) }}"
                        class="w-full h-96 object-cover rounded-xl mb-4"
                    >

                    <div class="flex gap-2">
                        @foreach($product->images as $image)
                            <img
                                src="{{ asset('storage/' . $image->image) }}"
                                class="w-20 h-20 object-cover rounded border"
                            >
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Details --}}
            <div>

                <h1 class="text-3xl font-bold mb-4">
                    {{ $product->title }}
                </h1>

                <p class="text-gray-500 mb-2">
                    Category: {{ $product->category?->name }}
                </p>

                <p class="text-2xl font-bold text-green-600 mb-4">
                    ${{ number_format($product->price, 2) }}
                </p>

                <p class="text-gray-700 mb-6">
                    {{ $product->description }}
                </p>

                {{-- Add to cart --}}
                <form action="{{ route('cart.store') }}" method="POST">
                    @csrf

                    <input type="hidden" name="product_id" value="{{ $product->id }}">

                    <div class="flex items-center gap-4 mb-4">
                        <input
                            type="number"
                            name="quantity"
                            value="1"
                            min="1"
                            class="w-20 rounded border-gray-300"
                        >

                        <button class="bg-green-600 px-6 py-2 rounded text-white hover:bg-green-700">
                            Add to Cart
                        </button>
                    </div>
                </form>

                {{-- Stock --}}
                <p class="text-sm text-gray-500">
                    Stock: {{ $product->stock }}
                </p>

            </div>

        </div>
    </div>
</x-app-layout>
