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
                <h1 class="text-3xl font-bold text-gray-800">My Orders</h1>
                <p class="mt-1 text-sm text-gray-500">Track all your orders and review their details</p>
            </div>

            {{-- Orders List --}}
            <div class="space-y-6">
                @forelse($orders as $order)
                    <div class="overflow-hidden rounded-2xl bg-white shadow transition hover:shadow-lg">

                        {{-- Order Header --}}
                        <div class="border-b bg-gradient-to-r from-gray-50 to-white px-6 py-5">
                            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">

                                <div>
                                    <h2 class="text-xl font-bold text-gray-800">
                                        Order #{{ $order->id }}
                                    </h2>
                                    <p class="mt-1 text-sm text-gray-500">
                                        Placed on {{ $order->created_at->format('Y-m-d h:i A') }}
                                    </p>
                                </div>

                                @php
                                    $statusColors = [
                                        'pending' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                                        'shipped' => 'bg-blue-100 text-blue-700 border-blue-200',
                                        'delivered' => 'bg-green-100 text-green-700 border-green-200',
                                    ];
                                @endphp

                                <span
                                id="order-status-{{ $order->id }}"
                                class="inline-flex w-fit items-center rounded-full border px-4 py-1.5 text-xs font-semibold {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-700 border-gray-200' }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </div>
                        </div>

                        {{-- Order Items --}}
                        <div class="px-6 py-5">
                            <div class="mb-4 flex items-center justify-between">
                                <h3 class="text-sm font-semibold uppercase tracking-wide text-gray-500">
                                    Order Items
                                </h3>

                                <span class="text-xs text-gray-400">
                                    {{ $order->items->count() }} item(s)
                                </span>
                            </div>

                            <div class="space-y-3">
                                @foreach($order->items as $item)
                                    <div class="flex flex-col gap-3 rounded-xl border border-gray-100 bg-gray-50 p-4 sm:flex-row sm:items-center sm:justify-between">

                                        <div class="min-w-0">
                                            <p class="text-sm font-semibold text-gray-800 break-words">
                                                {{ $item->product->title ?? 'Deleted Product' }}
                                            </p>

                                            <p class="mt-1 text-xs text-gray-500">
                                                Qty: {{ $item->quantity }} × ${{ number_format($item->price, 2) }}
                                            </p>
                                        </div>

                                        <p class="text-sm font-bold text-gray-700 whitespace-nowrap">
                                            ${{ number_format($item->price * $item->quantity, 2) }}
                                        </p>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Order Footer --}}
                        <div class="border-t bg-gray-50 px-6 py-5">
                            <div class="grid grid-cols-1 gap-4 lg:grid-cols-3">

                                {{-- Phone --}}
                                <div class="rounded-2xl bg-white px-4 py-4 shadow-sm">
                                    <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                                        Phone
                                    </p>
                                    <p class="mt-2 text-sm font-medium text-gray-700 break-words">
                                        {{ $order->phone }}
                                    </p>
                                </div>

                                {{-- Address --}}
                                <div class="rounded-2xl bg-white px-4 py-4 shadow-sm">
                                    <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                                        Address
                                    </p>
                                    <p class="mt-2 text-sm font-medium text-gray-700 break-words">
                                        {{ $order->address }}
                                    </p>
                                </div>

                                {{-- Total --}}
                                <div class="rounded-2xl bg-white px-4 py-4 shadow-sm">
                                    <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                                        Total
                                    </p>
                                    <p class="mt-2 text-2xl font-bold text-green-600">
                                        ${{ number_format($order->total, 2) }}
                                    </p>
                                </div>

                            </div>
                        </div>

                    </div>
                @empty
                    <div class="rounded-2xl bg-white p-10 text-center shadow">
                        <h2 class="text-xl font-semibold text-gray-800">No orders found</h2>
                        <p class="mt-2 text-sm text-gray-500">
                            You have not placed any orders yet.
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
