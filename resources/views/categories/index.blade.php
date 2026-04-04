<x-app-layout>
    <div class="py-8 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Success Message --}}
            @if(session('success'))
                <div class="mb-4 rounded-lg bg-green-100 px-4 py-3 text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Header --}}
            <div class="mb-8 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">Categories</h1>
                    <p class="text-sm text-gray-500">Manage your product categories</p>
                </div>

                @if(auth()->check() && auth()->user()->role === 'admin')
                    <a href="{{ route('categories.create') }}"
                       class="inline-block rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-indigo-700 transition">
                        + Add Category
                    </a>
                @endif
            </div>

            {{-- Categories Grid --}}
            @forelse($categories as $category)
                @if($loop->first)
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @endif

                <div class="bg-white rounded-2xl shadow hover:shadow-lg transition overflow-hidden flex flex-col">
                    {{-- Top Section --}}
                    <div class="p-5 border-b bg-gradient-to-r from-indigo-50 to-white">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <h2 class="text-lg font-bold text-gray-800 break-words">
                                    {{ $category->name }}
                                </h2>
                                <p class="mt-1 text-sm text-gray-500 break-all">
                                    {{ $category->slug }}
                                </p>
                            </div>

                            @if($category->is_active)
                                <span class="shrink-0 rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-700">
                                    Active
                                </span>
                            @else
                                <span class="shrink-0 rounded-full bg-red-100 px-3 py-1 text-xs font-medium text-red-700">
                                    Inactive
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- Body --}}
                    <div class="p-5 flex-1 flex flex-col">
                        <div class="mb-4">
                            <p class="text-sm text-gray-500">Category Name</p>
                            <p class="mt-1 font-medium text-gray-800">{{ $category->name }}</p>
                        </div>

                        <div class="mb-6">
                            <p class="text-sm text-gray-500">Slug</p>
                            <p class="mt-1 text-gray-700 break-all">{{ $category->slug }}</p>
                        </div>

                        {{-- Actions --}}
                        @if(auth()->check() && auth()->user()->role === 'admin')
                            <div class="mt-auto flex gap-2">
                                {{-- Edit --}}
                                <a href="{{ route('categories.edit', $category->id) }}"
                                   class="flex-1 rounded-lg bg-yellow-400 px-4 py-2 text-center text-sm font-medium text-white hover:bg-yellow-500 transition">
                                    Edit
                                </a>

                                {{-- Delete --}}
                                <form action="{{ route('categories.destroy', $category->id) }}"
                                      method="POST"
                                      class="flex-1"
                                      onsubmit="return confirm('Are you sure?')">
                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="w-full rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700 transition">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>

                @if($loop->last)
                    </div>
                @endif
            @empty
                <div class="rounded-xl bg-white p-10 text-center text-sm text-gray-500 shadow">
                    No categories found.
                </div>
            @endforelse

            {{-- Pagination --}}
            <div class="mt-8 flex justify-center">
                {{ $categories->links() }}
            </div>

        </div>
    </div>
</x-app-layout>
