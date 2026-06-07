<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Wartix Admin</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-gradient-to-br from-indigo-50 via-white to-purple-50 min-h-screen flex items-center justify-center font-sans">

<div class="w-full max-w-sm px-4">

    {{-- Logo --}}
    <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-12 h-12 bg-indigo-600 rounded-2xl mb-3 shadow-lg">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
            </svg>
        </div>
        <h1 class="text-xl font-semibold text-gray-900">Wartix Admin</h1>
        <p class="text-sm text-gray-500 mt-1">Masuk ke panel admin Wartix</p>
    </div>

    {{-- Card --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">

        @if(session('error'))
            <div class="mb-4 bg-red-50 border border-red-200 text-red-600 text-sm px-4 py-3 rounded-xl">
                {{ session('error') }}
            </div>
        @endif

        @if(session('success'))
            <div class="mb-4 bg-green-50 border border-green-200 text-green-600 text-sm px-4 py-3 rounded-xl">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.login.post') }}">
            @csrf

            {{-- Email --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
                <input
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="admin@wartix.id"
                    class="w-full px-3.5 py-2.5 text-sm border rounded-xl bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition
                    {{ $errors->has('email') ? 'border-red-300 bg-red-50' : 'border-gray-200' }}"
                    required autofocus>
                @error('email')
                    <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Password --}}
            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Password</label>
                <input
                    type="password"
                    name="password"
                    placeholder="••••••••"
                    class="w-full px-3.5 py-2.5 text-sm border rounded-xl bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition
                    {{ $errors->has('password') ? 'border-red-300 bg-red-50' : 'border-gray-200' }}"
                    required>
                @error('password')
                    <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Remember --}}
            <div class="flex items-center mb-5">
                <input type="checkbox" name="remember" id="remember"
                    class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                <label for="remember" class="ml-2 text-sm text-gray-600">Ingat saya</label>
            </div>

            {{-- Submit --}}
            <button type="submit"
                class="w-full bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium py-2.5 rounded-xl transition-colors duration-200">
                Masuk ke Dashboard
            </button>
        </form>
    </div>

    <p class="text-center text-xs text-gray-400 mt-5">
        &copy; {{ date('Y') }} Wartix. All rights reserved.
    </p>
</div>

</body>
</html>