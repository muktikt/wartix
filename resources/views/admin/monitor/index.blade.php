@extends('layouts.admin')
@section('title', 'Realtime Monitor')
@section('page-title', 'Realtime Monitor')

@section('content')
<div class="flex items-center gap-3 mb-5">
    <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
    <span class="text-sm font-medium text-gray-700">Live Monitor</span>
    <span class="text-xs text-gray-400 ml-auto">Data lengkap — admin only</span>
    <a href="{{ route('admin.export.orders') }}"
        class="inline-flex items-center gap-1.5 text-xs border border-gray-200 text-gray-600 px-3 py-1.5 rounded-lg hover:bg-gray-50">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        Export
    </a>
</div>

{{-- Filter --}}
<form method="GET" class="flex gap-3 mb-5">
    <select name="event_id"
        class="text-sm border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
        <option value="">Semua Event</option>
        @foreach($events as $event)
        <option value="{{ $event->id }}" {{ request('event_id') == $event->id ? 'selected' : '' }}>
            {{ $event->title }}
        </option>
        @endforeach
    </select>
    <select name="status"
        class="text-sm border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
        <option value="">Semua Status</option>
        <option value="success" {{ request('status') === 'success' ? 'selected' : '' }}>Success</option>
        <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed</option>
    </select>
    <button type="submit"
        class="bg-indigo-600 text-white text-sm px-4 py-2 rounded-xl hover:bg-indigo-700">
        Filter
    </button>
    <a href="{{ route('admin.monitor.index') }}"
        class="text-sm text-gray-500 px-4 py-2 border border-gray-200 rounded-xl hover:bg-gray-50">
        Reset
    </a>
</form>

{{-- Live Feed --}}
<div class="bg-white border border-gray-100 rounded-xl overflow-hidden mb-5">
    <div class="px-4 py-3 bg-gray-50 border-b border-gray-100 flex items-center gap-2">
        <span class="text-xs font-medium text-gray-700">Live Feed</span>
        <span class="text-xs text-gray-400">(update otomatis)</span>
    </div>
    <div id="liveFeed" class="divide-y divide-gray-50 max-h-48 overflow-y-auto">
        <div class="px-4 py-3 text-xs text-gray-400 text-center">
            Menunggu data realtime...
        </div>
    </div>
</div>

{{-- History Table --}}
<div class="bg-white border border-gray-100 rounded-xl overflow-hidden">
    <div class="px-4 py-3 bg-gray-50 border-b border-gray-100">
        <span class="text-xs font-medium text-gray-700">History Log</span>
    </div>
    <table class="w-full">
        <thead>
            <tr class="border-b border-gray-50">
                <th class="text-left text-xs font-medium text-gray-400 px-4 py-3">Order</th>
                <th class="text-left text-xs font-medium text-gray-400 px-4 py-3">Email</th>
                <th class="text-left text-xs font-medium text-gray-400 px-4 py-3">Event</th>
                <th class="text-left text-xs font-medium text-gray-400 px-4 py-3">Phase</th>
                <th class="text-left text-xs font-medium text-gray-400 px-4 py-3">Kategori</th>
                <th class="text-left text-xs font-medium text-gray-400 px-4 py-3">Qty</th>
                <th class="text-left text-xs font-medium text-gray-400 px-4 py-3">Status</th>
                <th class="text-left text-xs font-medium text-gray-400 px-4 py-3">Waktu</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50" id="monitorTable">
            @forelse($logs as $log)
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-4 py-3 text-xs font-mono text-gray-700">
                    {{ $log->order->order_code ?? '-' }}
                </td>
                <td class="px-4 py-3 text-xs text-gray-600">{{ $log->email }}</td>
                <td class="px-4 py-3 text-xs text-gray-600 max-w-[140px] truncate">
                    {{ $log->event->title ?? '-' }}
                </td>
                <td class="px-4 py-3 text-xs text-gray-600">{{ $log->salePhase->name ?? '-' }}</td>
                <td class="px-4 py-3 text-xs text-gray-600">{{ $log->ticketCategory->name ?? '-' }}</td>
                <td class="px-4 py-3 text-xs text-gray-600">x{{ $log->qty }}</td>
                <td class="px-4 py-3">
                    <span class="text-xs bg-green-50 text-green-700 px-2 py-0.5 rounded font-medium">
                        {{ strtoupper($log->status) }}
                    </span>
                </td>
                <td class="px-4 py-3 text-xs text-gray-400">
                    {{ $log->created_at->format('d M H:i:s') }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center py-12 text-sm text-gray-400">
                    Belum ada log sukses.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @if($logs->hasPages())
    <div class="px-4 py-3 border-t border-gray-100">{{ $logs->links() }}</div>
    @endif
</div>

<script>
window.Echo.channel('success-monitor-admin')
    .listen('.success.log.created', (data) => {
        const feed     = document.getElementById('liveFeed');
        const table    = document.getElementById('monitorTable');
        const d        = data.adminData;

        // Clear placeholder
        if (feed.querySelector('.text-center')) {
            feed.innerHTML = '';
        }

        // Add to live feed
        const feedRow = document.createElement('div');
        feedRow.className = 'flex items-center gap-3 px-4 py-2.5 bg-green-50 border-l-2 border-green-500 animate-pulse';
        feedRow.innerHTML = `
            <span class="text-xs bg-green-500 text-white px-2 py-0.5 rounded font-semibold">SUCCESS</span>
            <span class="text-xs font-medium text-gray-900">${d.order_code}</span>
            <span class="text-xs text-gray-600">${d.email}</span>
            <span class="text-xs text-gray-400 ml-auto">${d.event} | ${d.phase} | ${d.category} x${d.qty}</span>
        `;
        feed.prepend(feedRow);
        setTimeout(() => feedRow.classList.remove('animate-pulse', 'bg-green-50', 'border-green-500'), 3000);

        // Add to table
        const tableRow = document.createElement('tr');
        tableRow.className = 'hover:bg-gray-50 bg-green-50 transition-colors';
        tableRow.innerHTML = `
            <td class="px-4 py-3 text-xs font-mono text-gray-700">${d.order_code}</td>
            <td class="px-4 py-3 text-xs text-gray-600">${d.email}</td>
            <td class="px-4 py-3 text-xs text-gray-600">${d.event}</td>
            <td class="px-4 py-3 text-xs text-gray-600">${d.phase}</td>
            <td class="px-4 py-3 text-xs text-gray-600">${d.category}</td>
            <td class="px-4 py-3 text-xs text-gray-600">x${d.qty}</td>
            <td class="px-4 py-3">
                <span class="text-xs bg-green-50 text-green-700 px-2 py-0.5 rounded font-medium">SUCCESS</span>
            </td>
            <td class="px-4 py-3 text-xs text-gray-400">just now</td>
        `;
        table.prepend(tableRow);
        setTimeout(() => tableRow.classList.remove('bg-green-50'), 3000);
    });
</script>
@endsection