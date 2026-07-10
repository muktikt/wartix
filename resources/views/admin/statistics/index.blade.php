@extends('layouts.admin')
@section('title', 'Statistics')
@section('page-title', 'Statistics')

@section('content')
<div class="grid grid-cols-2 gap-5 mb-5">

    {{-- Orders by Day --}}
    <div class="bg-white border border-gray-100 rounded-xl p-5 reveal hover-lift" x-data x-intersect.once="$el.classList.add('reveal-visible')">
        <h3 class="text-sm font-semibold text-gray-900 mb-4">Orders 30 Hari Terakhir</h3>
        <div class="space-y-2">
            @foreach($ordersByDay->take(7) as $day)
            @php $pct = $day->total > 0 ? ($day->success / $day->total) * 100 : 0; @endphp
            <div class="flex items-center gap-3">
                <span class="text-xs text-gray-400 w-20 flex-shrink-0">
                    {{ \Carbon\Carbon::parse($day->date)->format('d M') }}
                </span>
                <div class="flex-1 bg-gray-100 rounded-full h-1.5">
                    <div class="bg-indigo-600 h-1.5 rounded-full transition-all duration-700 ease-out"
                        style="width: 0%" x-data x-intersect.once="$el.style.width = '{{ $pct }}%'"></div>
                </div>
                <span class="text-xs text-gray-600 w-12 text-right">{{ $day->total }} order</span>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Success by Event --}}
    <div class="bg-white border border-gray-100 rounded-xl p-5 reveal hover-lift" x-data x-intersect.once="$el.classList.add('reveal-visible')" style="transition-delay: 60ms">
        <h3 class="text-sm font-semibold text-gray-900 mb-4">Top Events by Success</h3>
        <div class="space-y-3">
            @foreach($successByEvent as $event)
            <div class="flex items-center gap-3">
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-medium text-gray-900 truncate">{{ $event->title }}</p>
                    <p class="text-xs text-gray-400">{{ $event->artist_name }}</p>
                </div>
                <span class="text-sm font-semibold text-indigo-600 flex-shrink-0">
                    {{ $event->success_count }}
                </span>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Payment Status --}}
    <div class="bg-white border border-gray-100 rounded-xl p-5 reveal hover-lift" x-data x-intersect.once="$el.classList.add('reveal-visible')" style="transition-delay: 120ms">
        <h3 class="text-sm font-semibold text-gray-900 mb-4">Payment Status Distribution</h3>
        <div class="space-y-2">
            @php
            $total  = $paymentStatus->sum('total');
            $colors = [
                'paid'    => 'bg-green-500',
                'pending' => 'bg-yellow-500',
                'unpaid'  => 'bg-gray-300',
                'expired' => 'bg-orange-500',
                'failed'  => 'bg-red-500',
            ];
            @endphp
            @foreach($paymentStatus as $ps)
            @php $pct = $total > 0 ? ($ps->total / $total) * 100 : 0; @endphp
            <div class="flex items-center gap-3">
                <span class="text-xs text-gray-500 w-16 capitalize">{{ $ps->payment_status }}</span>
                <div class="flex-1 bg-gray-100 rounded-full h-1.5">
                    <div class="{{ $colors[$ps->payment_status] ?? 'bg-gray-400' }} h-1.5 rounded-full transition-all duration-700 ease-out"
                        style="width: 0%" x-data x-intersect.once="$el.style.width = '{{ $pct }}%'"></div>
                </div>
                <span class="text-xs text-gray-600 w-8 text-right">{{ $ps->total }}</span>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Revenue by Month --}}
    <div class="bg-white border border-gray-100 rounded-xl p-5 reveal hover-lift" x-data x-intersect.once="$el.classList.add('reveal-visible')" style="transition-delay: 180ms">
        <h3 class="text-sm font-semibold text-gray-900 mb-4">Revenue 6 Bulan Terakhir</h3>
        <div class="space-y-2">
            @foreach($revenueByMonth as $rev)
            <div class="flex items-center justify-between py-1.5 border-b border-gray-50 last:border-0 transition-colors hover:bg-gray-50/60 rounded-lg px-1">
                <span class="text-xs text-gray-500">
                    {{ \Carbon\Carbon::createFromDate($rev->year, $rev->month, 1)->format('M Y') }}
                </span>
                <span class="text-sm font-semibold text-gray-900">
                    Rp {{ number_format($rev->revenue) }}
                </span>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection