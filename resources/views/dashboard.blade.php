<x-app-layout>
    <div class="py-8 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Header --}}
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-800">User Dashboard</h1>
                <p class="mt-1 text-sm text-gray-500">Quick access to everything you need</p>
            </div>

            {{-- Dashboard Cards --}}
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-3">

                {{-- Browse Products --}}
                <a href="{{ route('products.index') }}"
                   class="group rounded-2xl bg-white p-6 shadow transition hover:-translate-y-1 hover:shadow-lg">
                    <div class="mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-indigo-100 text-indigo-600">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-7 w-7"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M20 13V7a2 2 0 00-2-2h-3V3H9v2H6a2 2 0 00-2 2v6m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0H4" />
                        </svg>
                    </div>

                    <h2 class="text-xl font-bold text-gray-800 group-hover:text-indigo-600 transition">
                        Browse Products
                    </h2>

                    <p class="mt-2 text-sm text-gray-500">
                        View all available products and explore the store.
                    </p>
                </a>

                {{-- My Cart --}}
                <a href="{{ route('cart.index') }}"
                   class="group rounded-2xl bg-white p-6 shadow transition hover:-translate-y-1 hover:shadow-lg">
                    <div class="mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-green-100 text-green-600">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-7 w-7"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-1.293 1.293a1 1 0 00-.293.707V17h12m-9 4a1 1 0 11-2 0 1 1 0 012 0zm8 0a1 1 0 11-2 0 1 1 0 012 0z" />
                        </svg>
                    </div>

                    <h2 class="text-xl font-bold text-gray-800 group-hover:text-green-600 transition">
                        My Cart
                    </h2>

                    <p class="mt-2 text-sm text-gray-500">
                        Check your shopping cart and manage your items.
                    </p>
                </a>

                {{-- My Orders --}}
                <a href="{{ route('orders.index') }}"
                   class="group rounded-2xl bg-white p-6 shadow transition hover:-translate-y-1 hover:shadow-lg">
                    <div class="mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-yellow-100 text-yellow-600">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-7 w-7"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M9 17v-2a4 4 0 014-4h5m0 0l-3-3m3 3l-3 3M5 7h14M5 11h6M5 15h4" />
                        </svg>
                    </div>

                    <h2 class="text-xl font-bold text-gray-800 group-hover:text-yellow-600 transition">
                        My Orders
                    </h2>

                    <p class="mt-2 text-sm text-gray-500">
                        Track your orders and follow their latest status.
                    </p>
                </a>

            </div>
        </div>
    </div>
</x-app-layout>
