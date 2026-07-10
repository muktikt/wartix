@extends('layouts.app')

@section('title', 'Wartix Priority Ticket Assistance')

@section('content')

{{-- HERO --}}
<section class="bg-gradient-to-br from-indigo-50 via-white to-purple-50 py-16 px-4">
    <div class="max-w-7xl mx-auto grid md:grid-cols-2 gap-12 items-center">

        {{-- Left --}}
        <div>
            <div class="flex flex-wrap gap-2 mb-5">
                <span class="inline-flex items-center gap-1.5 text-xs font-medium bg-indigo-50 text-indigo-700 px-3 py-1 rounded-full animate-fade-in-up" style="animation-delay: 0ms; opacity: 0;">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    Priority Access
                </span>
                <span class="inline-flex items-center gap-1.5 text-xs font-medium bg-purple-50 text-purple-700 px-3 py-1 rounded-full animate-fade-in-up" style="animation-delay: 80ms; opacity: 0;">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Ticket Assistance
                </span>
                <span class="inline-flex items-center gap-1.5 text-xs font-medium bg-green-50 text-green-700 px-3 py-1 rounded-full animate-fade-in-up" style="animation-delay: 160ms; opacity: 0;">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    Realtime Monitoring
                </span>
            </div>

            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 leading-tight mb-4 animate-fade-in-up" style="animation-delay: 220ms; opacity: 0;">
                Priority Ticket Assistance<br>
                <span class="text-indigo-600">for High-Demand Events</span>
            </h1>

            <p class="text-gray-500 text-base leading-relaxed mb-8 max-w-md animate-fade-in-up" style="animation-delay: 300ms; opacity: 0;">
                Wartix membantu kamu mendapatkan tiket konser, festival, dan fanmeeting impian dengan layanan Ticket Assistance, Realtime Monitoring, dan notifikasi langsung via Telegram.
            </p>

            <div class="flex flex-wrap gap-3 animate-fade-in-up" style="animation-delay: 380ms; opacity: 0;">
                <a href="#active-events"
                    class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm px-5 py-2.5 rounded-xl transition-colors hover-lift">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    View Active Events
                </a>
                <a href="https://t.me/wartixdotcom" target="_blank" rel="noopener noreferrer"
                    class="inline-flex items-center gap-2 bg-white hover:bg-gray-50 text-gray-700 border border-gray-200 font-medium text-sm px-5 py-2.5 rounded-xl transition-colors hover-lift">
                    <svg class="w-4 h-4 text-[#229ED9]" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <path d="M9.04 15.38 8.9 19.33c.42 0 .6-.18.83-.4l1.98-1.9 4.1 3c.75.42 1.29.2 1.48-.7l2.68-12.63c.24-1.12-.4-1.56-1.14-1.28L3.8 9.56c-1.1.43-1.08 1.05-.2 1.33l4.05 1.26 9.4-5.92c.44-.29.84-.13.51.17z"/>
                    </svg>
                    Join Telegram Channel
                </a>
            </div>
        </div>

        {{-- Right --}}
        <div class="flex justify-center md:justify-end">
            <div class="bg-white border border-gray-100 rounded-2xl p-5 w-full max-w-sm shadow-sm hover-lift animate-slide-in-right" style="animation-delay: 200ms; opacity: 0;">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                        </svg>
                        <span class="text-sm font-semibold text-gray-900">Preview Realtime Monitor</span>
                    </div>
                    <span class="flex items-center gap-1 text-xs text-green-600 bg-green-50 px-2 py-0.5 rounded-full font-medium">
                        <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span>
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
                        <div class="rounded-xl border border-gray-100 bg-gray-50 px-3 py-2">
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
                    class="mt-4 inline-flex items-center gap-2 text-sm font-medium text-indigo-600 hover:text-indigo-700">
                    Lihat detail
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                ['value' => $stats['success_rate'], 'suffix' => '%', 'label' => 'Success Rate',     'sub' => 'Akun sukses'],
                ['value' => $stats['total_accounts'], 'suffix' => '', 'label' => 'Total Accounts', 'sub' => 'Akun yang pernah order'],
                ['value' => $stats['success_accounts'], 'suffix' => '', 'label' => 'Success Accounts', 'sub' => 'Akun yang berhasil'],
                ['value' => $stats['active_events'], 'suffix' => '',   'label' => 'Active Events',   'sub' => 'Event berlangsung'],
            ];
            @endphp
            @foreach($statsDisplay as $stat)
            <div class="py-8 px-6 text-center">
                <div class="text-3xl font-bold text-indigo-600 mb-1"
                    x-data="counter({{ $stat['value'] }})"
                    x-intersect.once="start()">
                    <span x-text="display.toLocaleString('id-ID')">{{ number_format($stat['value']) }}</span>{{ $stat['suffix'] }}
                </div>
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
        <div class="flex items-end justify-between mb-8">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Active Events</h2>
                <p class="text-sm text-gray-500 mt-1">Event yang sedang tersedia untuk order</p>
            </div>
            <a href="{{ route('events.index') }}" class="text-sm text-indigo-600 hover:text-indigo-700 font-medium flex items-center gap-1">
                Lihat semua
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
            @foreach($activeEvents as $event)
            <div class="reveal" x-data x-intersect.once="$el.classList.add('reveal-visible')" style="transition-delay: {{ min($loop->index, 5) * 70 }}ms">
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
        <div class="flex items-center gap-3 mb-6">
            <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
            <h2 class="text-lg font-semibold text-white">Realtime Success Monitor</h2>
            <span class="text-xs text-gray-500 ml-auto">Data tersensor untuk privasi pengguna</span>
        </div>
        <div class="space-y-2">
            @forelse($recentSuccess as $log)
            @php
                $email    = \App\Services\MaskService::email($log->email ?? 'us***@example.com');
                $event    = $log->event->title ?? '-';
                $phase    = $log->salePhase->name ?? '-';
                $category = $log->ticketCategory->name ?? '-';
                $qty      = $log->qty;
            @endphp
            <div class="flex items-center gap-3 bg-gray-800 rounded-xl px-4 py-2.5 text-sm overflow-x-auto reveal hover:bg-gray-700/60 transition-colors"
                x-data x-intersect.once="$el.classList.add('reveal-visible')"
                style="transition-delay: {{ min($loop->index, 6) * 50 }}ms">
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
            <a href="{{ route('monitor') }}" class="text-sm text-indigo-400 hover:text-indigo-300">
                Lihat semua di Realtime Monitor ->
            </a>
        </div>
    </div>
