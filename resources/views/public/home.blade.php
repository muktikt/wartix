@extends('layouts.app')

@section('title', 'Wartix Priority Ticket Assistance')

@section('content')

@php
    $telegramLink = 'https://t.me/wartixdotcom';
    $whatsappLink = 'https://chat.whatsapp.com/CBgJ9tYH2F08OlteajZcBJ?s=cl&p=i&ilr=4';
    $xLink = 'https://x.com/wartixcom';
    $tiktokLink = 'https://www.tiktok.com/@wartix.com';
    $instagramLink = '#'; 
    $threadsLink = '#'; 
@endphp

{{-- HERO --}}
<section class="animated-gradient py-16 px-4">
    <div class="max-w-7xl mx-auto grid md:grid-cols-2 gap-12 items-center">

        {{-- Left --}}
        <div class="animate-fade-in-up">
            <div class="flex flex-wrap gap-2 mb-5">
                <span class="inline-flex items-center gap-1.5 text-xs font-medium bg-indigo-50 text-indigo-700 px-3 py-1 rounded-full animate-pulse-soft">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    Priority Access
                </span>
                <span class="inline-flex items-center gap-1.5 text-xs font-medium bg-purple-50 text-purple-700 px-3 py-1 rounded-full">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Ticket Assistance
                </span>
                <span class="inline-flex items-center gap-1.5 text-xs font-medium bg-green-50 text-green-700 px-3 py-1 rounded-full">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    Realtime Monitoring
                </span>
            </div>

            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 leading-tight mb-4">
                Priority Ticket Assistance<br>
                <span class="text-indigo-600">for High-Demand Events</span>
            </h1>

            <p class="text-gray-500 text-base leading-relaxed mb-8 max-w-md">
                Wartix membantu kamu mendapatkan tiket konser, festival, dan fanmeeting impian dengan layanan Ticket Assistance, Realtime Monitoring, dan notifikasi langsung via Telegram.
            </p>

            <div class="flex flex-wrap gap-3">
                <a href="#active-events"
                    class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm px-5 py-2.5 rounded-xl transition-all duration-300 hover-lift hover:shadow-lg hover:shadow-indigo-500/25">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    View Active Events
                </a>
                <a href="https://t.me/wartixdotcom" target="_blank" rel="noopener noreferrer"
                    class="inline-flex items-center gap-2 bg-white hover:bg-gray-50 text-gray-700 border border-gray-200 font-medium text-sm px-5 py-2.5 rounded-xl transition-all duration-300 hover-lift hover:border-indigo-200">
                    <svg class="w-4 h-4 text-[#229ED9]" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <path d="M9.04 15.38 8.9 19.33c.42 0 .6-.18.83-.4l1.98-1.9 4.1 3c.75.42 1.29.2 1.48-.7l2.68-12.63c.24-1.12-.4-1.56-1.14-1.28L3.8 9.56c-1.1.43-1.08 1.05-.2 1.33l4.05 1.26 9.4-5.92c.44-.29.84-.13.51.17z"/>
                    </svg>
                    Join Telegram Channel
                </a>
            </div>

            {{-- Social Media Links --}}
            <div class="mt-8 pt-6 border-t border-gray-200/50 flex flex-wrap items-center gap-4">
                <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Follow Us:</span>
                <div class="flex items-center gap-3">
                    <!-- WhatsApp -->
                    <a href="{{ $whatsappLink }}" target="_blank" rel="noopener noreferrer" 
                        class="group flex items-center justify-center w-10 h-10 rounded-xl bg-white border border-gray-200 text-gray-400 hover:text-emerald-500 hover:border-emerald-300 hover:bg-emerald-50 transition-all duration-300 hover:scale-105 shadow-sm" title="WhatsApp Group">
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-emerald-500 transition-colors duration-300" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946C.06 5.348 5.397.01 12.008.01c3.202.001 6.212 1.246 8.477 3.514 2.266 2.268 3.507 5.28 3.505 8.484-.004 6.657-5.34 11.997-11.953 11.997-2.005-.001-3.973-.502-5.724-1.455L0 24zm6.59-4.846c1.6.95 3.197 1.45 4.817 1.451 5.485 0 9.948-4.469 9.952-9.953.002-2.656-1.03-5.153-2.905-7.03C16.637 1.745 14.145.717 11.492.717 6.004.717 1.542 5.185 1.538 10.67c-.001 1.77.462 3.497 1.342 5.034l-.993 3.627 3.71-.973zm11.538-4.63c-.301-.15-1.78-.879-2.056-.979-.275-.1-.475-.15-.675.15-.2.3-.775.979-.95 1.179-.175.2-.35.225-.65.075-1.02-.519-1.797-1.012-2.521-2.262-.19-.328.19-.304.543-.997.099-.2.05-.375-.025-.525-.075-.15-.675-1.625-.925-2.225-.244-.589-.493-.51-.675-.52-.172-.007-.368-.009-.565-.009-.197 0-.517.074-.788.374-.27.3-1.03 1.008-1.03 2.46 0 1.453 1.055 2.858 1.202 3.058.147.2 2.078 3.174 5.034 4.453.703.304 1.252.486 1.68.623.707.225 1.35.193 1.86.117.567-.085 1.78-.727 2.03-1.429.25-.701.25-1.301.175-1.429-.075-.125-.275-.201-.575-.351z"/>
                        </svg>
                    </a>
                    <!-- X (Twitter) -->
                    <a href="{{ $xLink }}" target="_blank" rel="noopener noreferrer" 
                        class="group flex items-center justify-center w-10 h-10 rounded-xl bg-white border border-gray-200 text-gray-400 hover:text-black hover:border-slate-800 hover:bg-slate-50 transition-all duration-300 hover:scale-105 shadow-sm" title="X (Twitter)">
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-slate-900 transition-colors duration-300" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                        </svg>
                    </a>
                    <!-- TikTok -->
                    <a href="{{ $tiktokLink }}" target="_blank" rel="noopener noreferrer" 
                        class="group flex items-center justify-center w-10 h-10 rounded-xl bg-white border border-gray-200 text-gray-400 hover:text-rose-500 hover:border-rose-300 hover:bg-rose-50 transition-all duration-300 hover:scale-105 shadow-sm" title="TikTok">
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-rose-500 transition-colors duration-300" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.02 1.63 4.17 1.13 1.2 2.68 1.93 4.3 2.11v3.78c-1.78-.15-3.49-.89-4.82-2.13-.07-.06-.11-.08-.21-.01-.05.95-.02 1.9-.02 2.86 0 2.27-.45 4.51-1.66 6.43-1.41 2.21-3.66 3.75-6.22 4.22-2.1.39-4.29.17-6.26-.67-2.58-1.07-4.57-3.23-5.32-5.91-.77-2.67-.36-5.59 1.14-7.91 1.77-2.73 4.79-4.38 8.02-4.37.1 0 .2 0 .3 0v3.72c-2.22-.05-4.34 1.12-5.4 3.07-1.13 2.01-1.02 4.61.27 6.5 1.24 1.83 3.51 2.76 5.69 2.37 1.85-.32 3.44-1.62 4.12-3.37.45-1.15.54-2.39.52-3.61-.01-3.29-.01-6.57-.02-9.86z"/>
                        </svg>
                    </a>
                    
                    <!-- Divider -->
                    <span class="w-px h-6 bg-gray-200 mx-1"></span>

                    <!-- Instagram -->
                    <a href="{{ $instagramLink }}" onclick="return false;"
                        class="group flex items-center justify-center w-10 h-10 rounded-xl bg-gray-50 border border-gray-150 text-gray-300 cursor-not-allowed opacity-50 transition-all duration-300" title="Instagram (Segera)">
                        <svg class="w-5 h-5 text-gray-300 transition-colors duration-300" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.051.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/>
                        </svg>
                    </a>
                    <!-- Threads -->
                    <a href="{{ $threadsLink }}" onclick="return false;"
                        class="group flex items-center justify-center w-10 h-10 rounded-xl bg-gray-50 border border-gray-150 text-gray-300 cursor-not-allowed opacity-50 transition-all duration-300" title="Threads (Segera)">
                        <svg class="w-5 h-5 text-gray-300 transition-colors duration-300" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M12.27 2c-5.52 0-10 4.48-10 10s4.48 10 10 10 10-4.48 10-10-4.48-10-10-10zm.11 16.48c-3.15 0-5.69-2.22-5.69-5.32 0-3.24 2.45-5.59 5.89-5.59 1.48 0 2.76.47 3.51 1.25.13.14.13.36 0 .5l-.89.89c-.12.12-.32.13-.46.02-.57-.46-1.39-.77-2.18-.77-2.14 0-3.66 1.48-3.66 3.65 0 2.11 1.54 3.52 3.69 3.52 1.34 0 2.37-.58 2.89-1.28.1-.14.28-.19.43-.1l1.17.68c.15.09.2.28.1.43-.88 1.35-2.61 2.17-4.87 2.17zm6.05-3.05c-.13.14-.35.15-.49.02l-.93-.93c-.13-.13-.13-.35 0-.48 1.09-1.09 1.63-2.66 1.63-4.54 0-3.75-2.68-6.1-6.26-6.1-3.68 0-6.4 2.42-6.4 6.2 0 3.64 2.59 6.02 6.09 6.02.82 0 1.61-.15 2.33-.43.19-.07.4.02.48.2l.48 1.12c.08.19-.01.41-.2.49-.97.37-2.03.57-3.13.57-4.71 0-8.19-3.27-8.19-8.02 0-4.88 3.68-8.2 8.52-8.2 4.74 0 8.12 3.12 8.12 8.1 0 2.41-.7 4.45-2.07 5.76z"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        {{-- Right --}}
        <div class="flex justify-center md:justify-end animate-fade-in-up">
            <div class="bg-white border border-gray-100 rounded-2xl p-5 w-full max-w-sm shadow-sm hover-glow transition-all duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-indigo-600 animate-float" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                        </svg>
                        <span class="text-sm font-semibold text-gray-900">Preview Realtime Monitor</span>
                    </div>
                    <span class="flex items-center gap-1 text-xs text-green-600 bg-green-50 px-2 py-0.5 rounded-full font-medium">
                        <span class="w-1.5 h-1.5 bg-green-500 rounded-full live-indicator"></span>
                        Live
                    </span>
                </div>

                <div class="text-xs text-gray-500 leading-relaxed mb-4">
                    Klik <span class="font-medium text-gray-700">Lihat detail</span> untuk langsung turun ke bagian Realtime Success Monitor di dashboard ini.
                </div>

                @php
                    $previewSuccess = $recentSuccess->take(4);
                @endphp

                <div class="space-y-2">
                    @forelse($previewSuccess as $log)
                        @php
                            $email = \App\Services\MaskService::email($log->email ?? 'us***@example.com');
                            $event = $log->event->title ?? '-';
                            $phase = $log->salePhase->name ?? '-';
                            $qty = $log->qty;
                        @endphp
                        <div class="rounded-xl border border-gray-100 bg-gray-50 px-3 py-2 transition-transform duration-300 hover:scale-[1.02]">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="bg-green-500/15 text-green-600 text-[10px] font-semibold px-2 py-0.5 rounded">SUCCESS</span>
                                <span class="text-xs text-gray-500 truncate">{{ $email }}</span>
                            </div>
                            <div class="flex items-center gap-2 text-xs text-gray-700">
                                <span class="font-medium truncate">{{ $event }}</span>
                                <span class="text-gray-300">&bull;</span>
                                <span class="truncate">{{ $phase }}</span>
                                <span class="text-gray-300">&bull;</span>
                                <span>x{{ $qty }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="rounded-xl border border-dashed border-gray-200 bg-gray-50 px-3 py-6 text-center text-xs text-gray-400">
                            Belum ada data sukses untuk ditampilkan.
                        </div>
                    @endforelse
                </div>

                <a href="#monitor"
                    class="mt-4 inline-flex items-center gap-2 text-sm font-medium text-indigo-600 hover:text-indigo-700 transition-colors duration-200 group">
                    Lihat detail
                    <svg class="w-4 h-4 transition-transform duration-200 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</section>

{{-- STATS --}}
<section class="border-y border-gray-100 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 divide-x divide-gray-100">
            @php
            $statsDisplay = [
                ['value' => $stats['success_rate'].'%', 'label' => 'Success Rate',     'sub' => 'Akun sukses'],
                ['value' => number_format($stats['total_accounts']), 'label' => 'Total Accounts', 'sub' => 'Akun yang pernah order'],
                ['value' => number_format($stats['success_accounts']), 'label' => 'Success Accounts', 'sub' => 'Akun yang berhasil'],
                ['value' => $stats['active_events'],     'label' => 'Active Events',   'sub' => 'Event berlangsung'],
            ];
            @endphp
            @foreach($statsDisplay as $i => $stat)
            <div class="py-8 px-6 text-center transition-all duration-300 hover:bg-gray-50 reveal-on-scroll" data-delay="{{ $i * 100 }}">
                <div class="text-3xl font-bold text-indigo-600 mb-1">{{ $stat['value'] }}</div>
                <div class="text-sm font-medium text-gray-900">{{ $stat['label'] }}</div>
                <div class="text-xs text-gray-400 mt-0.5">{{ $stat['sub'] }}</div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ACTIVE EVENTS --}}
