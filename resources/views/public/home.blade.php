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
<section class="animated-gradient py-10 px-4">
    <div class="max-w-7xl mx-auto grid md:grid-cols-2 gap-10 items-start">

        {{-- Left --}}
        <div>
            <div class="flex flex-wrap gap-2 mb-5">
                <span class="inline-flex items-center gap-1.5 text-xs font-medium bg-indigo-50 text-indigo-700 px-3 py-1 rounded-full animate-fade-in-down">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    Priority Access
                </span>
                <span class="inline-flex items-center gap-1.5 text-xs font-medium bg-purple-50 text-purple-700 px-3 py-1 rounded-full animate-fade-in-down anim-delay-100">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Ticket Assistance
                </span>
                <span class="inline-flex items-center gap-1.5 text-xs font-medium bg-green-50 text-green-700 px-3 py-1 rounded-full animate-fade-in-down anim-delay-200">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    Realtime Monitoring
                </span>
            </div>

            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 leading-tight mb-4 animate-fade-in-down anim-delay-150">
                Priority Ticket Assistance<br>
                <span class="text-indigo-600 animate-text-shine">for High-Demand Events</span>
            </h1>

            <p class="text-gray-500 text-base leading-relaxed mb-8 max-w-md animate-fade-in anim-delay-300">
                Wartix membantu kamu mendapatkan tiket konser, festival, dan fanmeeting impian dengan layanan Ticket Assistance, Realtime Monitoring, dan notifikasi langsung via Telegram.
            </p>

            <div class="flex flex-wrap gap-3 animate-fade-in anim-delay-450">
                <a href="#active-events"
                    class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm px-5 py-2.5 rounded-xl transition-all duration-300 hover-lift hover:shadow-lg hover:shadow-indigo-500/25 btn-press">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    View Active Events
                </a>
                <a href="https://t.me/wartixdotcom" target="_blank" rel="noopener noreferrer"
                    class="inline-flex items-center gap-2 bg-white hover:bg-gray-50 text-gray-700 border border-gray-200 font-medium text-sm px-5 py-2.5 rounded-xl transition-all duration-300 hover-lift hover:border-indigo-200 btn-press">
                    <svg class="w-4 h-4 text-[#229ED9]" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <path d="M9.04 15.38 8.9 19.33c.42 0 .6-.18.83-.4l1.98-1.9 4.1 3c.75.42 1.29.2 1.48-.7l2.68-12.63c.24-1.12-.4-1.56-1.14-1.28L3.8 9.56c-1.1.43-1.08 1.05-.2 1.33l4.05 1.26 9.4-5.92c.44-.29.84-.13.51.17z"/>
                    </svg>
                    Join Telegram Channel
                </a>
            </div>
        </div>

        {{-- Right --}}
        <div class="flex justify-center md:justify-end animate-scale-in anim-delay-150">
            <div class="w-full max-w-sm flex flex-col gap-4">
                <div class="bg-white border border-gray-100 rounded-2xl p-5 w-full shadow-sm hover-glow transition-all duration-300">
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
                        class="mt-4 inline-flex items-center gap-2 text-sm font-medium text-indigo-600 hover:text-indigo-700 transition-colors duration-200 group btn-press">
                        Lihat detail
                        <svg class="w-4 h-4 transition-transform duration-200 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>

                {{-- Social Media Links --}}
                <div class="flex flex-wrap items-center justify-center md:justify-start gap-3 px-1">
                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Follow Us:</span>
                    <div class="flex items-center gap-2.5">
                        <!-- WhatsApp -->
                        <a href="{{ $whatsappLink }}" target="_blank" rel="noopener noreferrer" 
                            class="group flex items-center justify-center w-9 h-9 rounded-full bg-white border border-gray-200 text-gray-500 hover:text-white hover:bg-[#25D366] hover:border-[#25D366] transition-all duration-300 hover:scale-110 hover:-translate-y-0.5 shadow-sm hover:shadow-md hover:shadow-emerald-500/20 active:scale-95 btn-press" title="WhatsApp Group">
                            <svg class="w-4 h-4 fill-current transition-transform duration-300 group-hover:scale-110" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                        </a>
                        <!-- X (Twitter) -->
                        <a href="{{ $xLink }}" target="_blank" rel="noopener noreferrer" 
                            class="group flex items-center justify-center w-9 h-9 rounded-full bg-white border border-gray-200 text-gray-500 hover:text-white hover:bg-black hover:border-black transition-all duration-300 hover:scale-110 hover:-translate-y-0.5 shadow-sm hover:shadow-md hover:shadow-black/20 active:scale-95 btn-press" title="X (Twitter)">
                            <svg class="w-3.5 h-3.5 fill-current transition-transform duration-300 group-hover:scale-110" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                        </a>
                        <!-- TikTok -->
                        <a href="{{ $tiktokLink }}" target="_blank" rel="noopener noreferrer" 
                            class="group flex items-center justify-center w-9 h-9 rounded-full bg-white border border-gray-200 text-gray-500 hover:text-white hover:bg-zinc-900 hover:border-zinc-900 transition-all duration-300 hover:scale-110 hover:-translate-y-0.5 shadow-sm hover:shadow-md hover:shadow-black/20 active:scale-95 btn-press" title="TikTok">
                            <svg class="w-3.5 h-3.5 fill-current transition-transform duration-300 group-hover:scale-110" viewBox="0 0 24 24"><path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.98-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.07-1.3 1.8-.24.84-.06 1.77.47 2.46.58.78 1.52 1.25 2.49 1.25.75-.01 1.48-.28 2.05-.76.77-.63 1.22-1.6 1.24-2.61.02-4.52.01-9.04.01-13.56z"/></svg>
                        </a>
                        <span class="w-px h-5 bg-gray-200"></span>
                        <!-- Instagram -->
                        <a href="{{ $instagramLink }}" onclick="return false;"
                            class="group flex items-center justify-center w-9 h-9 rounded-full bg-gray-50 border border-gray-150 text-gray-400 cursor-not-allowed opacity-50 transition-all duration-300" title="Instagram (Segera)">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                        </a>
                        <!-- Threads -->
                        <a href="{{ $threadsLink }}" onclick="return false;"
                            class="group flex items-center justify-center w-9 h-9 rounded-full bg-gray-50 border border-gray-150 text-gray-400 cursor-not-allowed opacity-50 transition-all duration-300" title="Threads (Segera)">
                            <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M12.004 0C5.373 0 0 5.373 0 12s5.373 12 12 12c4.717 0 8.784-2.723 10.702-6.666-.35-.205-.724-.38-1.127-.514-1.704 3.473-5.26 5.867-9.575 5.867-5.918 0-10.686-4.768-10.686-10.687S6.086 1.313 12.004 1.313c5.312 0 9.718 3.864 10.536 8.986.173 1.085.076 2.148-.27 3.078-.518 1.394-1.637 2.378-3.07 2.701-1.258.283-2.585-.028-3.328-.781-.592-.6-.827-1.464-.664-2.435.156-.931.761-1.798 1.704-2.441.879-.6 1.983-.984 3.197-1.109-.074-.755-.308-1.392-.703-1.892-.682-.864-1.821-1.309-3.393-1.321-1.579 0-2.825.485-3.602 1.401-.735.868-1.077 2.083-1.018 3.612.059 1.528.513 2.729 1.35 3.567.82.822 1.956 1.256 3.376 1.291.688.017 1.378-.066 2.05-.246.126.335.297.653.511.947.382.525.868.948 1.445 1.259 1.066.574 2.326.689 3.548.324 1.957-.585 3.5-1.996 4.232-3.868.49-1.253.642-2.673.439-4.116-1.066-6.666-6.844-11.716-13.785-11.716zm2.49 11.233c-.785.088-1.503.351-2.078.761-.555.396-.889.877-.97 1.393-.075.48.026.877.29 1.155.305.32.791.465 1.449.432.96-.048 1.724-.378 2.274-.981.366-.402.57-.91.606-1.512-.505.025-1.029.109-1.571.752z"/></svg>
                        </a>
                    </div>
                </div>
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
                ['value' => $stats['success_rate'].'%', 'label' => 'Success Rate',     'sub' => 'Akun sukses', 'icon' => 'fa-solid fa-circle-check'],
                ['value' => number_format($stats['total_accounts']), 'label' => 'Total Accounts', 'sub' => 'Akun yang pernah order', 'icon' => 'fa-solid fa-users'],
                ['value' => number_format($stats['success_accounts']), 'label' => 'Success Accounts', 'sub' => 'Akun yang berhasil', 'icon' => 'fa-solid fa-user-check'],
                ['value' => $stats['active_events'],     'label' => 'Active Events',   'sub' => 'Event berlangsung', 'icon' => 'fa-solid fa-ticket'],
            ];
            @endphp
            @foreach($statsDisplay as $i => $stat)
            <div class="py-8 px-6 flex flex-col items-center justify-center text-center transition-all duration-300 hover:bg-gray-50 scroll-animate scroll-animate-scale" data-delay="{{ $i * 100 }}">
                <div class="text-3xl font-bold text-indigo-600 mb-0.5">{{ $stat['value'] }}</div>
                <div class="text-sm font-medium text-gray-900">{{ $stat['label'] }}</div>
                <div class="text-xs text-gray-400 mt-0.5">{{ $stat['sub'] }}</div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ACTIVE EVENTS --}}
