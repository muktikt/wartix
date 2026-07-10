@extends('layouts.app')
@section('title', 'Events Wartix')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900 mb-2">Active Events</h1>
        <p class="text-sm text-gray-500">Temukan event konser, festival, dan fanmeeting yang tersedia</p>
    </div>

    {{-- Search --}}
    <form method="GET" class="mb-6">
        <div class="flex gap-3 mb-3">
            <div class="flex-1 relative">
                <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" name="q" value="{{ request('q') }}"
                    placeholder="Cari event, artis, kota, venue, sale phase, atau kategori tiket..."
                    class="w-full pl-10 pr-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white">
            </div>
            <button type="submit"
                class="bg-indigo-600 text-white text-sm px-5 py-2.5 rounded-xl hover:bg-indigo-700 transition-all duration-200 hover:-translate-y-0.5 active:translate-y-0">
                Cari
            </button>
            @if(request()->hasAny(['q','city','type','platform','status','month']))
            <a href="{{ route('events.index') }}"
                class="text-sm text-gray-500 px-4 py-2.5 border border-gray-200 rounded-xl hover:bg-gray-50 transition-colors">
                Reset
            </a>
            @endif
        </div>

        {{-- Filters --}}
        <div class="flex flex-wrap gap-2">
            <select name="city"
                class="text-xs border border-gray-200 rounded-lg px-2.5 py-1.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white">
                <option value="">Semua Kota</option>
                @foreach($cities as $city)
                <option value="{{ $city }}" {{ request('city') === $city ? 'selected' : '' }}>{{ $city }}</option>
                @endforeach
            </select>
            <select name="type"
                class="text-xs border border-gray-200 rounded-lg px-2.5 py-1.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white">
                <option value="">Semua Jenis</option>
                @foreach($types as $type)
                <option value="{{ $type }}" {{ request('type') === $type ? 'selected' : '' }}>{{ $type }}</option>
                @endforeach
            </select>
            <select name="platform"
                class="text-xs border border-gray-200 rounded-lg px-2.5 py-1.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white">
                <option value="">Semua Platform</option>
                @foreach(['tiketcom','loket','yesplis','custom'] as $p)
                <option value="{{ $p }}" {{ request('platform') === $p ? 'selected' : '' }}>
                    {{ $p === 'tiketcom' ? 'Tiket.com' : ucfirst($p) }}
                </option>
                @endforeach
            </select>
            <select name="status"
                class="text-xs border border-gray-200 rounded-lg px-2.5 py-1.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white">
                <option value="">Semua Status</option>
                <option value="upcoming" {{ request('status') === 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                <option value="ongoing" {{ request('status') === 'ongoing' ? 'selected' : '' }}>Ongoing</option>
            </select>
            <button type="submit"
                class="text-xs bg-gray-100 text-gray-600 px-3 py-1.5 rounded-lg hover:bg-gray-200">
                Apply Filter
            </button>
        </div>
    </form>

    {{-- Results --}}
    @if($events->isEmpty())
    <div class="text-center py-16 bg-white border border-gray-100 rounded-2xl">
        <svg class="w-12 h-12 text-gray-200 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
        </svg>
        <p class="text-gray-400 text-sm">Tidak ada event yang ditemukan.</p>
        @if(request('q'))
        <p class="text-gray-400 text-xs mt-1">Coba kata kunci lain atau hapus filter.</p>
        @endif
    </div>
    @else
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-5">
        @foreach($events as $event)
            <div class="reveal" x-data x-intersect.once="$el.classList.add('reveal-visible')" style="transition-delay: {{ min($loop->index, 8) * 60 }}ms">
                @include('public.events._card', ['event' => $event])
            </div>
        @endforeach
    </div>

    <div class="mt-6">{{ $events->links() }}</div>
    @endif
</div>
@endsection