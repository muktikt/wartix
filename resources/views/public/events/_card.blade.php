<div class="relative group rounded-[32px] overflow-hidden bg-gray-900 border-4 border-white dark:border-gray-800 shadow-xl hover:shadow-2xl hover:-translate-y-1.5 transition-all duration-300 flex flex-col justify-end min-h-[380px]">
    {{-- Banner Image & Gradient Overlay --}}
    <div class="absolute inset-0 z-0">
        @if($event->banner_image)
            <img src="{{ asset('storage/'.$event->banner_image) }}"
                alt="{{ $event->title }}"
                class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
        @else
            <div class="w-full h-full bg-gradient-to-br from-indigo-950 via-purple-950 to-slate-900 flex items-center justify-center p-6 text-center">
                <span class="text-white/50 text-base font-semibold">{{ $event->title }}</span>
            </div>
        @endif
        {{-- Smooth dark gradient transition --}}
        <div class="absolute inset-0 bg-gradient-to-t from-black via-black/85 via-50% to-transparent"></div>
    </div>

    {{-- Top Badges --}}
    <div class="absolute top-4 left-4 z-10">
        @if($event->status === 'ongoing')
            <span class="bg-green-500/90 backdrop-blur-md text-white text-xs font-semibold px-3 py-1 rounded-full animate-pulse-soft">Proses</span>
        @elseif($event->status === 'slot_penuh')
            <span class="bg-rose-500/90 backdrop-blur-md text-white text-xs font-semibold px-3 py-1 rounded-full">Slot Penuh</span>
        @elseif($event->status === 'finished')
            <span class="bg-gray-600/90 backdrop-blur-md text-white text-xs font-semibold px-3 py-1 rounded-full">Finished</span>
        @else
            <span class="bg-indigo-600/90 backdrop-blur-md text-white text-xs font-semibold px-3 py-1 rounded-full">Aktif</span>
        @endif
    </div>

    {{-- Card Content overlay at bottom --}}
    <div class="relative z-10 p-5 flex flex-col gap-2.5">
        {{-- Title & Fee --}}
        <div class="flex items-start justify-between gap-3">
            <div class="min-w-0 flex-1">
                <h3 class="font-bold text-white text-lg leading-snug truncate drop-shadow-sm">{{ $event->title }}</h3>
                @if($event->artist_name)
                    <p class="text-xs text-white/70 font-medium truncate mt-0.5">{{ $event->artist_name }}</p>
                @endif
            </div>
            @if($event->ticketCategories->count())
                <span class="bg-white/20 backdrop-blur-md text-white text-xs font-semibold px-3 py-1 rounded-full shrink-0 border border-white/10">
                    Fee Rp {{ number_format($event->ticketCategories->min('fee_per_ticket')) }}
                </span>
            @endif
        </div>

        {{-- Location & Date --}}
        <div class="flex items-center gap-3 text-xs text-white/75">
            <span class="flex items-center gap-1 truncate">
                <svg class="w-3.5 h-3.5 text-white/60 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                </svg>
                {{ $event->venue }}, {{ $event->city }}
            </span>
            <span class="text-white/40">•</span>
            <span class="flex items-center gap-1 shrink-0">
                <svg class="w-3.5 h-3.5 text-white/60 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                {{ $event->event_date->format('d M Y') }}
            </span>
        </div>

        {{-- Pill Tags (Sale phases & Slot Info) --}}
        <div class="flex flex-wrap items-center gap-1.5 pt-1">
            @if($event->salePhases->count())
                @foreach($event->salePhases->take(2) as $phase)
                    <span class="text-[11px] bg-white/15 backdrop-blur-md text-white/90 border border-white/10 px-3 py-1 rounded-full font-medium">
                        {{ $phase->name }}
                    </span>
                @endforeach
                @if($event->salePhases->count() > 2)
                    <span class="text-[11px] bg-white/10 backdrop-blur-md text-white/75 border border-white/10 px-2 py-1 rounded-full font-medium">
                        +{{ $event->salePhases->count() - 2 }}
                    </span>
                @endif
            @endif

            @if($event->status !== 'finished')
                <span class="text-[11px] bg-white/15 backdrop-blur-md text-white/90 border border-white/10 px-3 py-1 rounded-full font-medium">
                    Slot: <strong class="{{ ($event->available_slots ?? 0) > 0 ? 'text-green-300' : 'text-rose-300' }}">{{ $event->total_slots !== null ? ($event->available_slots ?? 0).'/'.$event->total_slots : '∞' }}</strong>
                </span>
            @endif
        </div>

        @if(request('debug'))
        <div class="mt-2 text-xs text-gray-300 bg-black/60 p-2 rounded-xl backdrop-blur-sm">
            <pre class="whitespace-pre-wrap text-xs">{{ json_encode($event->toArray(), JSON_PRETTY_PRINT) }}</pre>
        </div>
        @endif

        {{-- White Pill Reserve Button --}}
        <a href="{{ route('events.show', $event->slug) }}"
            class="mt-2 w-full bg-white hover:bg-gray-100 active:scale-[0.98] text-gray-900 font-bold py-3 rounded-full text-center text-sm transition-all duration-200 shadow-lg hover:shadow-xl flex items-center justify-center gap-2">
            Detail Event
        </a>
    </div>
</div>
