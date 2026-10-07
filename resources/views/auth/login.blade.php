<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Pengaduan PUTR Cianjur</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-6">

    <div class="w-full max-w-md bg-white p-8 rounded-2xl shadow-xl border border-gray-100">
        <div class="text-center mb-8">
            <img src="{{ asset('images/Logo PUTR.png') }}" alt="Logo PUTR" class="h-20 w-auto mx-auto mb-4">
            <h2 class="text-xl font-bold text-blue-900">Sistem Pengaduan PUTR Cianjur</h2>
        </div>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-4">
                <label class="block text-sm font-bold text-gray-700 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus
                       class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-900 outline-none">
                @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-bold text-gray-700 mb-1">Password</label>
                <input type="password" name="password" required
                       class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-900 outline-none">
            </div>

            <div class="flex items-center justify-between mb-6">
                <label class="flex items-center text-sm text-gray-600">
                    <input type="checkbox" name="remember" class="rounded border-gray-300 text-blue-900">
                    <span class="ml-2">Remember me</span>
                </label>
                <a href="{{ route('password.request') }}" class="text-sm text-blue-700 hover:underline">Forgot password?</a>
            </div>

            <button type="submit" class="w-full bg-blue-900 text-white font-bold py-3 rounded-lg hover:bg-blue-800 transition shadow-lg">
                LOG IN
            </button>
        </form>
    </div>

</body>
</html>
