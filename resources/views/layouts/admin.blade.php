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
    <title>@yield('title', 'Dashboard') Warindong Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 font-sans antialiased">

<div class="flex h-screen overflow-hidden" x-data="{ 
    sidebarOpen: false, 
    sidebarCollapsed: localStorage.getItem('admin_sidebar_collapsed') === 'true',
    toggleCollapse() {
        this.sidebarCollapsed = !this.sidebarCollapsed;
        localStorage.setItem('admin_sidebar_collapsed', this.sidebarCollapsed);
    }
}">

    {{-- Backdrop for mobile --}}
    <div x-show="sidebarOpen" 
         @click="sidebarOpen = false" 
         class="fixed inset-0 z-30 bg-gray-900/50 backdrop-blur-sm md:hidden"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         style="display: none;">
    </div>

    {{-- SIDEBAR --}}
    <aside class="fixed inset-y-0 left-0 z-40 bg-white border-r border-gray-100 flex flex-col flex-shrink-0 transition-all duration-300 ease-in-out transform md:static"
           :class="{
               'md:w-16': sidebarCollapsed,
               'md:w-56': !sidebarCollapsed,
               'translate-x-0 w-56': sidebarOpen,
               '-translate-x-full md:translate-x-0': !sidebarOpen
           }">

        {{-- Logo --}}
        <div class="h-14 flex items-center justify-between px-3 border-b border-gray-100">
            <div class="flex items-center gap-2.5 overflow-hidden">
                <img src="{{ asset('images/logo-w.png') }}"
                    alt="Warindong"
                    class="h-7 w-auto flex-shrink-0">
                <span class="text-xs bg-indigo-50 text-indigo-600 px-1.5 py-0.5 rounded font-medium" :class="{ 'md:hidden': sidebarCollapsed }">
                    Admin
                </span>
            </div>

            {{-- Desktop collapse toggle inside sidebar header --}}
            <button @click="toggleCollapse()" class="hidden md:flex items-center justify-center w-7 h-7 rounded-lg text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 transition-all cursor-pointer flex-shrink-0" :title="sidebarCollapsed ? 'Buka Sidebar' : 'Kecilkan Sidebar'">
                <svg class="w-4 h-4 transition-transform duration-300" :class="{ 'rotate-180': sidebarCollapsed }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/>
                </svg>
            </button>

            {{-- Mobile drawer close button --}}
            <button @click.stop="sidebarOpen = false" class="text-gray-400 hover:text-gray-600 focus:outline-none md:hidden p-1 cursor-pointer" title="Tutup Menu">
                <svg class="w-5 h-5 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Nav --}}
        <nav class="flex-1 py-3 overflow-y-auto">
            <div class="px-2 mb-1">
                <p class="text-xs text-gray-400 uppercase tracking-wider px-2 mb-1" :class="{ 'md:hidden': sidebarCollapsed }">Main</p>
                <a href="{{ route('admin.dashboard') }}" title="Dashboard"
                    class="sidebar-link flex items-center gap-2.5 px-2.5 py-2 rounded-lg text-sm transition-all
                    {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-50 text-indigo-600 font-medium' : 'text-gray-600 hover:bg-gray-50' }}"
                    :class="{ 'md:justify-center md:px-0': sidebarCollapsed }">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                    </svg>
                    <span :class="{ 'md:hidden': sidebarCollapsed }">Dashboard</span>
                </a>
                <a href="{{ route('admin.events.index') }}" title="Events"
                    class="sidebar-link flex items-center gap-2.5 px-2.5 py-2 rounded-lg text-sm transition-all
                    {{ request()->routeIs('admin.events.*') ? 'bg-indigo-50 text-indigo-600 font-medium' : 'text-gray-600 hover:bg-gray-50' }}"
                    :class="{ 'md:justify-center md:px-0': sidebarCollapsed }">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span :class="{ 'md:hidden': sidebarCollapsed }">Events</span>
                </a>
                <a href="{{ route('admin.orders.index') }}" title="Orders"
                    class="sidebar-link flex items-center gap-2.5 px-2.5 py-2 rounded-lg text-sm transition-all
                    {{ request()->routeIs('admin.orders.*') ? 'bg-indigo-50 text-indigo-600 font-medium' : 'text-gray-600 hover:bg-gray-50' }}"
                    :class="{ 'md:justify-center md:px-0': sidebarCollapsed }">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <span :class="{ 'md:hidden': sidebarCollapsed }">Orders</span>
                </a>
            </div>

            <div class="px-2 mt-3 mb-1">
                <p class="text-xs text-gray-400 uppercase tracking-wider px-2 mb-1" :class="{ 'md:hidden': sidebarCollapsed }">Monitor</p>
                <a href="{{ route('admin.monitor.index') }}" title="Realtime Monitor"
                    class="sidebar-link flex items-center gap-2.5 px-2.5 py-2 rounded-lg text-sm transition-all
                    {{ request()->routeIs('admin.monitor.*') ? 'bg-indigo-50 text-indigo-600 font-medium' : 'text-gray-600 hover:bg-gray-50' }}"
                    :class="{ 'md:justify-center md:px-0': sidebarCollapsed }">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    <span :class="{ 'md:hidden': sidebarCollapsed }">Realtime Monitor</span>
                </a>
                <a href="{{ route('admin.reports.index') }}" title="Reports"
                    class="sidebar-link flex items-center gap-2.5 px-2.5 py-2 rounded-lg text-sm transition-all
                    {{ request()->routeIs('admin.reports.*') ? 'bg-indigo-50 text-indigo-600 font-medium' : 'text-gray-600 hover:bg-gray-50' }}"
                    :class="{ 'md:justify-center md:px-0': sidebarCollapsed }">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    <span :class="{ 'md:hidden': sidebarCollapsed }">Reports</span>
                </a>

            </div>

            <div class="px-2 mt-3 mb-1">
                <p class="text-xs text-gray-400 uppercase tracking-wider px-2 mb-1" :class="{ 'md:hidden': sidebarCollapsed }">Settings</p>
                <a href="{{ route('admin.integrations.index') }}" title="Integration"
                    class="sidebar-link flex items-center gap-2.5 px-2.5 py-2 rounded-lg text-sm transition-all
                    {{ request()->routeIs('admin.integrations.*') ? 'bg-indigo-50 text-indigo-600 font-medium' : 'text-gray-600 hover:bg-gray-50' }}"
                    :class="{ 'md:justify-center md:px-0': sidebarCollapsed }">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                    </svg>
                    <span :class="{ 'md:hidden': sidebarCollapsed }">Integration</span>
                </a>
                <a href="{{ route('admin.statistics.index') }}" title="Statistics"
                    class="sidebar-link flex items-center gap-2.5 px-2.5 py-2 rounded-lg text-sm transition-all
                    {{ request()->routeIs('admin.statistics.*') ? 'bg-indigo-50 text-indigo-600 font-medium' : 'text-gray-600 hover:bg-gray-50' }}"
                    :class="{ 'md:justify-center md:px-0': sidebarCollapsed }">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/>
                    </svg>
                    <span :class="{ 'md:hidden': sidebarCollapsed }">Statistics</span>
                </a>
            </div>
        </nav>

        {{-- Admin Info --}}
        <div class="p-3 border-t border-gray-100">
            <div class="flex items-center" :class="{ 'md:justify-center': sidebarCollapsed, 'gap-2.5': !sidebarCollapsed }">
                <div class="w-7 h-7 rounded-full bg-indigo-600 flex items-center justify-center text-white text-xs font-semibold flex-shrink-0" title="{{ Auth::guard('admin')->user()->name ?? 'Admin' }}">
                    {{ strtoupper(substr(Auth::guard('admin')->user()->name ?? 'A', 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0" :class="{ 'md:hidden': sidebarCollapsed }">
                    <p class="text-xs font-medium text-gray-900 truncate">
                        {{ Auth::guard('admin')->user()->name ?? 'Admin' }}
                    </p>
                    <p class="text-xs text-gray-400 truncate">
                        {{ Auth::guard('admin')->user()->role ?? 'admin' }}
                    </p>
                </div>
                <form method="POST" action="{{ route('admin.logout') }}" :class="{ 'md:hidden': sidebarCollapsed }">
                    @csrf
                    <button type="submit" class="text-gray-400 hover:text-red-500 transition-colors" title="Logout">
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
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">

        {{-- Topbar --}}
        <header class="h-14 bg-white border-b border-gray-100 flex items-center px-4 sm:px-5 gap-3 flex-shrink-0">
            <button @click="sidebarOpen = true" class="text-gray-500 hover:text-gray-600 focus:outline-none md:hidden p-1" title="Buka Menu">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            <h1 class="text-sm font-semibold text-gray-900 flex-1 truncate">@yield('page-title', 'Dashboard')</h1>

            {{-- Notif --}}
            @php
                $unreadNotifCount = 0;
                if (\Illuminate\Support\Facades\Schema::hasTable('admin_notifications')) {
                    $unreadNotifCount = \App\Models\AdminNotification::unread()->count();
                }
            @endphp
            <a href="{{ route('admin.notifications.index') }}"
                title="{{ $unreadNotifCount }} notifikasi belum dibaca"
                class="w-8 h-8 rounded-lg border border-gray-100 flex items-center justify-center relative hover:bg-gray-50 transition-all duration-300">
                <svg class="w-4 h-4 text-gray-500 hover:text-indigo-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                @if($unreadNotifCount > 0)
                    <span class="absolute -top-0.5 -right-0.5 min-w-[16px] h-4 bg-red-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center px-1 notif-dot">
                        {{ $unreadNotifCount > 99 ? '99+' : $unreadNotifCount }}
                    </span>
                @endif
            </a>
        </header>

        {{-- Page Content --}}
        <main class="flex-1 overflow-y-auto p-5 animate-fade-in-up">
            @if(session('success'))
                <div class="mb-4 bg-emerald-50/80 backdrop-blur border border-emerald-200/80 text-emerald-800 text-sm px-4 py-3 rounded-2xl flex items-center gap-3 shadow-sm animate-fade-in-down">
                    <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div class="mb-4 bg-rose-50/80 backdrop-blur border border-rose-200/80 text-rose-800 text-sm px-4 py-3 rounded-2xl flex items-center gap-3 shadow-sm animate-fade-in-down">
                    <svg class="w-5 h-5 text-rose-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="font-medium">{{ session('error') }}</span>
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
