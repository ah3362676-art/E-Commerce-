<!DOCTYPE html>
<html lang="en">
<head>
    @if (auth()->check())
<script>
    window.userId = {{ auth()->user()->id }};
    window.userRole = "{{ auth()->user()->role }}";
</script>
@else
    window.userId = null;
    window.userRole = null;
    @endif
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'My App' }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
@vite(['resources/css/app.css', 'resources/js/app.js'])</head>
<body class="bg-gray-100 min-h-screen">

    @include('layouts.navigation')

    <header class="w-full px-4 sm:px-6 lg:px-8 mt-4 mb-6">
        @isset($header)
            <h1 class="text-3xl font-bold">{{ $header }}</h1>
        @endisset
    </header>

    <main class="w-full px-4 sm:px-6 lg:px-8 mt-4">
        {{ $slot }}
    </main>

</body>
</html>
