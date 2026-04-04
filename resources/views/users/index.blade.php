<x-app-layout>
    <div class="py-8 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Success Message --}}
            @if(session('success'))
                <div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-green-700 shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Header --}}
            <div class="mb-8 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">Users</h1>
                    <p class="text-sm text-gray-500">Manage all users in your store</p>
                </div>

                <a href="{{ route('users.create') }}"
                   class="inline-block rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-indigo-700 transition">
                    Add User
                </a>
            </div>

            {{-- Users Grid --}}
            @forelse($users as $user)
                @if($loop->first)
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @endif

                <div class="bg-white rounded-2xl shadow hover:shadow-lg transition overflow-hidden flex flex-col">

                    {{-- Top --}}
                    <div class="p-5 border-b bg-gradient-to-r from-gray-50 to-white">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <h2 class="text-lg font-bold text-gray-800 break-words">
                                    {{ $user->name }}
                                </h2>
                                <p class="mt-1 text-sm text-gray-500 break-all">
                                    {{ $user->email }}
                                </p>
                            </div>

                            @if($user->role === 'admin')
                                <span class="shrink-0 rounded-full bg-purple-100 px-3 py-1 text-xs font-medium text-purple-700">
                                    Admin
                                </span>
                            @else
                                <span class="shrink-0 rounded-full bg-blue-100 px-3 py-1 text-xs font-medium text-blue-700">
                                    User
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- Body --}}
                    <div class="p-5 flex-1 flex flex-col">
                        <div class="mb-4">
                            <p class="text-sm text-gray-500">Name</p>
                            <p class="mt-1 font-medium text-gray-800 break-words">
                                {{ $user->name }}
                            </p>
                        </div>

                        <div class="mb-4">
                            <p class="text-sm text-gray-500">Email</p>
                            <p class="mt-1 text-gray-700 break-all">
                                {{ $user->email }}
                            </p>
                        </div>

                        <div class="mb-6">
                            <p class="text-sm text-gray-500">Role</p>
                            <p class="mt-1 text-gray-700">
                                {{ ucfirst($user->role) }}
                            </p>
                        </div>

                        {{-- Actions --}}
                        <div class="mt-auto flex gap-2">
                            <a href="{{ route('users.edit', $user->id) }}"
                               class="flex-1 rounded-lg bg-yellow-400 px-4 py-2 text-center text-sm font-medium text-white hover:bg-yellow-500 transition">
                                Edit
                            </a>

                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="flex-1">
                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                    class="w-full rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700 transition">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                @if($loop->last)
                    </div>
                @endif
            @empty
                <div class="rounded-2xl bg-white p-10 text-center text-sm text-gray-500 shadow">
                    No users found.
                </div>
            @endforelse

            {{-- Pagination --}}
            <div class="mt-8">
                {{ $users->links() }}
            </div>

        </div>
    </div>
</x-app-layout>
