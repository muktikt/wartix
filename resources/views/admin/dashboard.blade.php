@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="grid grid-cols-4 gap-4 mb-6">

    {{-- Stat Cards --}}
    @php
    $cards = [
        ['label' => 'Total Orders',   'value' => $stats['total_orders'],   'color' => 'indigo'],
        ['label' => 'Success Orders', 'value' => $stats['success_orders'], 'color' => 'green'],
        ['label' => 'Pending Orders', 'value' => $stats['pending_orders'], 'color' => 'yellow'],
        ['label' => 'Active Events',  'value' => $stats['active_events'],  'color' => 'purple'],
    ];
    @endphp

    @foreach($cards as $card)
    <div class="bg-white border border-gray-100 rounded-xl p-4 reveal hover-lift"
        x-data="counter({{ $card['value'] }})" x-intersect.once="$el.classList.add('reveal-visible'); start()"
        style="transition-delay: {{ $loop->index * 60 }}ms">
        <p class="text-xs text-gray-500 mb-1">{{ $card['label'] }}</p>
        <p class="text-2xl font-semibold text-gray-900" x-text="display.toLocaleString('id-ID')"></p>
    </div>
    @endforeach
</div>

{{-- Revenue + Success Rate --}}
<div class="grid grid-cols-2 gap-4 mb-6">
    <div class="bg-white border border-gray-100 rounded-xl p-4 reveal hover-lift"
        x-data="counter({{ $stats['total_revenue'] }})" x-intersect.once="$el.classList.add('reveal-visible'); start()">
        <p class="text-xs text-gray-500 mb-1">Total Revenue</p>
        <p class="text-2xl font-semibold text-gray-900">
            Rp <span x-text="display.toLocaleString('id-ID')"></span>
        </p>
    </div>
    <div class="bg-white border border-gray-100 rounded-xl p-4 reveal hover-lift"
        x-data="counter({{ $stats['success_rate'] }})" x-intersect.once="$el.classList.add('reveal-visible'); start()"
        style="transition-delay: 60ms">
        <p class="text-xs text-gray-500 mb-1">Success Rate</p>
        <p class="text-2xl font-semibold text-gray-900">
            <span x-text="display.toLocaleString('id-ID')"></span>%
        </p>
    </div>
</div>

{{-- Recent Orders --}}
<div class="bg-white border border-gray-100 rounded-xl p-4 mb-4 reveal" x-data x-intersect.once="$el.classList.add('reveal-visible')">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-sm font-semibold text-gray-900">Recent Orders</h2>
        <a href="#" class="text-xs text-indigo-600 hover:underline">Lihat semua</a>
    </div>

    @if($recentOrders->isEmpty())
        <p class="text-sm text-gray-400 text-center py-6">Belum ada order.</p>
    @else
    <table class="w-full">
        <thead>
            <tr class="border-b border-gray-50">
                <th class="text-left text-xs text-gray-400 font-medium pb-2">Order</th>
                <th class="text-left text-xs text-gray-400 font-medium pb-2">Event</th>
                <th class="text-left text-xs text-gray-400 font-medium pb-2">Kategori</th>
                <th class="text-left text-xs text-gray-400 font-medium pb-2">Status</th>
                <th class="text-left text-xs text-gray-400 font-medium pb-2">Tanggal</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @foreach($recentOrders as $order)
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="py-2.5 text-xs font-medium text-gray-900">{{ $order->order_code }}</td>
                <td class="py-2.5 text-xs text-gray-600">{{ $order->event->title ?? '-' }}</td>
                <td class="py-2.5 text-xs text-gray-600">
                    {{ $order->ticketCategory->name ?? '-' }} x{{ $order->qty }}
                </td>
                <td class="py-2.5">
                    @php
                    $statusColor = match($order->order_status) {
                        'success'    => 'bg-green-50 text-green-700',
                        'waiting'    => 'bg-yellow-50 text-yellow-700',
                        'processing' => 'bg-indigo-50 text-indigo-700',
                        'failed'     => 'bg-red-50 text-red-700',
                        'cancelled'  => 'bg-gray-50 text-gray-700',
                        default      => 'bg-gray-50 text-gray-700',
                    };
                    @endphp
                    <span class="text-xs px-2 py-0.5 rounded-md font-medium {{ $statusColor }}">
                        {{ ucfirst($order->order_status) }}
                    </span>
                </td>
                <td class="py-2.5 text-xs text-gray-400">
                    {{ $order->created_at->format('d M Y H:i') }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
</div>

{{-- Quick Actions --}}
<div class="bg-white border border-gray-100 rounded-xl p-4 reveal" x-data x-intersect.once="$el.classList.add('reveal-visible')">
    <h2 class="text-sm font-semibold text-gray-900 mb-3">Quick Actions</h2>
    <div class="grid grid-cols-3 gap-3">
        <a href="{{ route('admin.events.builder.create') }}" class="flex items-center gap-2 p-3 border border-gray-100 rounded-xl hover:bg-gray-50 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-sm">
            <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            <span class="text-xs font-medium text-gray-700">Add Event</span>
        </a>
        <a href="{{ route('admin.reports.index') }}" class="flex items-center gap-2 p-3 border border-gray-100 rounded-xl hover:bg-gray-50 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-sm">
            <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
            <span class="text-xs font-medium text-gray-700">Reports</span>
        </a>
        <a href="{{ route('admin.integrations.index') }}" class="flex items-center gap-2 p-3 border border-gray-100 rounded-xl hover:bg-gray-50 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-sm">
            <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
            </svg>
            <span class="text-xs font-medium text-gray-700">Integration</span>
        </a>
    </div>
</div>

@if($stats['pending_link_count'] > 0)
<div class="bg-yellow-50 border border-yellow-100 rounded-xl p-3 mb-6 flex items-center gap-2">
    <svg class="w-4 h-4 text-yellow-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    <span class="text-xs text-yellow-700">
        <strong>{{ $stats['pending_link_count'] }}</strong> order sedang menunggu konfirmasi Telegram (auto-cancel dalam 10 menit jika tidak diklik).
    </span>
</div>
@endif
@endsection
