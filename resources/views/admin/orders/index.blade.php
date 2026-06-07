@extends('layouts.admin')
@section('title', 'Orders')
@section('page-title', 'Order Management')

@section('content')
{{-- Search & Filter --}}
<form method="GET" class="flex gap-3 mb-5">
    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari order code, nama, email..."
        class="flex-1 text-sm border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
    <select name="order_status"
        class="text-sm border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
        <option value="">Semua Status</option>
        @foreach(['waiting','processing','success','failed','cancelled'] as $s)
        <option value="{{ $s }}" {{ request('order_status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
        @endforeach
    </select>
    <select name="payment_status"
        class="text-sm border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
        <option value="">Semua Payment</option>
        @foreach(['unpaid','pending','paid','expired','failed'] as $s)
        <option value="{{ $s }}" {{ request('payment_status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
        @endforeach
    </select>
    <button type="submit" class="bg-indigo-600 text-white text-sm px-4 py-2 rounded-xl hover:bg-indigo-700">Filter</button>
    <a href="{{ route('admin.orders.index') }}" class="text-sm text-gray-500 px-4 py-2 border border-gray-200 rounded-xl hover:bg-gray-50">Reset</a>
</form>

<div class="bg-white border border-gray-100 rounded-xl overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-50 border-b border-gray-100">
            <tr>
                <th class="text-left text-xs font-medium text-gray-500 px-4 py-3">Order</th>
                <th class="text-left text-xs font-medium text-gray-500 px-4 py-3">Pemesan</th>
                <th class="text-left text-xs font-medium text-gray-500 px-4 py-3">Event</th>
                <th class="text-left text-xs font-medium text-gray-500 px-4 py-3">Kategori</th>
                <th class="text-left text-xs font-medium text-gray-500 px-4 py-3">Order</th>
                <th class="text-left text-xs font-medium text-gray-500 px-4 py-3">Payment</th>
                <th class="text-right text-xs font-medium text-gray-500 px-4 py-3">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($orders as $order)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3">
                    <div class="text-xs font-mono font-medium text-gray-900">{{ $order->order_code }}</div>
                    <div class="text-xs text-gray-400">{{ $order->created_at->format('d M Y H:i') }}</div>
                </td>
                <td class="px-4 py-3">
                    <div class="text-xs font-medium text-gray-900">{{ $order->full_name }}</div>
                    <div class="text-xs text-gray-400">{{ \App\Services\MaskService::email($order->email) }}</div>
                </td>
                <td class="px-4 py-3 text-xs text-gray-600 max-w-[140px] truncate">
                    {{ $order->event->title ?? '-' }}
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
                    <span class="text-xs px-2 py-0.5 rounded font-medium {{ $oc }}">{{ ucfirst($order->order_status) }}</span>
                </td>
                <td class="px-4 py-3">
                    @php
                    $pc = match($order->payment_status) {
                        'paid'    => 'bg-green-50 text-green-700',
                        'pending' => 'bg-yellow-50 text-yellow-700',
                        'unpaid'  => 'bg-gray-100 text-gray-500',
                        default   => 'bg-red-50 text-red-700',
                    };
                    @endphp
                    <span class="text-xs px-2 py-0.5 rounded font-medium {{ $pc }}">{{ ucfirst($order->payment_status) }}</span>
                </td>
                <td class="px-4 py-3 text-right">
                    <a href="{{ route('admin.orders.show', $order) }}"
                        class="text-xs text-indigo-600 hover:text-indigo-700 font-medium">Detail</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center py-12 text-sm text-gray-400">Belum ada order.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @if($orders->hasPages())
    <div class="px-4 py-3 border-t border-gray-100">{{ $orders->links() }}</div>
    @endif
</div>
@endsection