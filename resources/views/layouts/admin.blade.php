<!DOCTYPE html>
<html lang="id">
<head>
    {{-- Favicon --}}
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/logo-w.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/logo-w.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo-w.png') }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') Wartix Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 font-sans antialiased">

<div class="flex h-screen overflow-hidden">

    {{-- SIDEBAR --}}
    <aside class="w-56 bg-white border-r border-gray-100 flex flex-col flex-shrink-0">

        {{-- Logo --}}
        <div class="h-14 flex items-center gap-2.5 px-4 border-b border-gray-100">
            <img src="{{ asset('images/logo-w.png') }}"
                alt="Wartix"
                class="h-7 w-auto"
                style="filter: brightness(0) saturate(100%) invert(29%) sepia(89%) saturate(1234%) hue-rotate(220deg) brightness(97%) contrast(97%);">
            <span class="ml-auto text-xs bg-indigo-50 text-indigo-600 px-1.5 py-0.5 rounded font-medium">
                Admin
            </span>
        </div>

        {{-- Nav --}}
        <nav class="flex-1 py-3 overflow-y-auto">
            <div class="px-3 mb-1">
                <p class="text-xs text-gray-400 uppercase tracking-wider px-2 mb-1">Main</p>
                <a href="{{ route('admin.dashboard') }}"
                    class="sidebar-link flex items-center gap-2.5 px-2 py-2 rounded-lg text-sm
                    {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-50 text-indigo-600 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                    </svg>
                    Dashboard
                </a>
                <a href="{{ route('admin.events.index') }}"
                    class="sidebar-link flex items-center gap-2.5 px-2 py-2 rounded-lg text-sm
                    {{ request()->routeIs('admin.events.*') ? 'bg-indigo-50 text-indigo-600 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Events
                </a>
                <a href="{{ route('admin.orders.index') }}"
                    class="sidebar-link flex items-center gap-2.5 px-2 py-2 rounded-lg text-sm
                    {{ request()->routeIs('admin.orders.*') ? 'bg-indigo-50 text-indigo-600 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    Orders
                </a>
            </div>

            <div class="px-3 mt-3 mb-1">
                <p class="text-xs text-gray-400 uppercase tracking-wider px-2 mb-1">Monitor</p>
                <a href="{{ route('admin.monitor.index') }}"
                    class="sidebar-link flex items-center gap-2.5 px-2 py-2 rounded-lg text-sm
                    {{ request()->routeIs('admin.monitor.*') ? 'bg-indigo-50 text-indigo-600 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    Realtime Monitor
                </a>
                <a href="{{ route('admin.reports.index') }}"
                    class="sidebar-link flex items-center gap-2.5 px-2 py-2 rounded-lg text-sm
                    {{ request()->routeIs('admin.reports.*') ? 'bg-indigo-50 text-indigo-600 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    Reports
                </a>
                <a href="{{ route('admin.search.index') }}"
                    class="sidebar-link flex items-center gap-2.5 px-2 py-2 rounded-lg text-sm
                    {{ request()->routeIs('admin.search.*') ? 'bg-indigo-50 text-indigo-600 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Global Search
                </a>
            </div>

            <div class="px-3 mt-3 mb-1">
                <p class="text-xs text-gray-400 uppercase tracking-wider px-2 mb-1">Settings</p>
                <a href="{{ route('admin.integrations.index') }}"
                    class="sidebar-link flex items-center gap-2.5 px-2 py-2 rounded-lg text-sm
                    {{ request()->routeIs('admin.integrations.*') ? 'bg-indigo-50 text-indigo-600 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                    </svg>
                    Integration
                </a>
                <a href="{{ route('admin.statistics.index') }}"
                    class="sidebar-link flex items-center gap-2.5 px-2 py-2 rounded-lg text-sm
                    {{ request()->routeIs('admin.statistics.*') ? 'bg-indigo-50 text-indigo-600 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/>
                    </svg>
                    Statistics
                </a>
            </div>
        </nav>

        {{-- Admin Info --}}
        <div class="p-3 border-t border-gray-100">
            <div class="flex items-center gap-2.5">
                <div class="w-7 h-7 rounded-full bg-indigo-600 flex items-center justify-center text-white text-xs font-semibold">
                    {{ strtoupper(substr(Auth::guard('admin')->user()->name ?? 'A', 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-medium text-gray-900 truncate">
                        {{ Auth::guard('admin')->user()->name ?? 'Admin' }}
                    </p>
                    <p class="text-xs text-gray-400 truncate">
                        {{ Auth::guard('admin')->user()->role ?? 'admin' }}
                    </p>
                </div>
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" class="text-gray-400 hover:text-red-500 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- MAIN CONTENT --}}
    <div class="flex-1 flex flex-col overflow-hidden">

        {{-- Topbar --}}
        <header class="h-14 bg-white border-b border-gray-100 flex items-center px-5 gap-4 flex-shrink-0">
            <h1 class="text-sm font-semibold text-gray-900 flex-1">@yield('page-title', 'Dashboard')</h1>

            {{-- Notif --}}
            @php
                $waitingOrdersCount = \App\Models\Order::whereIn('order_status', ['waiting', 'processing'])->count();
            @endphp
            <a href="{{ route('admin.orders.index', ['order_status' => 'waiting']) }}"
                title="{{ $waitingOrdersCount }} order membutuhkan konfirmasi"
                class="w-8 h-8 rounded-lg border border-gray-100 flex items-center justify-center relative hover:bg-gray-50 transition-all duration-300">
                <svg class="w-4 h-4 text-gray-500 hover:text-indigo-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                @if($waitingOrdersCount > 0)
                    <span class="absolute top-1.5 right-1.5 w-1.5 h-1.5 bg-red-500 rounded-full notif-dot"></span>
                @endif
            </a>
        </header>

        {{-- Page Content --}}
        <main class="flex-1 overflow-y-auto p-5 animate-fade-in-up">
            @if(session('success'))
                <div class="mb-4 bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-4 bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>

@stack('scripts')
<script>
    // Alpine sudah diload via Vite
</script>

</body>
</html>