</section>

{{-- CARA ORDER --}}
<section class="py-14 px-4 bg-white" id="cara-order">
    <div class="max-w-7xl mx-auto">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-2 h-2 bg-indigo-500 rounded-full animate-pulse"></div>
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

            <div class="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm reveal hover-lift" x-data x-intersect.once="$el.classList.add('reveal-visible')">
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
                        <div class="w-7 h-7 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center text-xs font-semibold flex-shrink-0">
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

            <div class="bg-gradient-to-br from-indigo-50 via-white to-purple-50 border border-gray-100 rounded-2xl p-5 shadow-sm reveal hover-lift" x-data x-intersect.once="$el.classList.add('reveal-visible')" style="transition-delay: 100ms">
                <div class="flex items-center gap-2 mb-4">
                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    <span class="text-sm font-semibold text-gray-900">Ringkas Alur</span>
                </div>

                <div class="space-y-3">
                    @foreach($orderSteps as $step)
                    <div class="flex items-start gap-3">
                        <div class="mt-0.5 w-6 h-6 rounded-full bg-white border border-indigo-100 text-indigo-600 flex items-center justify-center text-[11px] font-semibold flex-shrink-0">
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
        <h2 class="text-2xl font-bold text-gray-900 text-center mb-2">FAQ</h2>
        <p class="text-gray-500 text-sm text-center mb-8">Pertanyaan yang sering ditanyakan</p>

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
            <div class="border border-gray-100 rounded-xl overflow-hidden reveal transition-shadow hover:shadow-sm"
                x-intersect.once="$el.classList.add('reveal-visible')"
                style="transition-delay: {{ $i * 60 }}ms">
                <button
                    class="w-full flex items-center justify-between px-5 py-4 text-left hover:bg-gray-50 transition-colors"
                    @click="open = open === {{ $i }} ? null : {{ $i }}">
                    <span class="text-sm font-medium text-gray-900">{{ $faq['q'] }}</span>
                    <svg class="w-4 h-4 text-gray-400 transition-transform duration-300 flex-shrink-0"
                        :class="open === {{ $i }} ? 'rotate-180 text-indigo-500' : ''"
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





