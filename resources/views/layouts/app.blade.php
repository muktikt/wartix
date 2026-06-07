<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Wartix — Priority Ticket Assistance')</title>
    <meta name="description" content="@yield('meta-description', 'Wartix membantu kamu mendapatkan tiket konser, festival, dan fanmeeting dengan Priority Access, Realtime Monitoring, dan update via Telegram.')">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- SEO --}}
    <meta name="robots" content="index, follow">
    <meta property="og:title" content="@yield('title', 'Wartix — Priority Ticket Assistance')">
    <meta property="og:description" content="@yield('meta-description', 'Platform Ticket Assistance untuk event high-demand.')">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Wartix">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('title', 'Wartix')">
    <link rel="canonical" href="{{ url()->current() }}">
</head>
<body class="bg-white font-sans antialiased">

{{-- NAVBAR --}}
<nav class="sticky top-0 z-50 bg-white/95 backdrop-blur border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center h-14 gap-6">

            {{-- Logo --}}
            <a href="{{ route('home') }}" class="flex items-center gap-2 flex-shrink-0">
                <div class="w-7 h-7 bg-indigo-600 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                    </svg>
                </div>
                <div>
                    <span class="text-sm font-semibold text-gray-900">Wartix</span>
                    <span class="block text-xs text-gray-400 leading-none -mt-0.5">Ticket Assistance</span>
                </div>
            </a>

            {{-- Nav Links --}}
            <div class="hidden md:flex items-center gap-1 flex-1">
                <a href="{{ route('events.index') }}"
                    class="px-3 py-1.5 text-sm rounded-lg transition-colors
                    {{ request()->routeIs('events.*') ? 'text-indigo-600 bg-indigo-50' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                    Events
                </a>
                <a href="{{ route('monitor') }}"
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
                @php $tgLink = \App\Models\Setting::get('telegram_group_link', '#'); @endphp
                <a href="{{ $tgLink }}" target="_blank"
                    class="flex items-center gap-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-3.5 py-1.5 rounded-lg transition-colors">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm5.562 8.248l-1.97 9.289c-.145.658-.537.818-1.084.508l-3-2.21-1.447 1.394c-.16.16-.295.295-.605.295l.213-3.053 5.56-5.023c.242-.213-.054-.333-.373-.12L7.412 14.02l-2.96-.924c-.643-.204-.657-.643.136-.953l11.57-4.461c.537-.194 1.006.131.834.566z"/>
                    </svg>
                    Join Telegram
                </a>
            </div>
        </div>
    </div>
</nav>

{{-- PAGE CONTENT --}}
@yield('content')

{{-- FOOTER --}}
<footer class="bg-gray-900 text-gray-400 mt-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 mb-8">
            <div class="col-span-2 md:col-span-1">
                <div class="flex items-center gap-2 mb-3">
                    <div class="w-6 h-6 bg-indigo-600 rounded-md flex items-center justify-center">
                        <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                        </svg>
                    </div>
                    <span class="text-sm font-semibold text-white">Wartix</span>
                </div>
                <p class="text-xs leading-relaxed">
                    Platform Ticket Assistance untuk event high-demand. Priority Access, Realtime Monitoring, dan update via Telegram.
                </p>
            </div>
            <div>
                <h4 class="text-xs font-semibold text-white uppercase tracking-wider mb-3">Product</h4>
                <ul class="space-y-2 text-xs">
                    <li><a href="{{ route('events.index') }}" class="hover:text-white transition-colors">Events</a></li>
                    <li><a href="{{ route('monitor') }}" class="hover:text-white transition-colors">Realtime Monitor</a></li>
                    <li><a href="{{ route('home') }}#cara-order" class="hover:text-white transition-colors">Cara Order</a></li>
                    <li><a href="{{ route('home') }}#faq" class="hover:text-white transition-colors">FAQ</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-xs font-semibold text-white uppercase tracking-wider mb-3">Community</h4>
                <ul class="space-y-2 text-xs">
                    <li><a href="{{ \App\Models\Setting::get('telegram_group_link', '#') }}" target="_blank" class="hover:text-white transition-colors">Telegram Group</a></li>
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
<a href="{{ \App\Models\Setting::get('telegram_group_link', '#') }}" target="_blank"
    class="fixed bottom-6 right-6 w-12 h-12 bg-green-500 hover:bg-green-600 text-white rounded-full flex items-center justify-center shadow-lg transition-colors z-50">
    <svg class="w-6 h-6" viewBox="0 0 24 24" fill="currentColor">
        <path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm5.562 8.248l-1.97 9.289c-.145.658-.537.818-1.084.508l-3-2.21-1.447 1.394c-.16.16-.295.295-.605.295l.213-3.053 5.56-5.023c.242-.213-.054-.333-.373-.12L7.412 14.02l-2.96-.924c-.643-.204-.657-.643.136-.953l11.57-4.461c.537-.194 1.006.131.834.566z"/>
    </svg>
</a>

</body>
</html>