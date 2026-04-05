<x-app-layout>
    <div class="py-8 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Header --}}
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-800">Checkout</h1>
                <p class="mt-1 text-sm text-gray-500">Complete your order details and review your items</p>
            </div>

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

                {{-- Checkout Form --}}
                <div class="lg:col-span-2 rounded-2xl bg-white shadow overflow-hidden">
                    <div class="border-b bg-gradient-to-r from-gray-50 to-white px-6 py-5">
                        <h2 class="text-xl font-bold text-gray-800">Billing & Shipping Info</h2>
                        <p class="mt-1 text-sm text-gray-500">Enter your phone, address, and any extra notes</p>
                    </div>

                    <div class="p-6">
                        <form action="{{ route('checkout.store') }}" method="POST" class="space-y-6">
                            @csrf

                            {{-- Phone --}}
                            <div>
                                <label for="phone" class="mb-2 block text-sm font-medium text-gray-700">
                                    Phone *
                                </label>
                                <input
                                    type="text"
                                    name="phone"
                                    id="phone"
                                    value="{{ old('phone') }}"
                                    class="w-full rounded-xl border-gray-300 px-4 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    placeholder="Enter your phone number"
                                >
                                @error('phone')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Address --}}
                            <div>
                                <label for="address" class="mb-2 block text-sm font-medium text-gray-700">
                                    Address *
                                </label>
                                <textarea
                                    name="address"
                                    id="address"
                                    rows="4"
                                    class="w-full rounded-xl border-gray-300 px-4 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    placeholder="Enter your full address"
                                >{{ old('address') }}</textarea>
                                @error('address')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Notes --}}
                            <div>
                                <label for="notes" class="mb-2 block text-sm font-medium text-gray-700">
                                    Notes
                                </label>
                                <textarea
                                    name="notes"
                                    id="notes"
                                    rows="3"
                                    class="w-full rounded-xl border-gray-300 px-4 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    placeholder="Any extra notes for delivery?"
                                >{{ old('notes') }}</textarea>
                                @error('notes')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Submit --}}
                            <div class="pt-2">
                                <button
                                    type="submit"
                                    class="inline-flex items-center justify-center rounded-xl bg-green-600 px-6 py-3 text-sm font-medium text-white shadow hover:bg-green-700 transition"
                                >
                                    Place Order
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Order Summary --}}
                <div class="rounded-2xl bg-white shadow overflow-hidden h-fit">
                    <div class="border-b bg-gradient-to-r from-gray-50 to-white px-6 py-5">
                        <h2 class="text-xl font-bold text-gray-800">Order Summary</h2>
                        <p class="mt-1 text-sm text-gray-500">Review your selected products</p>
                    </div>

                    <div class="p-6">
                        <div class="space-y-4">
                            @foreach($cartItems as $item)
                                <div class="flex items-start justify-between gap-3 rounded-xl border border-gray-100 bg-gray-50 p-4">
                                    <div class="flex items-start gap-3 min-w-0">
                                        @if($item->product->images->first())
                                            <img
                                                src="{{ asset('storage/' . $item->product->images->first()->image) }}"
                                                alt="{{ $item->product->title }}"
                                                class="h-16 w-16 rounded-lg object-cover border"
                                            >
                                        @else
                                            <div class="flex h-16 w-16 items-center justify-center rounded-lg border bg-white text-xs text-gray-400">
                                                No Image
                                            </div>
                                        @endif

                                        <div class="min-w-0">
                                            <p class="text-sm font-semibold text-gray-800 break-words">
                                                {{ $item->product->title }}
                                            </p>
                                            <p class="mt-1 text-xs text-gray-500">
                                                Qty: {{ $item->quantity }}
                                            </p>
                                            <p class="mt-1 text-xs text-gray-500">
                                                Price: ${{ number_format($item->product->price, 2) }}
                                            </p>
                                        </div>
                                    </div>

                                    <p class="text-sm font-bold text-gray-700 whitespace-nowrap">
                                        ${{ number_format($item->product->price * $item->quantity, 2) }}
                                    </p>
                                </div>
                            @endforeach
                        </div>

                        {{-- Total --}}
                        <div class="mt-6 border-t pt-4">
                            <div class="flex items-center justify-between rounded-xl bg-green-50 px-4 py-3">
                                <span class="text-base font-bold text-gray-800">Total</span>
                                <span class="text-lg font-bold text-green-600">
                                    ${{ number_format($total, 2) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
