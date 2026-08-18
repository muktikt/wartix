<!DOCTYPE html>
<html lang="id">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#4F46E5">
    <title>@yield('title', 'Warindong Priority Ticket Assistance')</title>
    <meta name="description" content="@yield('meta-description', 'Warindong membantu kamu mendapatkan tiket konser, festival, dan fanmeeting dengan Priority Access, Realtime Monitoring, dan update via Telegram.')">

    {{-- Preconnect hints for external domains --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>

    {{-- Non-blocking Google Font loading --}}
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" media="print" onload="this.media='all'">
    <noscript><link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap"></noscript>

    {{-- Favicon --}}
    <meta property="og:image" content="{{ asset('images/logo-full.png') }}">
    <meta name="twitter:image" content="{{ asset('images/logo-full.png') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/logo-w.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/logo-w.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo-w.png') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- SEO --}}
    <meta name="google-site-verification" content="7Et0CRs828pDWQuyFH_ygTCy8IbI6YDnFwWY4Jx780Y" />
    <meta name="robots" content="index, follow">
    <meta property="og:title" content="@yield('title', 'Warindong Priority Ticket Assistance')">
    <meta property="og:description" content="@yield('meta-description', 'Platform Ticket Assistance untuk event high-demand.')">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:site_name" content="Warindong">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('title', 'Warindong')">
    <link rel="canonical" href="{{ url()->current() }}">
</head>
<body class="bg-white font-sans antialiased">

@php
    $telegramLink = 'https://t.me/wartixdotcom';
    $whatsappLink = 'https://chat.whatsapp.com/CBgJ9tYH2F08OlteajZcBJ?s=cl&p=i&ilr=4';
    $xLink = 'https://x.com/warindongcom';
    $tiktokLink = 'https://www.tiktok.com/@warindong.com';
    $instagramLink = 'https://www.instagram.com/warindongcom';
    $threadsLink = 'https://www.threads.com/@warindongcom';
@endphp

{{-- NAVBAR --}}
<nav class="sticky top-0 z-50 bg-white/95 backdrop-blur border-b border-gray-100 py-2.5 px-4 sm:px-6 lg:px-8 w-full">
    <div class="flex items-center justify-between">

        {{-- Logo & Divider --}}
        <div class="flex items-center gap-3">
            <a href="{{ route('home') }}" class="flex items-center gap-2">
                <img src="{{ asset('images/logo-w.png') }}"
                    alt="Warindong"
                    class="h-8 sm:h-9 w-auto max-w-[180px] object-contain">
            </a>
            <div class="h-4 w-px bg-gray-200"></div>
            <span class="text-[11px] font-medium text-gray-400 tracking-tight">Warindong</span>
        </div>

        {{-- Nav Links --}}
        <div class="hidden md:flex items-center gap-6 lg:gap-8">
            <a href="{{ route('home') }}"
                class="relative py-1 text-xs font-semibold uppercase tracking-wider transition-colors btn-press text-gray-600 hover:text-indigo-600 active:text-indigo-700">
                Home
            </a>
            <a href="{{ route('home') }}#active-events"
                class="relative py-1 text-xs font-semibold uppercase tracking-wider transition-colors btn-press text-gray-600 hover:text-indigo-600 active:text-indigo-700">
                Events
            </a>
            <a href="{{ route('home') }}#monitor"
                class="relative py-1 text-xs font-semibold uppercase tracking-wider transition-colors btn-press text-gray-600 hover:text-indigo-600 active:text-indigo-700">
                Realtime Monitor
            </a>
            <a href="{{ route('home') }}#cara-order"
                class="relative py-1 text-xs font-semibold uppercase tracking-wider transition-colors btn-press text-gray-600 hover:text-indigo-600 active:text-indigo-700">
                Cara Order
            </a>
            <a href="{{ route('home') }}#faq"
                class="relative py-1 text-xs font-semibold uppercase tracking-wider transition-colors btn-press text-gray-600 hover:text-indigo-600 active:text-indigo-700">
                FAQ
            </a>
        </div>

        {{-- CTA Button --}}
        <div class="flex items-center gap-2">
            <a href="{{ $telegramLink }}" target="_blank" rel="noopener noreferrer"
                class="flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold pl-4 pr-1.5 py-1.5 rounded-full transition-all duration-300 shadow-md shadow-indigo-200 hover:shadow-indigo-300 group btn-press">
                <span>Join Telegram</span>
                <span class="w-6 h-6 rounded-full bg-white/20 flex items-center justify-center transition-transform group-hover:translate-x-0.5 group-hover:-translate-y-0.5">
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M9.04 15.38 8.9 19.33c.42 0 .6-.18.83-.4l1.98-1.9 4.1 3c.75.42 1.29.2 1.48-.7l2.68-12.63c.24-1.12-.4-1.56-1.14-1.28L3.8 9.56c-1.1.43-1.08 1.05-.2 1.33l4.05 1.26 9.4-5.92c.44-.29.84-.13.51.17z"/>
                    </svg>
                </span>
            </a>
        </div>
    </div>
</nav>

{{-- PAGE CONTENT --}}
<main class="animate-fade-in-up">
    @yield('content')
</main>

    {{-- FOOTER --}}
    <footer class="bg-gray-900 text-gray-400 py-12 px-4 reveal-drop">
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 mb-8">
                <div class="footer-brand">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="text-sm font-semibold text-white">Warindong</span>
                    </div>
                    <p class="text-xs leading-relaxed text-gray-400 mb-4">
                        Platform Ticket Assistance untuk event high-demand.
                        Priority Access, Realtime Monitoring, dan update via Telegram.
                    </p>
                </div>
                <div>
                    <h4 class="text-xs font-semibold text-white uppercase tracking-wider mb-3">Product</h4>
                    <ul class="space-y-2 text-xs">
                        <li><a href="{{ route('events.index') }}" class="hover:text-white transition-colors duration-200">Events</a></li>
                        <li><a href="{{ route('home') }}#monitor" class="hover:text-white transition-colors duration-200">Realtime Monitor</a></li>
                        <li><a href="{{ route('home') }}#cara-order" class="hover:text-white transition-colors duration-200">Cara Order</a></li>
                        <li><a href="{{ route('home') }}#faq" class="hover:text-white transition-colors duration-200">FAQ</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-xs font-semibold text-white uppercase tracking-wider mb-3">Community</h4>
                    <ul class="space-y-2 text-xs">
                        <li><a href="{{ $telegramLink }}" target="_blank" rel="noopener noreferrer" class="hover:text-white transition-colors duration-200">Telegram Channel</a></li>
                        <li><a href="{{ $whatsappLink }}" target="_blank" rel="noopener noreferrer" class="hover:text-white transition-colors duration-200">WhatsApp Group</a></li>
                        <li><a href="{{ $xLink }}" target="_blank" rel="noopener noreferrer" class="hover:text-white transition-colors duration-200">X (Twitter)</a></li>
                        <li><a href="{{ $tiktokLink }}" target="_blank" rel="noopener noreferrer" class="hover:text-white transition-colors duration-200">TikTok</a></li>
                        <li><a href="{{ $instagramLink }}" target="_blank" rel="noopener noreferrer" class="hover:text-white transition-colors duration-200">Instagram</a></li>
                        <li><a href="{{ $threadsLink }}" target="_blank" rel="noopener noreferrer" class="hover:text-white transition-colors duration-200">Threads</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-xs font-semibold text-white uppercase tracking-wider mb-3">Legal</h4>
                    <ul class="space-y-2 text-xs">
                        <li><a href="#" class="hover:text-white transition-colors duration-200">Terms of Service</a></li>
                        <li><a href="#" class="hover:text-white transition-colors duration-200">Privacy Policy</a></li>
                        <li><a href="#" class="hover:text-white transition-colors duration-200">Refund Policy</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 pt-6 flex flex-col sm:flex-row items-center justify-between gap-2">
                <p class="text-xs">&copy; {{ date('Y') }} Warindong. All rights reserved.</p>
                <p class="text-xs">Event Assistance Platform</p>
            </div>
        </div>
    </footer>

{{-- Floating Telegram Button --}}
<a href="{{ $telegramLink }}" target="_blank" rel="noopener noreferrer"
    class="fixed bottom-6 right-6 w-12 h-12 bg-[#229ED9] hover:bg-[#1e8dcc] text-white rounded-full flex items-center justify-center shadow-lg hover:shadow-xl transition-all duration-300 z-50 float-btn-glow hover:scale-110 btn-press">
    <svg class="w-6 h-6" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
        <path d="M9.04 15.38 8.9 19.33c.42 0 .6-.18.83-.4l1.98-1.9 4.1 3c.75.42 1.29.2 1.48-.7l2.68-12.63c.24-1.12-.4-1.56-1.14-1.28L3.8 9.56c-1.1.43-1.08 1.05-.2 1.33l4.05 1.26 9.4-5.92c.44-.29.84-.13.51.17z"/>
    </svg>
</a>

</body>
</html>


