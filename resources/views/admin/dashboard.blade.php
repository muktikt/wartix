@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="grid grid-cols-4 gap-4 mb-6">

    {{-- Stat Cards --}}
    @php
    $cards = [
        ['label' => 'Total Orders',   'value' => $stats['total_orders'],   'color' => 'indigo', 'icon' => 'M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z'],
        ['label' => 'Success Orders', 'value' => $stats['success_orders'], 'color' => 'green',  'icon' => 'M5 13l4 4L19 7'],
        ['label' => 'Pending Orders', 'value' => $stats['pending_orders'], 'color' => 'yellow', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
        ['label' => 'Active Events',  'value' => $stats['active_events'],  'color' => 'purple', 'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
    ];
    @endphp

    @foreach($cards as $i => $card)
    <div class="stat-card bg-white border border-gray-100 rounded-xl p-4 animate-fade-in-up anim-delay-{{ ($i + 1) * 100 }}">
        <div class="flex items-center justify-between mb-2">
            <p class="text-xs text-gray-500">{{ $card['label'] }}</p>
            <div class="w-7 h-7 rounded-lg bg-{{ $card['color'] }}-50 flex items-center justify-center">
                <svg class="w-3.5 h-3.5 text-{{ $card['color'] }}-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $card['icon'] }}"/>
                </svg>
            </div>
        </div>
        <p class="text-2xl font-semibold text-gray-900 animate-count-up">{{ number_format($card['value']) }}</p>
    </div>
    @endforeach
</div>

{{-- Revenue + Success Rate --}}
<div class="grid grid-cols-2 gap-4 mb-6">
    <div class="stat-card bg-white border border-gray-100 rounded-xl p-4 animate-fade-in-up anim-delay-500">
        <div class="flex items-center justify-between mb-2">
            <p class="text-xs text-gray-500">Total Revenue</p>
            <div class="w-7 h-7 rounded-lg bg-emerald-50 flex items-center justify-center">
                <svg class="w-3.5 h-3.5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
        <p class="text-2xl font-semibold text-gray-900 animate-count-up">
            Rp {{ number_format($stats['total_revenue']) }}
        </p>
    </div>
    <div class="stat-card bg-white border border-gray-100 rounded-xl p-4 animate-fade-in-up anim-delay-600">
        <div class="flex items-center justify-between mb-2">
            <p class="text-xs text-gray-500">Success Rate</p>
            <div class="w-7 h-7 rounded-lg bg-blue-50 flex items-center justify-center">
                <svg class="w-3.5 h-3.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
            </div>
        </div>
        <p class="text-2xl font-semibold text-gray-900 animate-count-up">
            {{ $stats['success_rate'] }}%
        </p>
    </div>
</div>

{{-- Recent Orders --}}
<div class="bg-white border border-gray-100 rounded-xl p-4 mb-4 animate-fade-in-up anim-delay-700">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-sm font-semibold text-gray-900">Recent Orders</h2>
        <a href="#" class="text-xs text-indigo-600 hover:underline transition-colors duration-200">Lihat semua</a>
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
            @foreach($recentOrders as $i => $order)
            <tr class="table-row-hover hover:bg-gray-50 transition-colors animate-slide-row" style="animation-delay: {{ $i * 60 }}ms">
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
<div class="bg-white border border-gray-100 rounded-xl p-4 animate-fade-in-up anim-delay-800">
    <h2 class="text-sm font-semibold text-gray-900 mb-3">Quick Actions</h2>
    <div class="grid grid-cols-3 gap-3">
        <a href="{{ route('admin.events.builder.create') }}" class="flex items-center gap-2 p-3 border border-gray-100 rounded-xl hover:bg-indigo-50 hover:border-indigo-200 transition-all duration-300 group hover-lift">
            <svg class="w-4 h-4 text-indigo-600 transition-transform duration-300 group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            <span class="text-xs font-medium text-gray-700 group-hover:text-indigo-700 transition-colors duration-200">Add Event</span>
        </a>
        <a href="{{ route('admin.reports.index') }}" class="flex items-center gap-2 p-3 border border-gray-100 rounded-xl hover:bg-indigo-50 hover:border-indigo-200 transition-all duration-300 group hover-lift">
            <svg class="w-4 h-4 text-indigo-600 transition-transform duration-300 group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
            <span class="text-xs font-medium text-gray-700 group-hover:text-indigo-700 transition-colors duration-200">Reports</span>
        </a>
        <a href="{{ route('admin.integrations.index') }}" class="flex items-center gap-2 p-3 border border-gray-100 rounded-xl hover:bg-indigo-50 hover:border-indigo-200 transition-all duration-300 group hover-lift">
            <svg class="w-4 h-4 text-indigo-600 transition-transform duration-300 group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
            </svg>
            <span class="text-xs font-medium text-gray-700 group-hover:text-indigo-700 transition-colors duration-200">Integration</span>
        </a>
    </div>
</div>

@if($stats['pending_link_count'] > 0)
<div class="bg-yellow-50 border border-yellow-100 rounded-xl p-3 mb-6 flex items-center gap-2 animate-fade-in-up anim-delay-300 mt-4">
    <svg class="w-4 h-4 text-yellow-600 flex-shrink-0 animate-pulse-soft" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    <span class="text-xs text-yellow-700">
        <strong>{{ $stats['pending_link_count'] }}</strong> order sedang menunggu konfirmasi Telegram (auto-cancel dalam 10 menit jika tidak diklik).
    </span>
</div>
@endif
@endsection