<section class="py-14 px-4" id="active-events">
    <div class="max-w-7xl mx-auto">
        <div class="flex items-end justify-between mb-8 reveal-on-scroll">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Active Events</h2>
                <p class="text-sm text-gray-500 mt-1">Event yang sedang tersedia untuk order</p>
            </div>
            <a href="{{ route('events.index') }}" class="text-sm text-indigo-600 hover:text-indigo-700 font-medium flex items-center gap-1 group transition-colors duration-200">
                Lihat semua
                <svg class="w-4 h-4 transition-transform duration-200 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>

        @if($activeEvents->isEmpty())
            <div class="text-center py-16 bg-gray-50 rounded-2xl">
                <p class="text-gray-400">Belum ada event aktif saat ini.</p>
            </div>
        @else
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($activeEvents as $i => $event)
            <div class="reveal-on-scroll" data-delay="{{ $i * 100 }}">
                @include('public.events._card', ['event' => $event])
            </div>
            @endforeach
        </div>
        @endif
    </div>
</section>

{{-- REALTIME MONITOR --}}
<section class="bg-gray-900 py-12 px-4" id="monitor">
    <div class="max-w-7xl mx-auto">
        <div class="flex items-center gap-3 mb-6 reveal-on-scroll">
            <div class="w-2 h-2 bg-green-400 rounded-full live-indicator"></div>
            <h2 class="text-lg font-semibold text-white">Realtime Success Monitor</h2>
            <span class="text-xs text-gray-500 ml-auto">Data tersensor untuk privasi pengguna</span>
        </div>
        <div class="space-y-2">
            @forelse($recentSuccess as $i => $log)
            @php
                $email    = \App\Services\MaskService::email($log->email ?? 'us***@example.com');
                $event    = $log->event->title ?? '-';
                $phase    = $log->salePhase->name ?? '-';
                $category = $log->ticketCategory->name ?? '-';
                $qty      = $log->qty;
            @endphp
            <div class="flex items-center gap-3 bg-gray-800 rounded-xl px-4 py-2.5 text-sm overflow-x-auto hover:bg-gray-750 transition-colors duration-200 reveal-on-scroll" data-delay="{{ $i * 80 }}">
                <span class="bg-green-500/20 text-green-400 text-xs font-semibold px-2 py-0.5 rounded flex-shrink-0">SUCCESS</span>
                <span class="text-white font-medium flex-shrink-0">{{ $email }}</span>
                <span class="text-gray-600">|</span>
                <span class="text-gray-300 flex-shrink-0">{{ $event }}</span>
                <span class="text-gray-600">|</span>
                <span class="text-gray-400 flex-shrink-0">{{ $phase }}</span>
                <span class="text-gray-600">|</span>
                <span class="text-gray-400 flex-shrink-0">{{ $category }}</span>
                <span class="text-gray-600">|</span>
                <span class="text-gray-400 flex-shrink-0">x{{ $qty }}</span>
            </div>
            @empty
            <div class="text-center py-8 text-gray-500 text-sm">
                Belum ada data sukses. Monitor akan aktif saat event berlangsung.
            </div>
            @endforelse
        </div>
        <div class="text-center mt-4">
            <a href="{{ route('monitor') }}" class="text-sm text-indigo-400 hover:text-indigo-300 transition-colors duration-200 group inline-flex items-center gap-1">
                Lihat semua di Realtime Monitor
                <svg class="w-4 h-4 transition-transform duration-200 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
    </div>
