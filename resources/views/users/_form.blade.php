<div class="space-y-5">
    <div>
        <label for="name" class="block mb-1 text-sm font-medium text-gray-700">Name</label>
        <input
            type="text"
            name="name"
            id="name"
            value="{{ old('name', $user->name ?? '') }}"
            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
        >
        @error('name')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="email" class="block mb-1 text-sm font-medium text-gray-700">Email</label>
        <input
            type="email"
            name="email"
            id="email"
            value="{{ old('email', $user->email ?? '') }}"
              {{ $edit ? 'readonly' : '' }}
            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
        >
        @error('email')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="role" class="block mb-1 text-sm font-medium text-gray-700">Role</label>
        <select
            name="role"
            id="role"
            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
        >
            <option value="user" {{ old('role', $user->role ?? 'user') == 'user' ? 'selected' : '' }}>User</option>
            <option value="admin" {{ old('role', $user->role ?? 'user') == 'admin' ? 'selected' : '' }}>Admin</option>
        </select>
        @error('role')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="password" class="block mb-1 text-sm font-medium text-gray-700">Password</label>
        <input
            type="password"
            name="password"
            id="password"
            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
        >
        @error('password')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="password_confirmation" class="block mb-1 text-sm font-medium text-gray-700">Confirm Password</label>
        <input
            type="password"
            name="password_confirmation"
            id="password_confirmation"
            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
        >
    </div>
</div>
