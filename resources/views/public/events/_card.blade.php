<div class="bg-white border border-gray-100 rounded-2xl overflow-hidden hover:shadow-md transition-shadow">
    {{-- Banner --}}
    <div class="relative h-40 bg-gradient-to-br from-indigo-900 to-purple-900 overflow-hidden">
        @if($event->banner_image)
            <img src="{{ asset('storage/'.$event->banner_image) }}"
                alt="{{ $event->title }}"
                class="w-full h-full object-cover">
        @else
            <div class="absolute inset-0 flex items-center justify-center">
                <span class="text-white/60 text-sm font-medium text-center px-4">{{ $event->title }}</span>
            </div>
        @endif
        <div class="absolute top-3 left-3">
            @if($event->status === 'ongoing')
                <span class="bg-green-500 text-white text-xs font-semibold px-2.5 py-1 rounded-full">On Sale</span>
            @else
                <span class="bg-indigo-500 text-white text-xs font-semibold px-2.5 py-1 rounded-full">Upcoming</span>
            @endif
        </div>
    </div>

    {{-- Body --}}
    <div class="p-4">
        <h3 class="font-semibold text-gray-900 text-sm mb-0.5 truncate">{{ $event->title }}</h3>
        <p class="text-xs text-gray-400 mb-3">{{ $event->artist_name }}</p>

        <div class="space-y-1.5 mb-3">
            <div class="flex items-center gap-1.5 text-xs text-gray-500">
                <svg class="w-3.5 h-3.5 text-indigo-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                </svg>
                {{ $event->venue }}, {{ $event->city }}
            </div>
            <div class="flex items-center gap-1.5 text-xs text-gray-500">
                <svg class="w-3.5 h-3.5 text-indigo-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                {{ $event->event_date->format('d M Y') }}
            </div>
        </div>

        {{-- Phases --}}
        @if($event->salePhases->count())
        <div class="flex flex-wrap gap-1 mb-3">
            @foreach($event->salePhases->take(3) as $phase)
            <span class="text-xs bg-indigo-50 text-indigo-600 px-2 py-0.5 rounded-md">{{ $phase->name }}</span>
            @endforeach
            @if($event->salePhases->count() > 3)
            <span class="text-xs bg-gray-50 text-gray-500 px-2 py-0.5 rounded-md">+{{ $event->salePhases->count() - 3 }}</span>
            @endif
        </div>
        @endif

        {{-- Fee --}}
        @if($event->ticketCategories->count())
        <p class="text-xs text-gray-400 mb-3">
            Fee mulai <span class="font-semibold text-gray-700">Rp {{ number_format($event->ticketCategories->min('fee_per_ticket')) }}/tiket</span>
        </p>
        @endif

        @if(isset($event->total_accounts))
        <div class="flex items-center justify-between gap-2 text-xs mb-3">
            <span class="text-gray-500">
                {{ number_format($event->success_accounts ?? 0) }} sukses / {{ number_format($event->total_accounts ?? 0) }} akun
            </span>
            <span class="font-semibold {{ ($event->success_rate ?? 0) >= 80 ? 'text-green-600' : 'text-indigo-600' }}">
                {{ $event->success_rate ?? 0 }}%
            </span>
        </div>
        @endif

        <div class="flex items-center justify-between gap-2 text-xs mb-3 p-2 bg-indigo-50 rounded-lg">
            <span class="text-indigo-700">Slot Tersedia</span>
            @if($event->total_slots !== null)
            <span class="font-bold {{ ($event->available_slots ?? 0) > 0 ? 'text-green-600' : 'text-red-600' }}">
                {{ $event->available_slots ?? 0 }}/{{ $event->total_slots }}
            </span>
            @else
            <span class="font-bold text-indigo-600">∞ (tak terbatas)</span>
            @endif
        </div>
        @if(request('debug'))
        <div class="mt-2 text-xs text-gray-500 bg-gray-50 p-2 rounded">
            <pre class="whitespace-pre-wrap text-xs">{{ json_encode($event->toArray(), JSON_PRETTY_PRINT) }}</pre>
        </div>
        @endif

        <a href="{{ route('events.show', $event->slug) }}"
            class="block w-full text-center bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-medium py-2 rounded-lg transition-colors">
            View Detail
        </a>
    </div>
</div>
