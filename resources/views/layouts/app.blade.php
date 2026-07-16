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
    $whatsappLink = 'https://chat.whatsapp.com/CBgJ9tYH2F08OlteajZcBJ?s=cl&p=i&ilr=4';
    $xLink = 'https://x.com/wartixcom';
    $tiktokLink = 'https://www.tiktok.com/@wartix.com';
    $instagramLink = '#'; // Future use
    $threadsLink = '#'; // Future use
@endphp

{{-- NAVBAR --}}
<nav class="sticky top-0 z-50 bg-white/95 backdrop-blur border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center h-14 gap-6">

            {{-- Logo --}}
            <a href="{{ route('home') }}" class="flex items-center gap-2 flex-shrink-0 -ml-5 sm:-ml-8 group">
                <img src="{{ asset('images/logo-w.png') }}"
                    alt="Wartix"
                    class="h-10 sm:h-11 w-auto max-w-[210px] object-contain transition-transform duration-300 group-hover:scale-110 group-hover:rotate-3">
                <span class="hidden sm:inline text-sm font-semibold text-gray-900 transition-colors duration-200 group-hover:text-indigo-600">Wartix</span>
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
<div class="animate-fade-in-up">
    @yield('content')
</div>

    {{-- FOOTER --}}
    <footer class="bg-gray-900 text-gray-400 py-12 px-4 scroll-animate" data-delay="100">
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 mb-8">
                <div class="footer-brand">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="text-sm font-semibold text-white">Wartix</span>
                    </div>
                    <p class="text-xs leading-relaxed text-gray-400 mb-4">
                        Platform Ticket Assistance untuk event high-demand.
                        Priority Access, Realtime Monitoring, dan update via Telegram.
                    </p>
                    <div class="flex items-center gap-3">
                        <a href="{{ $whatsappLink }}" target="_blank" rel="noopener noreferrer" class="text-gray-400 hover:text-green-500 transition-colors duration-200" title="WhatsApp Group">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946C.06 5.348 5.397.01 12.008.01c3.202.001 6.212 1.246 8.477 3.514 2.266 2.268 3.507 5.28 3.505 8.484-.004 6.657-5.34 11.997-11.953 11.997-2.005-.001-3.973-.502-5.724-1.455L0 24zm6.59-4.846c1.6.95 3.197 1.45 4.817 1.451 5.485 0 9.948-4.469 9.952-9.953.002-2.656-1.03-5.153-2.905-7.03C16.637 1.745 14.145.717 11.492.717 6.004.717 1.542 5.185 1.538 10.67c-.001 1.77.462 3.497 1.342 5.034l-.993 3.627 3.71-.973zm11.538-4.63c-.301-.15-1.78-.879-2.056-.979-.275-.1-.475-.15-.675.15-.2.3-.775.979-.95 1.179-.175.2-.35.225-.65.075-1.02-.519-1.797-1.012-2.521-2.262-.19-.328.19-.304.543-.997.099-.2.05-.375-.025-.525-.075-.15-.675-1.625-.925-2.225-.244-.589-.493-.51-.675-.52-.172-.007-.368-.009-.565-.009-.197 0-.517.074-.788.374-.27.3-1.03 1.008-1.03 2.46 0 1.453 1.055 2.858 1.202 3.058.147.2 2.078 3.174 5.034 4.453.703.304 1.252.486 1.68.623.707.225 1.35.193 1.86.117.567-.085 1.78-.727 2.03-1.429.25-.701.25-1.301.175-1.429-.075-.125-.275-.201-.575-.351z"/>
                            </svg>
                        </a>
                        <a href="{{ $telegramLink }}" target="_blank" rel="noopener noreferrer" class="text-gray-400 hover:text-sky-400 transition-colors duration-200" title="Telegram Channel">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm4.64 6.8c-.15 1.58-.8 5.42-1.13 7.19-.14.75-.42 1-.68 1.03-.58.05-1.02-.38-1.58-.75-.88-.58-1.38-.94-2.23-1.5-.99-.65-.35-1.01.22-1.59.15-.15 2.71-2.48 2.76-2.69.01-.03.01-.14-.07-.2-.08-.06-.19-.04-.27-.02-.11.02-1.89 1.2-5.33 3.52-.5.35-.96.52-1.37.51-.45-.01-1.32-.26-1.97-.47-.8-.26-1.43-.4-1.38-.85.03-.24.36-.49.99-.75 3.86-1.68 6.43-2.78 7.72-3.3 3.67-1.48 4.43-1.74 4.93-1.75.11 0 .36.03.52.16.14.11.18.27.2.39z"/>
                            </svg>
                        </a>
                        <a href="{{ $xLink }}" target="_blank" rel="noopener noreferrer" class="text-gray-400 hover:text-white transition-colors duration-200" title="X (Twitter)">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                            </svg>
                        </a>
                        <a href="{{ $tiktokLink }}" target="_blank" rel="noopener noreferrer" class="text-gray-400 hover:text-[#FE2C55] transition-colors duration-200" title="TikTok">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.02 1.63 4.17 1.13 1.2 2.68 1.93 4.3 2.11v3.78c-1.78-.15-3.49-.89-4.82-2.13-.07-.06-.11-.08-.21-.01-.05.95-.02 1.9-.02 2.86 0 2.27-.45 4.51-1.66 6.43-1.41 2.21-3.66 3.75-6.22 4.22-2.1.39-4.29.17-6.26-.67-2.58-1.07-4.57-3.23-5.32-5.91-.77-2.67-.36-5.59 1.14-7.91 1.77-2.73 4.79-4.38 8.02-4.37.1 0 .2 0 .3 0v3.72c-2.22-.05-4.34 1.12-5.4 3.07-1.13 2.01-1.02 4.61.27 6.5 1.24 1.83 3.51 2.76 5.69 2.37 1.85-.32 3.44-1.62 4.12-3.37.45-1.15.54-2.39.52-3.61-.01-3.29-.01-6.57-.02-9.86z"/>
                            </svg>
                        </a>
                        {{-- Instagram (Ganti '#' dengan link jika sudah ada) --}}
                        {{--
                        <a href="{{ $instagramLink }}" target="_blank" rel="noopener noreferrer" class="text-gray-400 hover:text-[#E1306C] transition-colors duration-200" title="Instagram">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.051.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/>
                            </svg>
                        </a>
                        --}}
                        {{-- Threads (Ganti '#' dengan link jika sudah ada) --}}
                        {{--
                        <a href="{{ $threadsLink }}" target="_blank" rel="noopener noreferrer" class="text-gray-400 hover:text-white transition-colors duration-200" title="Threads">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M12.5.75c-6.19 0-10.75 4.09-10.75 11.27 0 7.42 4.7 11.23 10.75 11.23 3.62 0 6.64-1.32 8.34-3.67a1 1 0 10-1.6-1.2c-1.3 1.8-3.6 2.87-6.74 2.87-5 0-8.75-3-8.75-9.23 0-5.84 3.58-9.27 8.75-9.27 4.6 0 7.8 2.8 7.8 7.54v2.79c0 1-.58 1.63-1.27 1.63-.56 0-.98-.44-.98-1.4V10.2a1 1 0 00-1-1 4.54 4.54 0 00-3.79 1.83 5.43 5.43 0 00-4.66-2c-3.15 0-5.55 2.5-5.55 5.82 0 3.29 2.4 5.81 5.55 5.81 2.37 0 4.14-1.32 4.75-3a3.3 3.3 0 003.09 1.94c1.86 0 3.23-1.44 3.23-3.63v-2.79c0-5.88-4.08-9.54-9.8-9.54zm-2.22 13.06c-1.92 0-3.33-1.52-3.33-3.81s1.41-3.81 3.33-3.81 3.33 1.52 3.33 3.81-1.41 3.81-3.33 3.81z"/>
                            </svg>
                        </a>
                        --}}
                    </div>
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
                        {{--
                        <li><a href="{{ $instagramLink }}" target="_blank" rel="noopener noreferrer" class="hover:text-white transition-colors duration-200">Instagram</a></li>
                        <li><a href="{{ $threadsLink }}" target="_blank" rel="noopener noreferrer" class="hover:text-white transition-colors duration-200">Threads</a></li>
                        --}}
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
                <p class="text-xs">&copy; {{ date('Y') }} Wartix. All rights reserved.</p>
                <p class="text-xs">Event Assistance Platform</p>
            </div>
        </div>
    </footer>

{{-- Floating Telegram Button --}}
<a href="{{ $telegramLink }}" target="_blank" rel="noopener noreferrer"
    class="fixed bottom-6 right-6 w-12 h-12 bg-[#229ED9] hover:bg-[#1e8dcc] text-white rounded-full flex items-center justify-center shadow-lg hover:shadow-xl transition-all duration-300 z-50 float-btn-glow hover:scale-110">
    <svg class="w-6 h-6" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
        <path d="M9.04 15.38 8.9 19.33c.42 0 .6-.18.83-.4l1.98-1.9 4.1 3c.75.42 1.29.2 1.48-.7l2.68-12.63c.24-1.12-.4-1.56-1.14-1.28L3.8 9.56c-1.1.43-1.08 1.05-.2 1.33l4.05 1.26 9.4-5.92c.44-.29.84-.13.51.17z"/>
    </svg>
</a>

</body>
</html>


