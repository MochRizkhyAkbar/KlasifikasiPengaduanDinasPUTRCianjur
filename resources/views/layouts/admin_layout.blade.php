<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - PUTR CIANJUR</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Mencegah scroll pada body utama agar sidebar tetap stabil */
        body {
            height: 100vh;
            margin: 0;
            padding: 0;
            overflow: hidden;
        }
    </style>

    @stack('styles')
</head>
<body class="bg-gray-100 font-sans text-gray-900">

    <div class="flex h-screen overflow-hidden">
        @include('layouts.sidebar')

        <main class="flex-1 overflow-y-auto p-8">
            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>