</section>

{{-- CARA ORDER --}}
<section class="py-14 px-4 bg-white" id="cara-order">
    <div class="max-w-7xl mx-auto">
        <div class="flex items-center gap-3 mb-6 reveal-on-scroll">
            <div class="w-2 h-2 bg-indigo-500 rounded-full live-indicator"></div>
            <h2 class="text-lg font-semibold text-gray-900">Cara Order</h2>
            <span class="text-xs text-gray-500 ml-auto">Langkah order dari awal sampai selesai</span>
        </div>

        <div class="grid md:grid-cols-2 gap-6">
            @php
                $orderSteps = [
                    ['num' => '1', 'title' => 'Pilih event', 'desc' => 'Buka daftar event yang tersedia, lalu pilih event yang mau kamu amankan.'],
                    ['num' => '2', 'title' => 'Buka detail event', 'desc' => 'Cek sale phase, kategori tiket, fee jasa, dan informasi event.'],
                    ['num' => '3', 'title' => 'Isi form order', 'desc' => 'Lengkapi data diri, jumlah tiket, dan detail yang dibutuhkan.'],
                    ['num' => '4', 'title' => 'Tunggu proses', 'desc' => 'Setelah order masuk, pantau status di monitor dan Telegram.'],
                ];
            @endphp

            <div class="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm hover-glow transition-all duration-300 reveal-on-scroll" data-delay="100">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span class="text-sm font-semibold text-gray-900">Panduan Order</span>
                    </div>
                    <span class="flex items-center gap-1 text-xs text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-full font-medium">
                        4 Step
                    </span>
                </div>

                <div class="space-y-3">
                    @foreach($orderSteps as $step)
                    <div class="flex gap-3">
                        <div class="w-7 h-7 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center text-xs font-semibold flex-shrink-0 transition-transform duration-300 hover:scale-110">
                            {{ $step['num'] }}
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900 mb-0.5">{{ $step['title'] }}</p>
                            <p class="text-xs text-gray-500 leading-relaxed">{{ $step['desc'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="bg-gradient-to-br from-indigo-50 via-white to-purple-50 border border-gray-100 rounded-2xl p-5 shadow-sm hover-glow transition-all duration-300 reveal-on-scroll" data-delay="200">
                <div class="flex items-center gap-2 mb-4">
                    <svg class="w-4 h-4 text-indigo-600 animate-float" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    <span class="text-sm font-semibold text-gray-900">Ringkas Alur</span>
                </div>

                <div class="space-y-3">
                    @foreach($orderSteps as $step)
                    <div class="flex items-start gap-3">
                        <div class="mt-0.5 w-6 h-6 rounded-full bg-white border border-indigo-100 text-indigo-600 flex items-center justify-center text-[11px] font-semibold flex-shrink-0 transition-all duration-300 hover:bg-indigo-50 hover:scale-110">
                            {{ $step['num'] }}
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $step['title'] }}</p>
                            <p class="text-xs text-gray-500 leading-relaxed">{{ $step['desc'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

{{-- FAQ --}}
<section class="py-14 px-4 bg-white" id="faq">
    <div class="max-w-2xl mx-auto">
        <h2 class="text-2xl font-bold text-gray-900 text-center mb-2 reveal-on-scroll">FAQ</h2>
        <p class="text-gray-500 text-sm text-center mb-8 reveal-on-scroll" data-delay="100">Pertanyaan yang sering ditanyakan</p>

        @php
        $faqs = [
            ['q' => 'Apa itu Wartix?', 'a' => 'Wartix adalah platform Ticket Assistance yang membantu kamu mendapatkan tiket konser, festival, dan fanmeeting high-demand dengan layanan profesional dan update realtime via Telegram.'],
            ['q' => 'Apakah ada jaminan berhasil?', 'a' => 'Kami menampilkan success rate berdasarkan data akun yang benar-benar masuk dan berhasil. Hasil tetap bergantung pada ketersediaan tiket di platform resmi.'],
            ['q' => 'Kapan saya membayar fee jasa?', 'a' => 'Pembayaran fee jasa dilakukan setelah tiket berhasil didapatkan. QRIS akan dikirim otomatis ke Telegram kamu begitu proses berhasil.'],
            ['q' => 'Data saya aman?', 'a' => 'Ya, data kamu dienkripsi dan hanya digunakan untuk keperluan reservasi tiket. Data sensitif tidak pernah ditampilkan secara publik.'],
            ['q' => 'Bagaimana cara memantau status order?', 'a' => 'Kamu akan mendapat notifikasi langsung via Telegram. Selain itu, kamu juga bisa memantau di halaman Realtime Monitor kami.'],
        ];
        @endphp

        <div class="space-y-3" x-data="{ open: null }">
            @foreach($faqs as $i => $faq)
            <div class="border border-gray-100 rounded-xl overflow-hidden hover-glow transition-all duration-300 reveal-on-scroll" data-delay="{{ $i * 80 }}">
                <button
                    class="w-full flex items-center justify-between px-5 py-4 text-left hover:bg-gray-50 transition-all duration-200"
                    @click="open = open === {{ $i }} ? null : {{ $i }}">
                    <span class="text-sm font-medium text-gray-900">{{ $faq['q'] }}</span>
                    <svg class="w-4 h-4 text-gray-400 transition-transform duration-300 flex-shrink-0"
                        :class="open === {{ $i }} ? 'rotate-180' : ''"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="open === {{ $i }}" x-collapse class="px-5 pb-4">
                    <p class="text-sm text-gray-500 leading-relaxed">{{ $faq['a'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

@endsection
