@extends('layouts.admin')
@section('title', 'Reports')
@section('page-title', 'Advanced Reports')

@section('content')
{{-- Stats Cards --}}
<div class="grid grid-cols-4 gap-4 mb-5">
    @php
    $reportCards = [
        ['label' => 'Total Orders',   'value' => number_format($stats['total_orders']),   'color' => 'indigo'],
        ['label' => 'Success Orders', 'value' => number_format($stats['success_orders']), 'color' => 'green'],
        ['label' => 'Failed Orders',  'value' => number_format($stats['failed_orders']),  'color' => 'red'],
        ['label' => 'Total Revenue',  'value' => 'Rp '.number_format($stats['total_revenue']), 'color' => 'purple'],
    ];
    @endphp
    @foreach($reportCards as $card)
    <div class="bg-white border border-gray-100 rounded-xl p-4">
        <p class="text-xs text-gray-500 mb-1">{{ $card['label'] }}</p>
        <p class="text-xl font-semibold text-gray-900">{{ $card['value'] }}</p>
    </div>
    @endforeach
</div>

{{-- Filter --}}
<form method="GET" class="bg-white border border-gray-100 rounded-xl p-4 mb-5">
    <div class="grid grid-cols-4 gap-3 mb-3">
        <div>
            <label class="block text-xs text-gray-500 mb-1">Event</label>
            <select name="event_id"
                class="w-full text-sm border border-gray-200 rounded-lg px-2.5 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <option value="">Semua Event</option>
                @foreach($events as $event)
                <option value="{{ $event->id }}" {{ request('event_id') == $event->id ? 'selected' : '' }}>
                    {{ $event->title }}
                </option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs text-gray-500 mb-1">Order Status</label>
            <select name="order_status"
                class="w-full text-sm border border-gray-200 rounded-lg px-2.5 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <option value="">Semua</option>
                @foreach(['waiting','processing','success','failed','cancelled'] as $s)
                <option value="{{ $s }}" {{ request('order_status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs text-gray-500 mb-1">Dari Tanggal</label>
            <input type="date" name="date_from" value="{{ request('date_from') }}"
                class="w-full text-sm border border-gray-200 rounded-lg px-2.5 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
        </div>
        <div>
            <label class="block text-xs text-gray-500 mb-1">Sampai Tanggal</label>
            <input type="date" name="date_to" value="{{ request('date_to') }}"
                class="w-full text-sm border border-gray-200 rounded-lg px-2.5 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
        </div>
    </div>
    <div class="flex gap-2">
        <button type="submit"
            class="bg-indigo-600 text-white text-sm px-4 py-2 rounded-lg hover:bg-indigo-700">
            Filter
        </button>
        <a href="{{ route('admin.reports.index') }}"
            class="text-sm text-gray-500 px-4 py-2 border border-gray-200 rounded-lg hover:bg-gray-50">
            Reset
        </a>
    </div>
</form>

{{-- Table --}}
<div class="bg-white border border-gray-100 rounded-xl overflow-x-auto">
    <table class="w-full min-w-[800px]">
        <thead class="bg-gray-50 border-b border-gray-100">
            <tr>
                <th class="text-left text-xs font-medium text-gray-500 px-4 py-3">Order</th>
                <th class="text-left text-xs font-medium text-gray-500 px-4 py-3">Pemesan</th>
                <th class="text-left text-xs font-medium text-gray-500 px-4 py-3">Event</th>
                <th class="text-left text-xs font-medium text-gray-500 px-4 py-3">Kategori</th>
                <th class="text-left text-xs font-medium text-gray-500 px-4 py-3">Total</th>
                <th class="text-left text-xs font-medium text-gray-500 px-4 py-3">Status</th>
                <th class="text-left text-xs font-medium text-gray-500 px-4 py-3">Tanggal</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($orders as $order)
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-4 py-3 text-xs font-mono font-medium text-indigo-600">
                    <a href="{{ route('admin.orders.show', $order) }}">{{ $order->order_code }}</a>
                </td>
                <td class="px-4 py-3">
                    <p class="text-xs font-medium text-gray-900">{{ $order->full_name }}</p>
                    <p class="text-xs text-gray-400">{{ \App\Services\MaskService::email($order->email) }}</p>
                </td>
                <td class="px-4 py-3 text-xs text-gray-600 max-w-[120px] truncate">
                    {{ $order->event->title ?? '-' }}
                </td>
                <td class="px-4 py-3 text-xs text-gray-600">
                    {{ $order->ticketCategory->name ?? '-' }} x{{ $order->qty }}
                </td>
                <td class="px-4 py-3 text-xs font-semibold text-gray-900">
                    Rp {{ number_format($order->grand_total) }}
                </td>
                <td class="px-4 py-3">
                    @php
                    $sc = match($order->order_status) {
                        'success'    => 'bg-green-50 text-green-700',
                        'waiting'    => 'bg-yellow-50 text-yellow-700',
                        'processing' => 'bg-indigo-50 text-indigo-700',
                        default      => 'bg-red-50 text-red-700',
                    };
                    @endphp
                    <span class="text-xs px-2 py-0.5 rounded font-medium {{ $sc }}">
                        {{ ucfirst($order->order_status) }}
                    </span>
                </td>
                <td class="px-4 py-3 text-xs text-gray-400">
                    {{ $order->created_at->format('d M Y H:i') }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center py-12 text-sm text-gray-400">
                    Tidak ada data.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @if($orders->hasPages())
    <div class="px-4 py-3 border-t border-gray-100">{{ $orders->links() }}</div>
    @endif
</div>
@endsection
