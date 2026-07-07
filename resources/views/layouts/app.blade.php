<!DOCTYPE html>
<html lang="id">
<head>

    {{-- Favicon --}}
    <meta property="og:image" content="{{ asset('images/logo-full.png') }}">
    <meta name="twitter:image" content="{{ asset('images/logo-full.png') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/logo-w.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/logo-w.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo-w.png') }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Wartix Priority Ticket Assistance')</title>
    <meta name="description" content="@yield('meta-description', 'Wartix membantu kamu mendapatkan tiket konser, festival, dan fanmeeting dengan Priority Access, Realtime Monitoring, dan update via Telegram.')">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- SEO --}}
    <meta name="robots" content="index, follow">
    <meta property="og:title" content="@yield('title', 'Wartix Priority Ticket Assistance')">
    <meta property="og:description" content="@yield('meta-description', 'Platform Ticket Assistance untuk event high-demand.')">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Wartix">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('title', 'Wartix')">
    <link rel="canonical" href="{{ url()->current() }}">
</head>
<body class="bg-white font-sans antialiased">

@php
    $telegramLink = 'https://t.me/wartixdotcom';
@endphp

{{-- NAVBAR --}}
<nav class="sticky top-0 z-50 bg-white/95 backdrop-blur border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center h-14 gap-6">

            {{-- Logo --}}
            <a href="{{ route('home') }}" class="flex items-center gap-2 flex-shrink-0 -ml-5 sm:-ml-8">
                <img src="{{ asset('images/logo-w.png') }}"
                    alt="Wartix"
                    class="h-10 sm:h-11 w-auto max-w-[210px] object-contain">
                <span class="hidden sm:inline text-sm font-semibold text-gray-900">Wartix</span>
            </a>

            {{-- Nav Links --}}
            <div class="hidden md:flex items-center gap-1 flex-1">
                <a href="{{ route('home') }}#active-events"
                    class="px-3 py-1.5 text-sm rounded-lg transition-colors text-gray-600 hover:text-gray-900 hover:bg-gray-50">
                    Events
                </a>
                <a href="{{ route('home') }}#monitor"
                    class="px-3 py-1.5 text-sm rounded-lg transition-colors
                    {{ request()->routeIs('monitor') ? 'text-indigo-600 bg-indigo-50' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                    Realtime Monitor
                </a>
                <a href="{{ route('home') }}#cara-order"
                    class="px-3 py-1.5 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-lg transition-colors">
                    Cara Order
                </a>
                <a href="{{ route('home') }}#faq"
                    class="px-3 py-1.5 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-lg transition-colors">
                    FAQ
                </a>
            </div>

            {{-- CTA --}}
            <div class="ml-auto flex items-center gap-2">
                <a href="{{ $telegramLink }}" target="_blank" rel="noopener noreferrer"
                    class="flex items-center gap-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-3.5 py-1.5 rounded-lg transition-colors">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <path d="M9.04 15.38 8.9 19.33c.42 0 .6-.18.83-.4l1.98-1.9 4.1 3c.75.42 1.29.2 1.48-.7l2.68-12.63c.24-1.12-.4-1.56-1.14-1.28L3.8 9.56c-1.1.43-1.08 1.05-.2 1.33l4.05 1.26 9.4-5.92c.44-.29.84-.13.51.17z"/>
                    </svg>
                    Join Telegram
                </a>
            </div>
        </div>
    </div>
</nav>

{{-- PAGE CONTENT --}}
<div class="animate-fade-in">
    @yield('content')
</div>

    {{-- FOOTER --}}
    <footer class="bg-gray-900 text-gray-400 py-12 px-4">
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 mb-8">
                <div class="footer-brand">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="text-sm font-semibold text-white">Wartix</span>
                    </div>
                    <p class="text-xs leading-relaxed text-gray-400">
                        Platform Ticket Assistance untuk event high-demand.
                        Priority Access, Realtime Monitoring, dan update via Telegram.
                    </p>
                </div>
                <div>
                    <h4 class="text-xs font-semibold text-white uppercase tracking-wider mb-3">Product</h4>
                    <ul class="space-y-2 text-xs">
                        <li><a href="{{ route('events.index') }}" class="hover:text-white transition-colors">Events</a></li>
                        <li><a href="{{ route('home') }}#monitor" class="hover:text-white transition-colors">Realtime Monitor</a></li>
                        <li><a href="{{ route('home') }}#cara-order" class="hover:text-white transition-colors">Cara Order</a></li>
                        <li><a href="{{ route('home') }}#faq" class="hover:text-white transition-colors">FAQ</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-xs font-semibold text-white uppercase tracking-wider mb-3">Community</h4>
                    <ul class="space-y-2 text-xs">
                        <li><a href="{{ $telegramLink }}" target="_blank" rel="noopener noreferrer" class="hover:text-white transition-colors">Telegram Channel</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Testimoni</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Event Recap</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-xs font-semibold text-white uppercase tracking-wider mb-3">Legal</h4>
                    <ul class="space-y-2 text-xs">
                        <li><a href="#" class="hover:text-white transition-colors">Terms of Service</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Privacy Policy</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Refund Policy</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 pt-6 flex flex-col sm:flex-row items-center justify-between gap-2">
                <p class="text-xs">&copy; {{ date('Y') }} Wartix. All rights reserved.</p>
                <p class="text-xs">Event Assistance Platform</p>
            </div>
        </div>
    </footer>

{{-- Floating Telegram Button --}}
<a href="{{ $telegramLink }}" target="_blank" rel="noopener noreferrer"
    class="fixed bottom-6 right-6 w-12 h-12 bg-[#229ED9] hover:bg-[#1e8dcc] text-white rounded-full flex items-center justify-center shadow-lg transition-colors z-50">
    <svg class="w-6 h-6" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
        <path d="M9.04 15.38 8.9 19.33c.42 0 .6-.18.83-.4l1.98-1.9 4.1 3c.75.42 1.29.2 1.48-.7l2.68-12.63c.24-1.12-.4-1.56-1.14-1.28L3.8 9.56c-1.1.43-1.08 1.05-.2 1.33l4.05 1.26 9.4-5.92c.44-.29.84-.13.51.17z"/>
    </svg>
</a>

</body>
</html>


