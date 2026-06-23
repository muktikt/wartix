@extends('layouts.admin')
@section('title', 'Detail Order')
@section('page-title', 'Detail Order')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.orders.index') }}"
        class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Kembali ke Orders
    </a>
</div>

<div class="grid grid-cols-3 gap-5">

    {{-- LEFT --}}
    <div class="col-span-2 space-y-4">

        {{-- Order Info --}}
        <div class="bg-white border border-gray-100 rounded-xl p-5">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-gray-900">Order Information</h3>
                <div class="flex items-center gap-2">
                    @php
                    $oc = match($order->order_status) {
                        'success'    => 'bg-green-50 text-green-700',
                        'waiting'    => 'bg-yellow-50 text-yellow-700',
                        'processing' => 'bg-indigo-50 text-indigo-700',
                        'failed'     => 'bg-red-50 text-red-700',
                        'cancelled'  => 'bg-gray-100 text-gray-500',
                        default      => 'bg-gray-100 text-gray-500',
                    };
                    $pc = match($order->payment_status) {
                        'paid'    => 'bg-green-50 text-green-700',
                        'pending' => 'bg-yellow-50 text-yellow-700',
                        'unpaid'  => 'bg-gray-100 text-gray-500',
                        'expired' => 'bg-orange-50 text-orange-700',
                        'failed'  => 'bg-red-50 text-red-700',
                        default   => 'bg-gray-100 text-gray-500',
                    };
                    @endphp
                    <span class="text-xs px-2.5 py-1 rounded-lg font-medium {{ $oc }}">
                        Order: {{ ucfirst($order->order_status) }}
                    </span>
                    <span id="payment-badge-detail" class="text-xs px-2.5 py-1 rounded-lg font-medium {{ $pc }}">
                    Payment: {{ ucfirst($order->payment_status) }}
                </span>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">Order Code</p>
                    <p class="text-sm font-mono font-semibold text-gray-900">{{ $order->order_code }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">Tanggal Order</p>
                    <p class="text-sm text-gray-700">{{ $order->created_at->format('d M Y H:i:s') }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">Event</p>
                    <p class="text-sm font-medium text-gray-900">{{ $order->event->title ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">Platform</p>
                    <p class="text-sm text-gray-700">{{ strtoupper($order->event->platform_type ?? '-') }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">Sale Phase</p>
                    <p class="text-sm text-gray-700">{{ $order->salePhase->name ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">Kategori</p>
                    <p class="text-sm text-gray-700">{{ $order->ticketCategory->name ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">Qty</p>
                    <p class="text-sm font-semibold text-gray-900">{{ $order->qty }} tiket</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">Payment Mode</p>
                    <p class="text-sm text-gray-700">{{ ucfirst(str_replace('_', ' ', $order->payment_mode)) }}</p>
                </div>
            </div>
            <div class="mt-4">
                <form action="{{ route('admin.orders.destroy', $order) }}" method="POST" onsubmit="return confirm('Yakin hapus order ini?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white text-sm font-semibold px-4 py-2 rounded-xl">Hapus Order</button>
                </form>
            </div>
        </div>

        {{-- Buyer Info --}}
        <div class="bg-white border border-gray-100 rounded-xl p-5">
            <div class="flex items-center gap-2 mb-4">
                <h3 class="text-sm font-semibold text-gray-900">Buyer Information</h3>
                @if($order->guests->count())
                    <span class="text-xs px-2 py-0.5 rounded-lg font-medium bg-purple-50 text-purple-700">
                        Multi Guest ({{ $order->guests->count() }} tiket)
                    </span>
                @endif
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">Sapaan</p>
                    <p class="text-sm text-gray-700">{{ $order->title ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">Nama Lengkap</p>
                    <p class="text-sm font-medium text-gray-900">{{ $order->full_name }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">Email</p>
                    <p class="text-sm text-gray-700">{{ $order->email }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">Nomor Ponsel</p>
                    <p class="text-sm text-gray-700">{{ $order->phone_number }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">Nomor KTP / NIK</p>
                    <div class="flex items-center gap-2">
                        <p class="text-sm text-gray-700 font-mono" id="nikDisplay">
                            {{ \App\Services\MaskService::nik($order->identity_number ?? '') }}
                        </p>
                        <button type="button" onclick="toggleNik()"
                            class="text-xs text-indigo-600 hover:text-indigo-700">
                            Reveal
                        </button>
                    </div>
                    <p class="hidden text-sm font-mono text-gray-700" id="nikFull">
                        {{ $order->identity_number }}
                    </p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">Username Sosial Media</p>
                    <p class="text-sm text-gray-700">
                        {{ $order->telegram_username ? '@'.$order->telegram_username : '-' }}
                    </p>
                </div>
            </div>

            {{-- Guest NIK List (integrated) --}}
            @if($order->guests->where('guest_type', 'additional_guest')->count())
            <div class="mt-4 pt-4 border-t border-gray-100">
                <p class="text-xs text-gray-400 mb-2">NIK Tambahan (Additional Guest)</p>
                <div class="space-y-2">
                    @foreach($order->guests as $guest)
                        @if($guest->guest_type === 'additional_guest')
                        <div class="flex items-center gap-3 p-2.5 bg-gray-50 rounded-lg">
                            <div class="w-6 h-6 bg-indigo-50 rounded-full flex items-center justify-center flex-shrink-0">
                                <span class="text-[10px] font-semibold text-indigo-600">{{ $guest->ticket_position }}</span>
                            </div>
                            <div class="flex-1">
                                <p class="text-xs text-gray-500">Tiket {{ $guest->ticket_position }}</p>
                            </div>
                            <p class="text-xs font-mono text-gray-700">
                                NIK: {{ \App\Services\MaskService::nik($guest->identity_number ?? '') }}
                            </p>
                        </div>
                        @endif
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        {{-- Custom Field Answers --}}
        @if($order->customFieldAnswers->count())
        <div class="bg-white border border-gray-100 rounded-xl p-5">
            <h3 class="text-sm font-semibold text-gray-900 mb-4">Custom Field Answers</h3>
            <div class="space-y-3">
                @foreach($order->customFieldAnswers as $answer)
                <div class="flex justify-between items-center py-2 border-b border-gray-50 last:border-0">
                    <span class="text-xs text-gray-500">{{ $answer->customField->label ?? '-' }}</span>
                    @if($answer->customField->field_type === 'password')
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-mono text-gray-700">••••••••</span>
                            <button type="button"
                                onclick="this.previousElementSibling.textContent = this.previousElementSibling.textContent === '••••••••' ? '{{ $answer->value }}' : '••••••••'"
                                class="text-xs text-indigo-600">Reveal</button>
                        </div>
                    @else
                        <span class="text-xs font-medium text-gray-900">{{ $answer->value }}</span>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Success Log --}}
        @if($order->successLog)
        <div class="bg-white border border-gray-100 rounded-xl p-5">
            <h3 class="text-sm font-semibold text-gray-900 mb-4">Success Log</h3>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">Status</p>
                    <span class="text-xs bg-green-50 text-green-700 px-2 py-0.5 rounded font-medium">
                        {{ strtoupper($order->successLog->status) }}
                    </span>
                </div>
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">Waktu</p>
                    <p class="text-sm text-gray-700">{{ $order->successLog->created_at->format('d M Y H:i:s') }}</p>
                </div>
                @if($order->successLog->raw_report)
                <div class="col-span-2">
                    <p class="text-xs text-gray-400 mb-0.5">Raw Report</p>
                    <p class="text-xs font-mono bg-gray-50 rounded-lg p-3 text-gray-600 leading-relaxed">
                        {{ $order->successLog->raw_report }}
                    </p>
                </div>
                @endif
            </div>
        </div>
        @endif

    </div>

    {{-- RIGHT --}}
    <div class="space-y-4">

        {{-- Payment Info --}}
        <div class="bg-white border border-gray-100 rounded-xl p-5">
            <h3 class="text-sm font-semibold text-gray-900 mb-4">Payment Information</h3>
            <div class="space-y-2">
                @if($order->ticket_price_total > 0)
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Harga Tiket</span>
                    <span class="text-gray-700">Rp {{ number_format($order->ticket_price_total) }}</span>
                </div>
                @endif
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Service Fee</span>
                    <span class="text-gray-700">Rp {{ number_format($order->service_fee_total) }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Admin Fee</span>
                    <span class="text-gray-700">Rp {{ number_format($order->admin_fee) }}</span>
                </div>
                <div class="flex justify-between text-sm font-semibold border-t border-gray-100 pt-2 mt-2">
                    <span class="text-gray-900">Grand Total</span>
                    <span class="text-indigo-600">Rp {{ number_format($order->grand_total) }}</span>
                </div>
            </div>

            @if($order->paymentLog)
            <div class="mt-4 pt-4 border-t border-gray-100">
                <p class="text-xs text-gray-400 mb-2">Payment Log</p>
                <div class="space-y-1.5">
                    <div class="flex justify-between text-xs">
                        <span class="text-gray-500">Provider</span>
                        <span class="font-medium text-gray-700 uppercase">{{ $order->paymentLog->provider }}</span>
                    </div>
                    <div class="flex justify-between text-xs">
                        <span class="text-gray-500">Reference</span>
                        <span class="font-mono text-gray-700">{{ $order->paymentLog->payment_reference }}</span>
                    </div>
                    <div class="flex justify-between text-xs">
                        <span class="text-gray-500">Status</span>
                        <span class="font-medium
                            {{ $order->paymentLog->status === 'paid' ? 'text-green-600' : 'text-yellow-600' }}">
                            {{ ucfirst($order->paymentLog->status) }}
                        </span>
                    </div>
                    @if($order->paymentLog->paid_at)
                    <div class="flex justify-between text-xs">
                        <span class="text-gray-500">Paid At</span>
                        <span class="text-green-600">{{ $order->paymentLog->paid_at->format('d M Y H:i') }}</span>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>

        {{-- Telegram Info --}}
        <div class="bg-white border border-gray-100 rounded-xl p-5">
            <h3 class="text-sm font-semibold text-gray-900 mb-4">Telegram Information</h3>
            <div class="space-y-2">
                <div class="flex justify-between text-xs">
                    <span class="text-gray-500">Username</span>
                    <span class="text-gray-700">
                        {{ $order->telegram_username ? '@'.$order->telegram_username : '-' }}
                    </span>
                </div>
                <div class="flex justify-between text-xs">
                    <span class="text-gray-500">User ID</span>
                    <span class="font-mono text-gray-700">{{ $order->telegram_user_id ?? '-' }}</span>
                </div>
                <div class="flex justify-between text-xs">
                    <span class="text-gray-500">Chat ID</span>
                    <span class="font-mono text-gray-700">{{ $order->telegram_chat_id ?? '-' }}</span>
                </div>
            </div>
        </div>

        {{-- Update Status --}}
        <div class="bg-white border border-gray-100 rounded-xl p-5">
            <h3 class="text-sm font-semibold text-gray-900 mb-4">Update Status</h3>
            <form action="{{ route('admin.orders.status', $order) }}" method="POST">
                @csrf
                @method('PATCH')
                <select name="order_status"
                    class="w-full text-sm border border-gray-200 rounded-xl px-3 py-2.5 mb-3 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    @foreach(['waiting','processing','success','failed','cancelled'] as $s)
                    <option value="{{ $s }}" {{ $order->order_status === $s ? 'selected' : '' }}>
                        {{ ucfirst($s) }}
                    </option>
                    @endforeach
                </select>
                <button type="submit"
                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium py-2.5 rounded-xl transition-colors">
                    Update Status
                </button>
            </form>
        </div>

        {{-- Admin Notes --}}
        <div class="bg-white border border-gray-100 rounded-xl p-5">
            <h3 class="text-sm font-semibold text-gray-900 mb-3">Admin Notes</h3>
            <p class="text-sm text-gray-500 italic">{{ $order->notes ?? 'Tidak ada catatan.' }}</p>
        </div>

    </div>
</div>

<script>
function toggleNik() {
    const masked = document.getElementById('nikDisplay');
    const full   = document.getElementById('nikFull');
    if (masked.classList.contains('hidden')) {
        masked.classList.remove('hidden');
        full.classList.add('hidden');
    } else {
        masked.classList.add('hidden');
        full.classList.remove('hidden');
    }
}
</script>

<script>
window.Echo.channel('orders-admin')
    .listen('.payment.status.updated', (data) => {
        if (data.order_id !== {{ $order->id }}) return;

        const badge = document.getElementById('payment-badge-detail');
        const colorMap = {
            paid:    'bg-green-50 text-green-700',
            pending: 'bg-yellow-50 text-yellow-700',
            unpaid:  'bg-gray-100 text-gray-500',
            expired: 'bg-red-50 text-red-700',
            failed:  'bg-red-50 text-red-700',
        };

        badge.className = 'text-xs px-2.5 py-1 rounded-lg font-medium ' + (colorMap[data.payment_status] || 'bg-gray-100 text-gray-500');
        badge.textContent = 'Payment: ' + data.payment_status.charAt(0).toUpperCase() + data.payment_status.slice(1);
    });
</script>
@endsection