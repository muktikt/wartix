@extends('layouts.admin')
@section('title', 'Global Search')
@section('page-title', 'Global Search')

@section('content')
<form method="GET" class="mb-6">
    <div class="flex gap-3">
        <div class="flex-1 relative">
            <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400"
                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input type="text" name="q" value="{{ $q }}"
                placeholder="Cari order code, nama, email, nomor HP, username sosial media..."
                class="w-full pl-10 pr-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500"
                autofocus>
        </div>
        <button type="submit"
            class="bg-indigo-600 text-white text-sm px-5 py-2.5 rounded-xl hover:bg-indigo-700 transition-all duration-200 hover:-translate-y-0.5 active:translate-y-0">
            Cari
        </button>
    </div>
</form>

@if($q && strlen($q) >= 2)

{{-- Tabs --}}
<div class="flex gap-1 mb-4 border-b border-gray-100 animate-fade-in-up">
    @php
    $tabs = [
        'all'     => 'Semua',
        'orders'  => 'Orders ('.count($results['orders'] ?? []).')',
        'events'  => 'Events ('.count($results['events'] ?? []).')',
        'payments'=> 'Payments ('.count($results['payments'] ?? []).')',
        'logs'    => 'Success Logs ('.count($results['logs'] ?? []).')',
    ];
    @endphp
    @foreach($tabs as $key => $label)
    <a href="{{ request()->fullUrlWithQuery(['tab' => $key]) }}"
        class="px-4 py-2 text-sm font-medium transition-colors border-b-2 -mb-px
        {{ $tab === $key ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
        {{ $label }}
    </a>
    @endforeach
</div>

{{-- Orders Results --}}
@if(in_array($tab, ['all', 'orders']) && !empty($results['orders']))
<div class="bg-white border border-gray-100 rounded-xl overflow-hidden mb-4 animate-fade-in-up">
    <div class="px-4 py-3 bg-gray-50 border-b border-gray-100">
        <span class="text-xs font-semibold text-gray-700">Orders</span>
    </div>
    <table class="w-full">
        <thead>
            <tr class="border-b border-gray-50">
                <th class="text-left text-xs font-medium text-gray-400 px-4 py-2">Order Code</th>
                <th class="text-left text-xs font-medium text-gray-400 px-4 py-2">Pemesan</th>
                <th class="text-left text-xs font-medium text-gray-400 px-4 py-2">Event</th>
                <th class="text-left text-xs font-medium text-gray-400 px-4 py-2">Status</th>
                <th class="text-right text-xs font-medium text-gray-400 px-4 py-2">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @foreach($results['orders'] as $order)
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-4 py-2.5 text-xs font-mono font-medium text-indigo-600">
                    {{ $order->order_code }}
                </td>
                <td class="px-4 py-2.5">
                    <p class="text-xs font-medium text-gray-900">{{ $order->full_name }}</p>
                    <p class="text-xs text-gray-400">
                        {{ \App\Services\MaskService::email($order->email) }}
                    </p>
                </td>
                <td class="px-4 py-2.5 text-xs text-gray-600">
                    {{ $order->event->title ?? '-' }}
                </td>
                <td class="px-4 py-2.5">
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
                <td class="px-4 py-2.5 text-right">
                    <a href="{{ route('admin.orders.show', $order) }}"
                        class="text-xs text-indigo-600 hover:text-indigo-700 font-medium">
                        Detail
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

{{-- Events Results --}}
@if(in_array($tab, ['all', 'events']) && !empty($results['events']))
<div class="bg-white border border-gray-100 rounded-xl overflow-hidden mb-4 animate-fade-in-up" style="animation-delay: 60ms; opacity: 0;">
    <div class="px-4 py-3 bg-gray-50 border-b border-gray-100">
        <span class="text-xs font-semibold text-gray-700">Events</span>
    </div>
    <div class="divide-y divide-gray-50">
        @foreach($results['events'] as $event)
        <div class="flex items-center justify-between px-4 py-3 hover:bg-gray-50 transition-colors">
            <div>
                <p class="text-sm font-medium text-gray-900">{{ $event->title }}</p>
                <p class="text-xs text-gray-400">{{ $event->artist_name }} — {{ $event->city }}</p>
            </div>
            <a href="{{ route('admin.events.show', $event) }}"
                class="text-xs text-indigo-600 hover:text-indigo-700 font-medium">
                Detail
            </a>
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- No Results --}}
@if(empty($results['orders']) && empty($results['events']) && empty($results['payments']) && empty($results['logs']))
<div class="text-center py-16 bg-white border border-gray-100 rounded-xl animate-fade-in-up">
    <svg class="w-10 h-10 text-gray-200 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="animation: successPop 0.5s cubic-bezier(0.16, 1, 0.3, 1);">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
    </svg>
    <p class="text-sm text-gray-400">Tidak ada hasil untuk "<strong>{{ $q }}</strong>"</p>
</div>
@endif

@elseif($q)
<div class="text-center py-8 text-sm text-gray-400">
    Masukkan minimal 2 karakter untuk mencari.
</div>
@endif
@endsection