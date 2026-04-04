<x-app-layout>
    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Create Category</h1>
                <p class="text-sm text-gray-500">Add a new category to your store</p>
            </div>

            <div class="rounded-xl bg-white p-6 shadow">
                <form action="{{ route('categories.store') }}" method="POST" class="space-y-5">
                    @csrf

                    @include('categories._form')

                    <div class="flex items-center gap-3">
                        <button
                            type="submit"
                            class="rounded-lg bg-indigo-600 px-5 py-2.5 text-white hover:bg-indigo-700"
                        >
                            Save
                        </button>

                        <a
                            href="{{ route('categories.index') }}"
                            class="rounded-lg bg-gray-200 px-5 py-2.5 text-gray-700 hover:bg-gray-300"
                        >
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
