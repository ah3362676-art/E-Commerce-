<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">

            {{-- Left Side --}}
            <div class="flex items-center">
                {{-- Logo --}}
                <a href="{{ route('dashboard') }}" class="text-xl font-bold text-gray-800">
                    warda e-commerce
                </a>

                {{-- Desktop Links --}}
@if(auth()->check() && auth()->user()->role === 'user')

                <div class="hidden sm:flex space-x-8 ms-10">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        Dashboard
                    </x-nav-link>
                    @endif

@if(auth()->check() && auth()->user()->role === 'admin')
                <div class="hidden sm:flex space-x-8 ms-10">
                    <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('dashboard')">
                        Dashboard
                    </x-nav-link>
                                        @endif

@if(auth()->check() && auth()->user()->role === 'admin')
                    <x-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')">
                        Users
                    </x-nav-link>
                    @endif
                    <x-nav-link :href="route('categories.index')" :active="request()->routeIs('categories.*')">
                        Categories
                    </x-nav-link>

                    <x-nav-link :href="route('products.index')" :active="request()->routeIs('products.*')">
                        Products
                    </x-nav-link>
@if(auth()->check() && auth()->user()->role === 'user')
                    <x-nav-link :href="route('cart.index')" :active="request()->routeIs('cart.*')">
                        Cart
                    </x-nav-link>
            @endif


@if(auth()->check() && auth()->user()->role === 'user')
                    <x-responsive-nav-link :href="route('orders.index')">
                         My-order
                   </x-responsive-nav-link>
                   @endif

@if(auth()->check() && auth()->user()->role === 'admin')
             <x-nav-link :href="route('admin.orders.index')" :active="request()->routeIs('admin.orders.*')">
                        Orders
             </x-nav-link>
             @endif
                </div>
            </div>

            {{-- Right Side --}}
            <div class="hidden sm:flex items-center gap-4">
                <span class="font-semibold text-gray-700">
                    {{ Auth::user()->name ??"Guest" }}
                </span>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700">
                        Log Out
                    </button>
                </form>
            </div>

            {{-- Mobile Menu Button --}}
            <div class="sm:hidden flex items-center">
                <button @click="open = ! open"
                    class="p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': !open }"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': !open, 'inline-flex': open }"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Mobile Menu --}}
    <div :class="{'block': open, 'hidden': !open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')">
                Dashboard
            </x-responsive-nav-link>

@if(auth()->check() && auth()->user()->role === 'admin')

            <x-responsive-nav-link :href="route('users.index')">
                Users
            </x-responsive-nav-link>
@endif
            <x-responsive-nav-link :href="route('categories.index')">
                Categories
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('products.index')">
                Products
            </x-responsive-nav-link>
@if(auth()->check() && auth()->user()->role === 'user')
            <x-responsive-nav-link :href="route('cart.index')">
                Cart
            </x-responsive-nav-link>
            @endif

@if(auth()->check() && auth()->user()->role === 'user')
              <x-responsive-nav-link :href="route('orders.index')">
                my-order
            </x-responsive-nav-link>
                    @endif


@if(auth()->check() && auth()->user()->role === 'admin')
            <x-nav-link :href="route('admin.orders.index')" :active="request()->routeIs('admin.orders.*')">
            Orders
              </x-nav-link>
        </div>
        @endif

        <div class="border-t pt-4 pb-2 px-4">
            <div class="font-medium text-gray-800">
                {{ Auth::user()->name?? "Guest" }}
            </div>

            <form method="POST" action="{{ route('logout') }}" class="mt-3">
                @csrf
                <button type="submit"
                    class="w-full text-left text-red-600 px-3 py-2 hover:bg-gray-100 rounded">
                    Log Out
                </button>
            </form>
        </div>
    </div>
</nav>
