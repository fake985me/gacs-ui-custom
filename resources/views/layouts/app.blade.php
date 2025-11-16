<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'GenieACS Laravel UI')</title>

    {{-- Kalau kamu pakai Vite, boleh pakai @vite --}}
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}

    {{-- Atau pakai Tailwind CDN sementara --}}
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-900">
<div class="min-h-screen">

    {{-- Navbar (kalau ada) --}}
    @includeIf('layouts.navigation')

    <main class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">

        {{-- Flash messages --}}
        @if (session('status'))
            <div class="mb-4 p-3 rounded bg-green-100 text-green-800">
                {{ session('status') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4 p-3 rounded bg-red-100 text-red-800">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 p-3 rounded bg-red-100 text-red-800">
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Konten halaman --}}
        @yield('content')
    </main>
</div>
</body>
</html>