<section class="py-14 px-4 scroll-mt-20" id="active-events">
    <div class="max-w-7xl mx-auto">
        <div class="flex items-end justify-between mb-8">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 scroll-animate scroll-animate-left">Active Events</h2>
                <p class="text-sm text-gray-500 mt-1 scroll-animate scroll-animate-left" data-delay="100">Event yang sedang tersedia untuk order</p>
            </div>
            <a href="{{ route('events.index') }}" class="text-sm text-indigo-600 hover:text-indigo-700 font-medium flex items-center gap-1 group transition-colors duration-200 btn-press scroll-animate scroll-animate-right" data-delay="200">
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
            <div class="scroll-animate {{ $i % 2 === 0 ? 'scroll-animate-left' : 'scroll-animate-right' }}" data-delay="{{ $i * 100 }}">
                @include('public.events._card', ['event' => $event])
            </div>
            @endforeach
        </div>
        @endif
    </div>
</section>

{{-- REALTIME MONITOR --}}
<section class="bg-gray-900 py-12 px-4 scroll-mt-20" id="monitor">
    <div class="max-w-7xl mx-auto">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-2 h-2 bg-green-400 rounded-full live-indicator scroll-animate scroll-animate-scale"></div>
            <h2 class="text-lg font-semibold text-white scroll-animate scroll-animate-left" data-delay="100">Realtime Success Monitor</h2>
            <span class="text-xs text-gray-500 ml-auto scroll-animate scroll-animate-right" data-delay="200">Data tersensor untuk privasi pengguna</span>
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
            <div class="flex items-center gap-3 bg-gray-800 rounded-xl px-4 py-2.5 text-sm overflow-x-auto hover:bg-gray-750 transition-colors duration-200 scroll-animate {{ $i % 2 === 0 ? 'scroll-animate-left' : 'scroll-animate-right' }}" data-delay="{{ $i * 80 }}">
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
<section class="py-14 px-4 bg-white scroll-mt-20" id="cara-order">
    <div class="max-w-7xl mx-auto">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-2 h-2 bg-indigo-500 rounded-full live-indicator scroll-animate scroll-animate-scale"></div>
            <h2 class="text-lg font-semibold text-gray-900 scroll-animate scroll-animate-left" data-delay="100">Cara Order</h2>
            <span class="text-xs text-gray-500 ml-auto scroll-animate scroll-animate-right" data-delay="200">Langkah order dari awal sampai selesai</span>
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

            <div class="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm hover-glow transition-all duration-300 scroll-animate scroll-animate-left" data-delay="100">
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

            <div class="bg-gradient-to-br from-indigo-50 via-white to-purple-50 border border-gray-100 rounded-2xl p-5 shadow-sm hover-glow transition-all duration-300 scroll-animate scroll-animate-right" data-delay="200">
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
<section class="py-14 px-4 bg-white scroll-mt-20" id="faq">
    <div class="max-w-2xl mx-auto">
        <h2 class="text-2xl font-bold text-gray-900 text-center mb-2 scroll-animate scroll-animate-scale">FAQ</h2>
        <p class="text-gray-500 text-sm text-center mb-8 scroll-animate scroll-animate-scale" data-delay="150">Pertanyaan yang sering ditanyakan</p>

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
            <div class="border border-gray-100 rounded-xl overflow-hidden hover-glow transition-all duration-300 scroll-animate scroll-animate-scale" data-delay="{{ $i * 80 }}">
                <button
                    class="w-full flex items-center justify-between px-5 py-4 text-left hover:bg-gray-50 transition-all duration-200 btn-press"
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
