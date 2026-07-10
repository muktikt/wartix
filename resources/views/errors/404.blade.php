<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 — Wartix</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-gradient-to-br from-indigo-50 via-white to-purple-50 min-h-screen flex items-center justify-center font-sans">
    <div class="text-center px-4">
        <div class="w-16 h-16 bg-indigo-100 rounded-2xl flex items-center justify-center mx-auto mb-5" style="animation: successPop 0.5s cubic-bezier(0.16, 1, 0.3, 1), floatY 3s ease-in-out 0.5s infinite;">
            <svg class="w-8 h-8 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <h1 class="text-6xl font-bold text-indigo-600 mb-2 animate-fade-in-up" style="animation-delay: 100ms; opacity: 0;">404</h1>
        <h2 class="text-xl font-semibold text-gray-900 mb-2 animate-fade-in-up" style="animation-delay: 180ms; opacity: 0;">Halaman tidak ditemukan</h2>
        <p class="text-sm text-gray-500 mb-6 max-w-xs mx-auto animate-fade-in-up" style="animation-delay: 250ms; opacity: 0;">
            Halaman yang kamu cari tidak ada atau sudah dipindahkan.
        </p>
        <a href="{{ route('home') }}"
            class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-5 py-2.5 rounded-xl transition-all duration-200 hover:-translate-y-0.5 active:translate-y-0 animate-fade-in-up" style="animation-delay: 320ms; opacity: 0;">
            Kembali ke Beranda
        </a>
    </div>

    <style>
        @keyframes floatY {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-6px); }
        }
        @media (prefers-reduced-motion: reduce) {
            [style*="animation"] { animation: none !important; opacity: 1 !important; }
        }
    </style>
</body>
</html>