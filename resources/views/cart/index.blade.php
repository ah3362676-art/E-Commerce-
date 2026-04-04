<x-app-layout>
    <div class="py-8 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Success Message --}}
            @if(session('success'))
                <div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-green-700 shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Header --}}
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-800">My Cart</h1>
                <p class="mt-1 text-sm text-gray-500">Manage products in your cart</p>
            </div>

            @if($cartItems->count())
                <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

                    {{-- Cart Items --}}
                    <div class="lg:col-span-2 space-y-5">
                        @foreach($cartItems as $item)
                            <div class="overflow-hidden rounded-2xl bg-white shadow hover:shadow-lg transition">
                                <div class="flex flex-col gap-4 p-5 sm:flex-row sm:items-start sm:justify-between">

                                    {{-- Left Side --}}
                                    <div class="flex gap-4 min-w-0">
                                        {{-- Image --}}
                                        <div class="shrink-0">
                                            @if($item->product->images->first())
                                                <img
                                                    src="{{ asset('storage/' . $item->product->images->first()->image) }}"
                                                    alt="{{ $item->product->title }}"
                                                    class="h-24 w-24 rounded-xl object-cover border"
                                                >
                                            @else
                                                <div class="flex h-24 w-24 items-center justify-center rounded-xl border bg-gray-50 text-xs text-gray-400">
                                                    No Image
                                                </div>
                                            @endif
                                        </div>

                                        {{-- Product Info --}}
                                        <div class="min-w-0 flex-1">
                                            <h2 class="text-lg font-bold text-gray-800 break-words">
                                                {{ $item->product->title ?? 'Deleted Product' }}
                                            </h2>

                                            <p class="mt-2 text-sm text-gray-500">
                                                Price:
                                                <span class="font-medium text-gray-700">
                                                    ${{ number_format($item->product->price, 2) }}
                                                </span>
                                            </p>

                                            <p class="mt-1 text-sm text-gray-500">
                                                Subtotal:
                                                <span class="font-semibold text-green-600">
                                                    ${{ number_format($item->product->price * $item->quantity, 2) }}
                                                </span>
                                            </p>
                                        </div>
                                    </div>

                                    {{-- Right Side --}}
                                    <div class="w-full sm:w-auto sm:min-w-[220px]">
                                        {{-- Update Quantity --}}
                                        <form action="{{ route('cart.update', $item->id) }}" method="POST" class="space-y-3">
                                            @csrf
                                            @method('PUT')

                                            <div>
                                                <label class="mb-2 block text-sm font-medium text-gray-700">
                                                    Quantity
                                                </label>

                                                <div class="flex items-center gap-2">
                                                    <input
                                                        type="number"
                                                        name="quantity"
                                                        min="1"
                                                        value="{{ $item->quantity }}"
                                                        class="w-24 rounded-xl border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                    >

                                                    <button
                                                        type="submit"
                                                        class="rounded-xl bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 transition"
                                                    >
                                                        Update
                                                    </button>
                                                </div>
                                            </div>
                                        </form>

                                        {{-- Remove --}}
                                        <form action="{{ route('cart.destroy', $item->id) }}" method="POST" class="mt-3">
                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="w-full rounded-xl bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700 transition"
                                            >
                                                Remove
                                            </button>
                                        </form>
                                    </div>

                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Summary --}}
                    <div class="h-fit overflow-hidden rounded-2xl bg-white shadow">
                        <div class="border-b bg-gradient-to-r from-gray-50 to-white px-6 py-5">
                            <h2 class="text-xl font-bold text-gray-800">Cart Summary</h2>
                            <p class="mt-1 text-sm text-gray-500">Review your cart before checkout</p>
                        </div>

                        <div class="p-6">
                            <div class="space-y-4">
                                @foreach($cartItems as $item)
                                    <div class="flex items-start justify-between gap-3 border-b pb-3">
                                        <div class="min-w-0">
                                            <p class="text-sm font-medium text-gray-800 break-words">
                                                {{ $item->product->title ?? 'Deleted Product' }}
                                            </p>
                                            <p class="mt-1 text-xs text-gray-500">
                                                Qty: {{ $item->quantity }}
                                            </p>
                                        </div>

                                        <p class="text-sm font-semibold text-gray-700 whitespace-nowrap">
                                            ${{ number_format($item->product->price * $item->quantity, 2) }}
                                        </p>
                                    </div>
                                @endforeach
                            </div>

                            <div class="mt-6 rounded-xl bg-green-50 px-4 py-4">
                                <div class="flex items-center justify-between">
                                    <span class="text-base font-bold text-gray-800">Total</span>
                                    <span class="text-xl font-bold text-green-600">
                                        ${{ number_format($total, 2) }}
                                    </span>
                                </div>
                            </div>

                            <a href="{{ route('checkout.create') }}"
                               class="mt-5 inline-flex w-full items-center justify-center rounded-xl bg-green-600 px-5 py-3 text-sm font-medium text-white shadow hover:bg-green-700 transition">
                                Checkout
                            </a>
                        </div>
                    </div>

                </div>
            @else
                <div class="rounded-2xl bg-white p-10 text-center shadow">
                    <h2 class="text-xl font-semibold text-gray-800">Your cart is empty</h2>
                    <p class="mt-2 text-sm text-gray-500">Add some products to get started.</p>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
