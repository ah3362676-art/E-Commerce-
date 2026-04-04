<x-app-layout>
    <div class="py-8 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Header --}}
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-800">Dashboard</h1>
                <p class="mt-1 text-sm text-gray-500">Overview of your store performance</p>
            </div>

            {{-- Stats Cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6">

                {{-- Products --}}
                <div class="bg-white rounded-2xl shadow p-6 flex items-center justify-between hover:shadow-lg transition">
                    <div>
                        <p class="text-sm text-gray-500">Products</p>
                        <h2 class="text-2xl font-bold text-gray-800">
                            {{ \App\Models\Product::count() }}
                        </h2>
                    </div>

                    <div class="h-12 w-12 flex items-center justify-center rounded-xl bg-indigo-100 text-indigo-600">
                        📦
                    </div>
                </div>

                {{-- Orders --}}
                <div class="bg-white rounded-2xl shadow p-6 flex items-center justify-between hover:shadow-lg transition">
                    <div>
                        <p class="text-sm text-gray-500">Orders</p>
                        <h2 class="text-2xl font-bold text-gray-800">
                            {{ \App\Models\Order::count() }}
                        </h2>
                    </div>

                    <div class="h-12 w-12 flex items-center justify-center rounded-xl bg-yellow-100 text-yellow-600">
                        🧾
                    </div>
                </div>

                {{-- Users --}}
                <div class="bg-white rounded-2xl shadow p-6 flex items-center justify-between hover:shadow-lg transition">
                    <div>
                        <p class="text-sm text-gray-500">Users</p>
                        <h2 class="text-2xl font-bold text-gray-800">
                            {{ \App\Models\User::count() }}
                        </h2>
                    </div>

                    <div class="h-12 w-12 flex items-center justify-center rounded-xl bg-blue-100 text-blue-600">
                        👤
                    </div>
                </div>

                {{-- Revenue --}}
                <div class="bg-white rounded-2xl shadow p-6 flex items-center justify-between hover:shadow-lg transition">
                    <div>
                        <p class="text-sm text-gray-500">Revenue</p>
                        <h2 class="text-2xl font-bold text-green-600">
                            ${{ number_format(\App\Models\Order::sum('total'), 2) }}
                        </h2>
                    </div>

                    <div class="h-12 w-12 flex items-center justify-center rounded-xl bg-green-100 text-green-600">
                        💰
                    </div>
                </div>

            </div>

            {{-- Extra Section (اختياري بس شكله جامد 🔥) --}}
            <div class="mt-10 grid grid-cols-1 lg:grid-cols-2 gap-6">

                {{-- Quick Actions --}}
                <div class="bg-white rounded-2xl shadow p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Quick Actions</h3>

                    <div class="flex flex-col gap-3">
                        <a href="{{ route('products.index') }}"
                           class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 text-sm">
                            Manage Products
                        </a>

                        <a href="{{ route('orders.index') }}"
                           class="bg-yellow-500 text-white px-4 py-2 rounded-lg hover:bg-yellow-600 text-sm">
                            View Orders
                        </a>

                        <a href="{{ route('users.index') }}"
                           class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 text-sm">
                            Manage Users
                        </a>
                    </div>
                </div>

                {{-- Info Box --}}
                <div class="bg-white rounded-2xl shadow p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Store Info</h3>

                    <p class="text-sm text-gray-500">
                        This dashboard gives you a quick overview of your store performance.
                        You can manage products, track orders, and monitor users from here.
                    </p>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
