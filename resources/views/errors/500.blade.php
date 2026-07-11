<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 — Wartix</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-gradient-to-br from-red-50 via-white to-orange-50 min-h-screen flex items-center justify-center font-sans">
    <div class="text-center px-4">
        <div class="w-16 h-16 bg-red-100 rounded-2xl flex items-center justify-center mx-auto mb-5">
            <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        </div>
        <h1 class="text-6xl font-bold text-red-500 mb-2">500</h1>
        <h2 class="text-xl font-semibold text-gray-900 mb-2">Server Error</h2>
        <p class="text-sm text-gray-500 mb-6 max-w-xs mx-auto">
            Terjadi kesalahan di server. Tim kami sedang memperbaikinya.
        </p>
        <a href="{{ route('home') }}"
            class="inline-flex items-center gap-2 bg-gray-900 hover:bg-gray-800 text-white text-sm font-medium px-5 py-2.5 rounded-xl transition-colors">
            Kembali ke Beranda
        </a>
    </div>
</body>
</html>