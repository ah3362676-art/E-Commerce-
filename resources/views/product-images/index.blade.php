<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 rounded-lg bg-green-100 px-4 py-3 text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Product Images</h1>
                    <p class="text-sm text-gray-500">Manage product gallery images</p>
                </div>

                <a href="{{ route('product-images.create') }}"
                   class="rounded-lg bg-indigo-600 px-4 py-2 text-white hover:bg-indigo-700">
                    Add Image
                </a>
            </div>

            <div class="overflow-hidden rounded-xl bg-white shadow">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                ID
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                Product
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                Image
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                Created At
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                Actions
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse($images as $image)
                            <tr>
                                <td class="px-6 py-4 text-sm text-gray-700">
                                    {{ $image->id }}
                                </td>

                                <td class="px-6 py-4 text-sm text-gray-700">
                                    {{ $image->product->title ?? 'No Product' }}
                                </td>

                                <td class="px-6 py-4">
                                    <img
                                        src="{{ asset('storage/' . $image->image) }}"
                                        alt="Product Image"
                                        class="h-20 w-20 rounded-lg border object-cover"
                                    >
                                </td>

                                <td class="px-6 py-4 text-sm text-gray-700">
                                    {{ $image->created_at?->format('Y-m-d h:i A') }}
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('product-images.edit', $image->id) }}"
                                           class="rounded-lg bg-yellow-500 px-3 py-1.5 text-sm text-white hover:bg-yellow-600">
                                            Edit
                                        </a>

                                        <form action="{{ route('product-images.destroy', $image->id) }}"
                                              method="POST"
                                              onsubmit="return confirm('Are you sure you want to delete this image?');">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="rounded-lg bg-red-600 px-3 py-1.5 text-sm text-white hover:bg-red-700">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-6 text-center text-sm text-gray-500">
                                    No images found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $images->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
