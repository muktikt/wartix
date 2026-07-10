@extends('layouts.app')
@section('title', 'Order Berhasil Wartix')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4 py-12">
    <div class="max-w-md w-full">
        <div class="bg-white border border-gray-100 rounded-2xl p-6 text-center animate-fade-in-up" style="animation-duration: .5s;">
            <div class="w-14 h-14 bg-green-50 rounded-full flex items-center justify-center mx-auto mb-4" style="animation: successPop .5s cubic-bezier(0.16, 1, 0.3, 1) 0.1s both;">
                <svg class="w-7 h-7 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"
                        style="stroke-dasharray: 24; stroke-dashoffset: 24; animation: drawCheck 0.4s ease-out 0.45s forwards;"/>
                </svg>
            </div>
            <h1 class="text-lg font-bold text-gray-900 mb-1 animate-fade-in-up" style="animation-delay: 150ms; opacity: 0;">Order Berhasil Dikirim!</h1>
            <p class="text-sm text-gray-500 mb-5 animate-fade-in-up" style="animation-delay: 220ms; opacity: 0;">Tim Wartix akan segera memproses order kamu.</p>

            <div class="bg-gray-50 rounded-xl p-4 text-left mb-5 space-y-2 animate-fade-in-up" style="animation-delay: 300ms; opacity: 0;">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Order Code</span>
                    <span class="font-semibold text-gray-900">{{ $order->order_code }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Event</span>
                    <span class="font-medium text-gray-700">{{ $order->event->title }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Sale Phase</span>
                    <span class="font-medium text-gray-700">{{ $order->salePhase->name }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Kategori</span>
                    <span class="font-medium text-gray-700">{{ $order->ticketCategory->name }} x{{ $order->qty }}</span>
                </div>
                <div class="flex justify-between text-sm border-t border-gray-200 pt-2 mt-2">
                    <span class="text-gray-500">Fee Jasa</span>
                    <span class="font-semibold text-indigo-600">Rp {{ number_format($order->grand_total) }}</span>
                </div>
            </div>

            @if($telegramLinkUrl)
            <div id="telegramPrompt" class="bg-blue-50 border border-blue-100 rounded-xl p-4 mb-5 animate-fade-in-up" style="animation-delay: 380ms; opacity: 0;">
                <p class="text-xs text-blue-700 mb-3 text-center">
                    Mengalihkan ke Telegram untuk konfirmasi order...
                </p>
                <div class="h-1 bg-blue-100 rounded-full overflow-hidden mb-3">
                    <div class="h-full bg-[#229ED9] rounded-full" style="animation: redirectProgress 1.5s linear 0.4s both;"></div>
                </div>
                <a href="{{ $telegramLinkUrl }}"
                    class="block w-full text-center bg-[#229ED9] hover:bg-[#1e8dcc] text-white text-sm font-medium py-2.5 rounded-xl transition-all duration-200 hover:-translate-y-0.5 active:translate-y-0">
                    Buka Bot Telegram Wartix
                </a>
                <p class="text-xs text-blue-500 mt-2 text-center">
                    Belum teralihkan otomatis? Klik tombol di atas.
                </p>
            </div>
            @else
            <div class="bg-blue-50 border border-blue-100 rounded-xl p-3 mb-5 animate-fade-in-up" style="animation-delay: 380ms; opacity: 0;">
                <p class="text-xs text-blue-700 text-center">
                    Pantau status order kamu via Telegram. QRIS akan dikirim otomatis setelah tiket berhasil.
                </p>
            </div>
            @endif

            <div class="flex gap-2 animate-fade-in-up" style="animation-delay: 450ms; opacity: 0;">
                <a href="{{ route('home') }}"
                    class="flex-1 text-center text-sm border border-gray-200 text-gray-600 py-2.5 rounded-xl hover:bg-gray-50 transition-all duration-200 hover:-translate-y-0.5 active:translate-y-0">
                    Kembali
                </a>
                <a href="{{ \App\Models\Setting::get('telegram_group_link', '#') }}" target="_blank"
                    class="flex-1 text-center text-sm bg-indigo-600 hover:bg-indigo-700 text-white py-2.5 rounded-xl transition-all duration-200 hover:-translate-y-0.5 active:translate-y-0">
                    Join Telegram
                </a>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes successPop {
        0% { transform: scale(0.4); opacity: 0; }
        100% { transform: scale(1); opacity: 1; }
    }
    @keyframes drawCheck {
        to { stroke-dashoffset: 0; }
    }
    @keyframes redirectProgress {
        from { width: 0%; }
        to { width: 100%; }
    }
    @media (prefers-reduced-motion: reduce) {
        [style*="animation"] { animation: none !important; opacity: 1 !important; }
    }
</style>

@if($telegramLinkUrl)
<script>
    // Auto-redirect setelah 1.5 detik, kasih waktu customer baca ringkasan order
    setTimeout(function() {
        window.location.href = "{{ $telegramLinkUrl }}";
    }, 1500);
</script>
@endif
@endsection