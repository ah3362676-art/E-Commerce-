<x-app-layout>
    <div id="notifications" class="fixed top-5 right-5 z-50 space-y-3"></div>
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
                <p id="test-orders-page" class="mb-4 text-red-600 font-bold">ORDERS PAGE TEST</p>
                <h1 class="text-3xl font-bold text-gray-800">Orders Management</h1>
                <p class="mt-1 text-sm text-gray-500">Manage and update all customer orders</p>
            </div>

            {{-- Orders List --}}
            <div id="orders-container" class="space-y-6">
                  @forelse($orders as $order)
                    <div class="overflow-hidden rounded-2xl bg-white shadow transition hover:shadow-lg">

                        {{-- Order Header --}}
                        <div class="border-b bg-gradient-to-r from-gray-50 to-white px-6 py-5">
                            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

                                <div>
                                    <h2 class="text-xl font-bold text-gray-800">
                                        Order #{{ $order->id }}
                                    </h2>
                                    <p class="mt-1 text-sm text-gray-500">
                                        Customer: {{ $order->user->name ?? 'Deleted User' }}
                                    </p>
                                </div>

                                @php
                                    $statusColors = [
                                        'pending' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                                        'shipped' => 'bg-blue-100 text-blue-700 border-blue-200',
                                        'delivered' => 'bg-green-100 text-green-700 border-green-200',
                                    ];
                                @endphp

                                <span class="inline-flex w-fit items-center rounded-full border px-4 py-1.5 text-xs font-semibold {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-700 border-gray-200' }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </div>
                        </div>

                        {{-- Order Body --}}
                        <div class="px-6 py-5">
                            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">

                                {{-- User --}}
                                <div class="rounded-2xl bg-gray-50 px-4 py-4 border border-gray-100">
                                    <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                                        User
                                    </p>
                                    <p class="mt-2 text-sm font-medium text-gray-700 break-words">
                                        {{ $order->user->name ?? 'Deleted User' }}
                                    </p>
                                </div>

                                {{-- Total --}}
                                <div class="rounded-2xl bg-gray-50 px-4 py-4 border border-gray-100">
                                    <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                                        Total
                                    </p>
                                    <p class="mt-2 text-2xl font-bold text-green-600">
                                        ${{ number_format($order->total, 2) }}
                                    </p>
                                </div>

                                {{-- Status Update --}}
                                <div class="rounded-2xl bg-gray-50 px-4 py-4 border border-gray-100">
                                    <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                                        Change Status
                                    </p>

                                    <form action="{{ route('admin.orders.update', $order) }}" method="POST" class="mt-3 flex flex-col gap-3 sm:flex-row">
                                        @csrf
                                        @method('PUT')

                                        <select
                                            name="status"
                                            class="w-full rounded-xl border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                                        >
                                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>
                                                Pending
                                            </option>
                                            <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>
                                                Shipped
                                            </option>
                                            <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>
                                                Delivered
                                            </option>
                                        </select>

                                        <button
                                            type="submit"
                                            class="rounded-xl bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow hover:bg-indigo-700 transition"
                                        >
                                            Save
                                        </button>
                                    </form>
                                </div>

                            </div>
                        </div>

                    </div>
                @empty
                    <div class="rounded-2xl bg-white p-10 text-center shadow">
                        <h2 class="text-xl font-semibold text-gray-800">No orders found</h2>
                        <p class="mt-2 text-sm text-gray-500">
                            There are no customer orders yet.
                        </p>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            <div class="mt-8">
                {{ $orders->links() }}
            </div>

        </div>
    </div>
</x-app-layout>
