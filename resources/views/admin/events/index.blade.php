@extends('layouts.admin')
@section('title', 'Events')
@section('page-title', 'Events')

@section('content')
<div class="flex items-center justify-between mb-5">
    <div>
        <p class="text-xs text-gray-400">Total {{ $events->total() }} event</p>
    </div>
    <a href="{{ route('admin.events.builder.create') }}"
        class="inline-flex items-center gap-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-4 py-2 rounded-xl transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Add Event
    </a>
</div>

<div class="bg-white border border-gray-100 rounded-xl overflow-x-auto">
    <table class="w-full min-w-[600px]">
        <thead class="bg-gray-50 border-b border-gray-100">
            <tr>
                <th class="text-left text-xs font-medium text-gray-500 px-4 py-3">Event</th>
                <th class="text-left text-xs font-medium text-gray-500 px-4 py-3">Platform</th>
                <th class="text-left text-xs font-medium text-gray-500 px-4 py-3">Tanggal</th>
                <th class="text-left text-xs font-medium text-gray-500 px-4 py-3">Status</th>
                <th class="text-left text-xs font-medium text-gray-500 px-4 py-3">Orders</th>
                <th class="text-right text-xs font-medium text-gray-500 px-4 py-3">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($events as $event)
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-4 py-3">
                    <div class="text-sm font-medium text-gray-900">{{ $event->title }}</div>
                    <div class="text-xs text-gray-400">{{ $event->artist_name }}</div>
                </td>
                <td class="px-4 py-3">
                    <span class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded">
                        {{ strtoupper($event->platform_type) }}
                    </span>
                </td>
                <td class="px-4 py-3 text-xs text-gray-500">
                    {{ $event->event_date->format('d M Y') }}
                </td>
                <td class="px-4 py-3">
                    @php
                    $sc = match($event->status) {
                        'ongoing'  => 'bg-green-50 text-green-700',
                        'upcoming' => 'bg-indigo-50 text-indigo-700',
                        'finished' => 'bg-gray-100 text-gray-500',
                    };
                    @endphp
                    <span class="text-xs px-2 py-0.5 rounded-md font-medium {{ $sc }}">
                        {{ ucfirst($event->status) }}
                    </span>
                </td>
                <td class="px-4 py-3 text-sm text-gray-600">
                    {{ $event->orders_count }}
                </td>
                <td class="px-4 py-3 text-right">
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('admin.events.show', $event) }}"
                            class="text-xs text-indigo-600 hover:text-indigo-700 font-medium">Detail</a>
                        <a href="{{ route('admin.events.builder.edit', $event) }}"
                            class="text-xs text-gray-500 hover:text-gray-700">Edit</a>
                        <form action="{{ route('admin.events.destroy', $event) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus event ini? Semua phase, kategori, dan order terkait akan ikut terhapus.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-xs text-red-600 hover:text-red-700 font-medium">Hapus</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center py-12 text-gray-400 text-sm">
                    Belum ada event.
                    <a href="{{ route('admin.events.builder.create') }}" class="text-indigo-600">Buat event pertama</a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($events->hasPages())
    <div class="px-4 py-3 border-t border-gray-100">
        {{ $events->links() }}
    </div>
    @endif
</div>
@endsection
