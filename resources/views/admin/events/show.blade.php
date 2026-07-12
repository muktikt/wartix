@extends('layouts.admin')
@section('title', $event->title)
@section('page-title', 'Detail Event')

@section('content')
<div class="mb-4 flex items-center justify-between">
    <a href="{{ route('admin.events.index') }}"
        class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Kembali
    </a>
    <div class="flex items-center gap-2">
        <a href="{{ route('admin.events.builder.edit', $event) }}"
            class="inline-flex items-center gap-1.5 text-sm border border-gray-200 text-gray-600 px-3 py-1.5 rounded-lg hover:bg-gray-50">
            Edit Event
        </a>
        <form action="{{ route('admin.events.destroy', $event) }}" method="POST" class="inline"
            onsubmit="return confirm('Yakin hapus event ini? Semua phase, kategori, dan order terkait akan ikut terhapus.');">
            @csrf
            @method('DELETE')
            <button type="submit"
                class="inline-flex items-center gap-1.5 text-sm border border-red-200 text-red-600 px-3 py-1.5 rounded-lg hover:bg-red-50">
                Hapus Event
            </button>
        </form>
        <form action="{{ route('admin.events.status', $event) }}" method="POST" class="inline">
            @csrf @method('PATCH')
            <select name="status" onchange="this.form.submit()"
                class="text-sm border border-gray-200 rounded-lg px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                @foreach(['upcoming','ongoing','finished'] as $s)
                <option value="{{ $s }}" {{ $event->status === $s ? 'selected' : '' }}>
                    {{ ucfirst($s) }}
                </option>
                @endforeach
            </select>
        </form>
    </div>
</div>

