<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100">

<div class="flex min-h-screen">

    {{-- Sidebar --}}
    <aside class="w-64 bg-gray-900 text-white p-5">
        <h2 class="text-xl font-bold mb-6">Admin Panel</h2>

        <ul class="space-y-3">
            <li><a href="{{ route('dashboard') }}" class="block hover:text-gray-300">Dashboard</a></li>
            <li><a href="{{ route('products.index') }}" class="block hover:text-gray-300">Products</a></li>
            <li><a href="{{ route('categories.index') }}" class="block hover:text-gray-300">Categories</a></li>
            <li><a href="{{ route('admin.orders.index') }}" class="block hover:text-gray-300">Orders</a></li>
            <li><a href="{{ route('users.index') }}" class="block hover:text-gray-300">Users</a></li>
        </ul>
    </aside>

    {{-- Content --}}
    <main class="flex-1 p-6">
        {{ $slot }}
    </main>

</div>

</body>
</html>
