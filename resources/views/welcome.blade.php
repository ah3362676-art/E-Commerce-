<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>E-Commerce App</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100">

    {{-- Navbar --}}
    <nav class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">

            <h1 class="text-xl font-bold text-gray-800"> warda store </h1>

            <div class="space-x-4">
                @auth
                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="text-gray-700 hover:text-black">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-black">
                            Dashboard
                        </a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="text-gray-700 hover:text-black">
                        Login
                    </a>

                    <a href="{{ route('register') }}"
                       class="bg-black text-white px-4 py-2 rounded hover:bg-gray-800">
                        Register
                    </a>
                @endauth
            </div>

        </div>
    </nav>

    {{-- Hero Section --}}
    <section class="text-center py-20">
        <h1 class="text-4xl font-bold text-gray-800 mb-6">
            Welcome to Our E-Commerce Store
        </h1>

        <p class="text-gray-600 mb-8">
            Discover amazing products at the best prices.
        </p>

        <div class="space-x-4">
            <a href="{{ route('products.index') }}"
               class="bg-blue-600 text-white px-6 py-3 rounded hover:bg-blue-700">
                Browse Products
            </a>

            @guest
                <a href="{{ route('register') }}"
                   class="bg-gray-800 text-white px-6 py-3 rounded hover:bg-black">
                    Get Started
                </a>
            @endguest
        </div>
    </section>

    {{-- Features --}}
    <section class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-6 px-4 pb-20">

        <div class="bg-white p-6 rounded shadow text-center">
            <h3 class="text-lg font-bold mb-2">Fast Delivery</h3>
            <p class="text-gray-500">Get your products delivered quickly.</p>
        </div>

        <div class="bg-white p-6 rounded shadow text-center">
            <h3 class="text-lg font-bold mb-2">Best Prices</h3>
            <p class="text-gray-500">Affordable prices for everyone.</p>
        </div>

        <div class="bg-white p-6 rounded shadow text-center">
            <h3 class="text-lg font-bold mb-2">Secure Payment</h3>
            <p class="text-gray-500">Your data is safe with us.</p>
        </div>

    </section>

</body>
</html>
