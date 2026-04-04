<nav x-data="{ open: false }" class="sticky top-0 z-50 border-b border-gray-200 bg-white/95 backdrop-blur shadow-sm">
    <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">

        {{-- Left Side --}}
        <div class="flex items-center gap-10">
            {{-- Logo / Brand --}}
            <a href="{{ auth()->check() && auth()->user()->role === 'admin' ? route('admin.dashboard') : route('dashboard') }}"
               class="flex items-center gap-2 text-2xl font-extrabold tracking-tight text-gray-900">
                <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-indigo-600 text-sm font-bold text-white shadow">
                    W
                </span>
                <span>Warda Store</span>
            </a>

            {{-- Desktop Links --}}
            @auth
                <div class="hidden items-center gap-2 sm:flex">

                    @if(auth()->user()->role === 'user')
                        <a href="{{ route('dashboard') }}"
                           class="rounded-lg px-4 py-2 text-sm font-medium transition {{ request()->routeIs('dashboard') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-100 hover:text-indigo-600' }}">
                            Dashboard
                        </a>
                    @endif

                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}"
                           class="rounded-lg px-4 py-2 text-sm font-medium transition {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-100 hover:text-indigo-600' }}">
                            Dashboard
                        </a>

                        <a href="{{ route('users.index') }}"
                           class="rounded-lg px-4 py-2 text-sm font-medium transition {{ request()->routeIs('users.*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-100 hover:text-indigo-600' }}">
                            Users
                        </a>
                    @endif

                    <a href="{{ route('categories.index') }}"
                       class="rounded-lg px-4 py-2 text-sm font-medium transition {{ request()->routeIs('categories.*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-100 hover:text-indigo-600' }}">
                        Categories
                    </a>

                    <a href="{{ route('products.index') }}"
                       class="rounded-lg px-4 py-2 text-sm font-medium transition {{ request()->routeIs('products.*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-100 hover:text-indigo-600' }}">
                        Products
                    </a>

                    @if(auth()->user()->role === 'user')
                        <a href="{{ route('cart.index') }}"
                           class="rounded-lg px-4 py-2 text-sm font-medium transition {{ request()->routeIs('cart.*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-100 hover:text-indigo-600' }}">
                            Cart
                        </a>

                        <a href="{{ route('orders.index') }}"
                           class="rounded-lg px-4 py-2 text-sm font-medium transition {{ request()->routeIs('orders.*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-100 hover:text-indigo-600' }}">
                            My Orders
                        </a>
                    @endif

                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('admin.orders.index') }}"
                           class="rounded-lg px-4 py-2 text-sm font-medium transition {{ request()->routeIs('admin.orders.*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-100 hover:text-indigo-600' }}">
                            Orders
                        </a>
                    @endif

                </div>
            @endauth
        </div>

        {{-- Right Side --}}
        <div class="hidden items-center gap-3 sm:flex">
            @auth
                <div class="flex items-center gap-3 rounded-full border border-gray-200 bg-gray-50 px-4 py-2">
                    <div class="flex h-9 w-9 items-center justify-center rounded-full bg-indigo-100 text-sm font-bold text-indigo-700">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>

                    <div class="leading-tight">
                        <p class="text-sm font-semibold text-gray-800">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-500 capitalize">{{ Auth::user()->role }}</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="rounded-xl bg-red-500 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-red-600">
                        Log Out
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}"
                   class="rounded-lg px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100">
                    Login
                </a>

                <a href="{{ route('register') }}"
                   class="rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">
                    Register
                </a>
            @endauth
        </div>

        {{-- Mobile Button --}}
        <div class="sm:hidden">
            <button @click="open = !open"
                class="inline-flex items-center justify-center rounded-lg p-2 text-gray-600 hover:bg-gray-100 hover:text-gray-900">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path :class="{ 'hidden': open, 'inline-flex': !open }"
                          class="inline-flex"
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M4 6h16M4 12h16M4 18h16" />
                    <path :class="{ 'hidden': !open, 'inline-flex': open }"
                          class="hidden"
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    {{-- Mobile Menu --}}
    <div x-show="open" x-transition class="border-t border-gray-200 bg-white sm:hidden">
        <div class="space-y-1 px-4 py-4">

            @auth
                @if(auth()->user()->role === 'user')
                    <a href="{{ route('dashboard') }}" class="block rounded-lg px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100">
                        Dashboard
                    </a>
                @endif

                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="block rounded-lg px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100">
                        Dashboard
                    </a>

                    <a href="{{ route('users.index') }}" class="block rounded-lg px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100">
                        Users
                    </a>
                @endif

                <a href="{{ route('categories.index') }}" class="block rounded-lg px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100">
                    Categories
                </a>

                <a href="{{ route('products.index') }}" class="block rounded-lg px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100">
                    Products
                </a>

                @if(auth()->user()->role === 'user')
                    <a href="{{ route('cart.index') }}" class="block rounded-lg px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100">
                        Cart
                    </a>

                    <a href="{{ route('orders.index') }}" class="block rounded-lg px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100">
                        My Orders
                    </a>
                @endif

                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('admin.orders.index') }}" class="block rounded-lg px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100">
                        Orders
                    </a>
                @endif

                <div class="mt-4 border-t border-gray-200 pt-4">
                    <div class="mb-3 rounded-xl bg-gray-50 px-4 py-3">
                        <p class="text-sm font-semibold text-gray-800">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-500 capitalize">{{ Auth::user()->role }}</p>
                    </div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="w-full rounded-xl bg-red-500 px-4 py-2 text-left text-sm font-semibold text-white hover:bg-red-600">
                            Log Out
                        </button>
                    </form>
                </div>
            @endauth

        </div>
    </div>
</nav>
