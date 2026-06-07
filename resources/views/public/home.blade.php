@extends('layouts.app')

@section('title', 'Wartix — Priority Ticket Assistance')

@section('content')

{{-- HERO --}}
<section class="bg-gradient-to-br from-indigo-50 via-white to-purple-50 py-16 px-4">
    <div class="max-w-7xl mx-auto grid md:grid-cols-2 gap-12 items-center">

        {{-- Left --}}
        <div>
            <div class="flex flex-wrap gap-2 mb-5">
                <span class="inline-flex items-center gap-1.5 text-xs font-medium bg-indigo-50 text-indigo-700 px-3 py-1 rounded-full">
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
                <a href="{{ route('events.index') }}"
                    class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm px-5 py-2.5 rounded-xl transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    View Active Events
                </a>
                <a href="{{ \App\Models\Setting::get('telegram_group_link', '#') }}" target="_blank"
                    class="inline-flex items-center gap-2 bg-white hover:bg-gray-50 text-gray-700 border border-gray-200 font-medium text-sm px-5 py-2.5 rounded-xl transition-colors">
                    <svg class="w-4 h-4 text-blue-500" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm5.562 8.248l-1.97 9.289c-.145.658-.537.818-1.084.508l-3-2.21-1.447 1.394c-.16.16-.295.295-.605.295l.213-3.053 5.56-5.023c.242-.213-.054-.333-.373-.12L7.412 14.02l-2.96-.924c-.643-.204-.657-.643.136-.953l11.57-4.461c.537-.194 1.006.131.834.566z"/>
                    </svg>
                    Join Telegram Group
                </a>
            </div>
        </div>

        {{-- Right — Flow Card --}}
        <div class="flex justify-center md:justify-end">
            <div class="bg-white border border-gray-100 rounded-2xl p-5 w-full max-w-sm shadow-sm">
                <div class="flex items-center justify-between mb-5">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                        </svg>
                        <span class="text-sm font-semibold text-gray-900">Alur Order Wartix</span>
                    </div>
                    <span class="flex items-center gap-1 text-xs text-green-600 bg-green-50 px-2 py-0.5 rounded-full font-medium">
                        <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span>
                        Live
                    </span>
                </div>

                @php
                $steps = [
                    ['num'=>'1','color'=>'indigo','icon'=>'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z','label'=>'Pilih event','desc'=>'Buka wartix.id, pilih event konser atau festival yang tersedia'],
                    ['num'=>'2','color'=>'indigo','icon'=>'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2','label'=>'Isi form order','desc'=>'Pilih sale phase, kategori, qty, dan lengkapi data diri kamu'],
                    ['num'=>'3','color'=>'orange','icon'=>'M13 10V3L4 14h7v7l9-11h-7z','label'=>'Tim Wartix beraksi','desc'=>'Tim kami siap siaga membantu proses reservasi tiket kamu saat sale dibuka'],
                    ['num'=>'4','color'=>'green','icon'=>'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z','label'=>'Tiket berhasil','desc'=>'Kamu langsung dapat notifikasi sukses via Telegram'],
                    ['num'=>'5','color'=>'indigo','icon'=>'M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z','label'=>'Bayar fee jasa via QRIS','desc'=>'QRIS dikirim otomatis ke Telegram kamu, bayar fee jasa dan selesai'],
                ];
                @endphp

                <div class="space-y-0">
                    @foreach($steps as $i => $step)
                    <div class="flex gap-3 {{ !$loop->last ? 'pb-4' : '' }}">
                        <div class="flex flex-col items-center">
                            <div class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-semibold flex-shrink-0
                                {{ $step['color'] === 'orange' ? 'bg-orange-50 text-orange-600' : ($step['color'] === 'green' ? 'bg-green-50 text-green-600' : 'bg-indigo-50 text-indigo-600') }}">
                                {{ $step['num'] }}
                            </div>
                            @if(!$loop->last)
                            <div class="w-px flex-1 bg-gray-100 mt-1"></div>
                            @endif
                        </div>
                        <div class="flex-1 {{ !$loop->last ? 'pb-1' : '' }}">
                            <p class="text-sm font-medium text-gray-900 mb-0.5 flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 {{ $step['color'] === 'orange' ? 'text-orange-500' : ($step['color'] === 'green' ? 'text-green-500' : 'text-indigo-500') }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $step['icon'] }}"/>
                                </svg>
                                {{ $step['label'] }}
                            </p>
                            <p class="text-xs text-gray-500 leading-relaxed">{{ $step['desc'] }}</p>
                        </div>
                    </div>
                    @endforeach
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
                ['value' => $stats['success_rate'].'%', 'label' => 'Success Rate',     'sub' => 'Tingkat keberhasilan'],
                ['value' => number_format($stats['total_checkout']), 'label' => 'Total Checkout', 'sub' => 'Total order berhasil'],
                ['value' => $stats['total_events'],      'label' => 'Events Handled',  'sub' => 'Total event dibantu'],
                ['value' => $stats['active_events'],     'label' => 'Active Events',   'sub' => 'Event berlangsung'],
            ];
            @endphp
            @foreach($statsDisplay as $stat)
            <div class="py-8 px-6 text-center">
                <div class="text-3xl font-bold text-indigo-600 mb-1">{{ $stat['value'] }}</div>
                <div class="text-sm font-medium text-gray-900">{{ $stat['label'] }}</div>
                <div class="text-xs text-gray-400 mt-0.5">{{ $stat['sub'] }}</div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ACTIVE EVENTS --}}
<section class="py-14 px-4">
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
            @include('public.events._card', ['event' => $event])
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
            <div class="flex items-center gap-3 bg-gray-800 rounded-xl px-4 py-2.5 text-sm overflow-x-auto">
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
                Lihat semua di Realtime Monitor →
            </a>
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
            ['q' => 'Apakah ada jaminan berhasil?', 'a' => 'Kami berusaha sebaik mungkin untuk membantu proses reservasi tiket kamu. Success rate kami di atas 98%, namun hasil tetap bergantung pada ketersediaan tiket di platform resmi.'],
            ['q' => 'Kapan saya membayar fee jasa?', 'a' => 'Pembayaran fee jasa dilakukan setelah tiket berhasil didapatkan. QRIS akan dikirim otomatis ke Telegram kamu begitu proses berhasil.'],
            ['q' => 'Data saya aman?', 'a' => 'Ya, data kamu dienkripsi dan hanya digunakan untuk keperluan reservasi tiket. Data sensitif tidak pernah ditampilkan secara publik.'],
            ['q' => 'Bagaimana cara memantau status order?', 'a' => 'Kamu akan mendapat notifikasi langsung via Telegram. Selain itu, kamu juga bisa memantau di halaman Realtime Monitor kami.'],
        ];
        @endphp

        <div class="space-y-3" x-data="{ open: null }">
            @foreach($faqs as $i => $faq)
            <div class="border border-gray-100 rounded-xl overflow-hidden">
                <button
                    class="w-full flex items-center justify-between px-5 py-4 text-left hover:bg-gray-50 transition-colors"
                    @click="open = open === {{ $i }} ? null : {{ $i }}">
                    <span class="text-sm font-medium text-gray-900">{{ $faq['q'] }}</span>
                    <svg class="w-4 h-4 text-gray-400 transition-transform flex-shrink-0"
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