{{-- Event Header --}}
<div class="bg-white border border-gray-100 rounded-xl p-5 mb-5">
    <div class="flex gap-5">
        @if($event->banner_image)
        <img src="{{ asset('storage/'.$event->banner_image) }}"
            class="w-32 h-20 object-cover rounded-xl flex-shrink-0" alt="{{ $event->title }}">
        @else
        <div class="w-32 h-20 bg-indigo-100 rounded-xl flex items-center justify-center flex-shrink-0">
            <svg class="w-8 h-8 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
            </svg>
        </div>
        @endif
        <div class="flex-1">
            <div class="flex items-start justify-between">
                <div>
                    <h2 class="text-lg font-bold text-gray-900">{{ $event->title }}</h2>
                    <p class="text-sm text-gray-500">{{ $event->artist_name }}</p>
                </div>
                @php
                $sc = match($event->status) {
                    'ongoing'  => 'bg-green-50 text-green-700',
                    'upcoming' => 'bg-indigo-50 text-indigo-700',
                    'finished' => 'bg-gray-100 text-gray-500',
                };
                @endphp
                <span class="text-xs px-2.5 py-1 rounded-lg font-medium {{ $sc }}">
                    {{ ucfirst($event->status) }}
                </span>
            </div>
            <div class="grid grid-cols-4 gap-4 mt-3">
                <div>
                    <p class="text-xs text-gray-400">Venue</p>
                    <p class="text-xs font-medium text-gray-700">{{ $event->venue }}, {{ $event->city }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400">Tanggal</p>
                    <p class="text-xs font-medium text-gray-700">{{ $event->event_date->format('d M Y') }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400">Platform</p>
                    <p class="text-xs font-medium text-gray-700">{{ strtoupper($event->platform_type) }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400">Max Tiket/Order</p>
                    <p class="text-xs font-medium text-gray-700">{{ $event->max_ticket_per_order }}</p>
                </div>
            </div>
                <div class="grid grid-cols-4 gap-4 mt-3">
                <div>
                    <p class="text-xs text-gray-400">Total Slot</p>
                    <p class="text-xs font-medium text-gray-700">{{ $event->resolved_total_slots ?? '∞' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Stats --}}
<div class="grid grid-cols-4 gap-4 mb-5">
    @php
    $cards = [
        ['label' => 'Total Orders',   'value' => $stats['total_orders']],
        ['label' => 'Success Orders', 'value' => $stats['success_orders']],
        ['label' => 'Pending Orders', 'value' => $stats['pending_orders']],
        ['label' => 'Total Revenue',  'value' => 'Rp '.number_format($stats['total_revenue'])],
    ];
    @endphp
    @foreach($cards as $card)
    <div class="bg-white border border-gray-100 rounded-xl p-4">
        <p class="text-xs text-gray-400 mb-1">{{ $card['label'] }}</p>
        <p class="text-xl font-semibold text-gray-900">{{ $card['value'] }}</p>
    </div>
    @endforeach
</div>

{{-- Tabs --}}
<div x-data="{ tab: 'phases' }">
    <div class="flex gap-1 border-b border-gray-100 mb-5">
        @foreach(['phases' => 'Sale Phases', 'categories' => 'Categories & Fee', 'orders' => 'Orders', 'guests' => 'Guest Data', 'logs' => 'Success Logs'] as $key => $label)
        <button @click="tab = '{{ $key }}'"
            class="px-4 py-2 text-sm font-medium transition-colors border-b-2 -mb-px"
            :class="tab === '{{ $key }}' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700'">
            {{ $label }}
        </button>
        @endforeach
    </div>

    {{-- Tab: Sale Phases --}}
    <div x-show="tab === 'phases'">
        <div class="bg-white border border-gray-100 rounded-xl overflow-x-auto">
            <table class="w-full min-w-[600px]">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="text-left text-xs font-medium text-gray-500 px-4 py-3">Phase</th>
                        <th class="text-left text-xs font-medium text-gray-500 px-4 py-3">Start</th>
                        <th class="text-left text-xs font-medium text-gray-500 px-4 py-3">End</th>
                        <th class="text-left text-xs font-medium text-gray-500 px-4 py-3">Slot</th>
                        <th class="text-left text-xs font-medium text-gray-500 px-4 py-3">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($event->salePhases as $phase)
                    <tr>
                        <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $phase->name }}</td>
                        <td class="px-4 py-3 text-xs text-gray-500">
                            {{ $phase->start_time?->format('d M Y H:i') ?? '-' }}
                        </td>
                        <td class="px-4 py-3 text-xs text-gray-500">
                            {{ $phase->end_time?->format('d M Y H:i') ?? '-' }}
                        </td>
                        <td class="px-4 py-3 text-xs text-gray-500">{{ $phase->slot_limit ?? '∞' }}</td>
                        <td class="px-4 py-3">
                            <span class="text-xs px-2 py-0.5 rounded font-medium
                                {{ $phase->status === 'open' ? 'bg-green-50 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                {{ ucfirst($phase->status) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center py-8 text-sm text-gray-400">Belum ada phase.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Tab: Categories --}}
    <div x-show="tab === 'categories'">
        <div class="bg-white border border-gray-100 rounded-xl overflow-x-auto">
            <table class="w-full min-w-[700px]">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="text-left text-xs font-medium text-gray-500 px-4 py-3">Kategori</th>
                        <th class="text-left text-xs font-medium text-gray-500 px-4 py-3">Fee/Tiket</th>
                        <th class="text-left text-xs font-medium text-gray-500 px-4 py-3">Harga Tiket</th>
                        <th class="text-left text-xs font-medium text-gray-500 px-4 py-3">Payment Mode</th>
                        <th class="text-left text-xs font-medium text-gray-500 px-4 py-3">Max Qty</th>
                        <th class="text-left text-xs font-medium text-gray-500 px-4 py-3">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($event->ticketCategories as $cat)
                    <tr>
                        <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $cat->name }}</td>
                        <td class="px-4 py-3 text-sm font-semibold text-indigo-600">
                            Rp {{ number_format($cat->fee_per_ticket) }}
                        </td>
                        <td class="px-4 py-3 text-xs text-gray-500">
                            {{ $cat->ticket_price > 0 ? 'Rp '.number_format($cat->ticket_price) : '-' }}
                        </td>
                        <td class="px-4 py-3 text-xs text-gray-500">
                            {{ ucfirst(str_replace('_', ' ', $cat->payment_mode)) }}
                        </td>
                        <td class="px-4 py-3 text-xs text-gray-500">{{ $cat->max_qty }}</td>
                        <td class="px-4 py-3">
                            <span class="text-xs px-2 py-0.5 rounded font-medium
                                {{ $cat->is_active ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700' }}">
                                {{ $cat->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center py-8 text-sm text-gray-400">Belum ada kategori.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Tab: Orders --}}
    <div x-show="tab === 'orders'">
        <div class="bg-white border border-gray-100 rounded-xl overflow-x-auto">
            <table class="w-full min-w-[800px]">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="text-left text-xs font-medium text-gray-500 px-4 py-3">Order Code</th>
                        <th class="text-left text-xs font-medium text-gray-500 px-4 py-3">Pemesan</th>
                        <th class="text-left text-xs font-medium text-gray-500 px-4 py-3">Kategori</th>
                        <th class="text-left text-xs font-medium text-gray-500 px-4 py-3">Status</th>
                        <th class="text-left text-xs font-medium text-gray-500 px-4 py-3">Payment</th>
                        <th class="text-right text-xs font-medium text-gray-500 px-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($event->orders as $order)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-xs font-mono text-indigo-600">{{ $order->order_code }}</td>
                        <td class="px-4 py-3">
                            <p class="text-xs font-medium text-gray-900">{{ $order->full_name }}</p>
                            <p class="text-xs text-gray-400">
                                {{ \App\Services\MaskService::email($order->email) }}
                            </p>
                        </td>
                        <td class="px-4 py-3 text-xs text-gray-600">
                            {{ $order->ticketCategory->name ?? '-' }} x{{ $order->qty }}
                        </td>
                        <td class="px-4 py-3">
                            @php
                            $oc = match($order->order_status) {
                                'success'    => 'bg-green-50 text-green-700',
                                'waiting'    => 'bg-yellow-50 text-yellow-700',
                                'processing' => 'bg-indigo-50 text-indigo-700',
                                default      => 'bg-red-50 text-red-700',
                            };
                            @endphp
                            <span class="text-xs px-2 py-0.5 rounded {{ $oc }}">
                                {{ ucfirst($order->order_status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            @php
                            $pc = match($order->payment_status) {
                                'paid'    => 'bg-green-50 text-green-700',
                                'pending' => 'bg-yellow-50 text-yellow-700',
                                default   => 'bg-gray-100 text-gray-500',
                            };
                            @endphp
                            <span class="text-xs px-2 py-0.5 rounded {{ $pc }}">
                                {{ ucfirst($order->payment_status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.orders.show', $order) }}"
                                class="text-xs text-indigo-600 hover:text-indigo-700">Detail</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-8 text-sm text-gray-400">
                            Belum ada order.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Tab: Guest Data --}}
    <div x-show="tab === 'guests'">
        <div class="bg-white border border-gray-100 rounded-xl overflow-x-auto">
            <table class="w-full min-w-[650px]">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="text-left text-xs font-medium text-gray-500 px-4 py-3">Order Code</th>
                        <th class="text-left text-xs font-medium text-gray-500 px-4 py-3">Buyer</th>
                        <th class="text-left text-xs font-medium text-gray-500 px-4 py-3">Ticket</th>
                        <th class="text-left text-xs font-medium text-gray-500 px-4 py-3">NIK</th>
                        <th class="text-left text-xs font-medium text-gray-500 px-4 py-3">Type</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @php
                    $allGuests = $event->orders->flatMap(fn($o) => $o->guests->map(fn($g) => ['order' => $o, 'guest' => $g]));
                    @endphp
                    @forelse($allGuests as $item)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-xs font-mono text-indigo-600">
                            {{ $item['order']->order_code }}
                        </td>
                        <td class="px-4 py-3 text-xs text-gray-700">
                            {{ \App\Services\MaskService::email($item['order']->email) }}
                        </td>
                        <td class="px-4 py-3 text-xs text-gray-600">
                            Tiket {{ $item['guest']->ticket_position }}
                        </td>
                        <td class="px-4 py-3 text-xs font-mono text-gray-600">
                            {{ \App\Services\MaskService::nik($item['guest']->identity_number ?? '') }}
                        </td>
                        <td class="px-4 py-3">
                            <span class="text-xs px-2 py-0.5 rounded
                                {{ $item['guest']->guest_type === 'main_buyer' ? 'bg-indigo-50 text-indigo-600' : 'bg-gray-100 text-gray-500' }}">
                                {{ $item['guest']->guest_type === 'main_buyer' ? 'Main' : 'Guest' }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-8 text-sm text-gray-400">
                            Belum ada data guest.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Tab: Success Logs --}}
    <div x-show="tab === 'logs'">
        <div class="bg-white border border-gray-100 rounded-xl overflow-x-auto">
            <table class="w-full min-w-[750px]">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="text-left text-xs font-medium text-gray-500 px-4 py-3">Email</th>
                        <th class="text-left text-xs font-medium text-gray-500 px-4 py-3">Phase</th>
                        <th class="text-left text-xs font-medium text-gray-500 px-4 py-3">Kategori</th>
                        <th class="text-left text-xs font-medium text-gray-500 px-4 py-3">Qty</th>
                        <th class="text-left text-xs font-medium text-gray-500 px-4 py-3">Status</th>
                        <th class="text-left text-xs font-medium text-gray-500 px-4 py-3">Waktu</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($event->successLogs as $log)
                    <tr>
                        <td class="px-4 py-3 text-xs text-gray-600">
                            {{ \App\Services\MaskService::email($log->email ?? '') }}
                        </td>
                        <td class="px-4 py-3 text-xs text-gray-600">
                            {{ $log->salePhase->name ?? '-' }}
                        </td>
                        <td class="px-4 py-3 text-xs text-gray-600">
                            {{ $log->ticketCategory->name ?? '-' }}
                        </td>
                        <td class="px-4 py-3 text-xs text-gray-600">x{{ $log->qty }}</td>
                        <td class="px-4 py-3">
                            <span class="text-xs bg-green-50 text-green-700 px-2 py-0.5 rounded font-medium">
                                {{ strtoupper($log->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-xs text-gray-400">
                            {{ $log->created_at->format('d M Y H:i') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-8 text-sm text-gray-400">
                            Belum ada success log.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
