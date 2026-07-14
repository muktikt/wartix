@extends('layouts.admin')

@section('title', 'Notifikasi')
@section('page-title', 'Notifikasi')

@section('content')
<div class="mb-4 flex items-center justify-between">
    <div>
        <h2 class="text-sm font-semibold text-gray-900">Semua Notifikasi</h2>
        <p class="text-xs text-gray-400 mt-0.5">Notifikasi order masuk dan pembayaran fee</p>
    </div>
    @if($notifications->where('read_at', null)->count() > 0)
    <form method="POST" action="{{ route('admin.notifications.readAll') }}">
        @csrf
        <button type="submit" class="text-xs text-indigo-600 hover:text-indigo-700 font-medium transition-colors">
            Tandai semua sudah dibaca
        </button>
    </form>
    @endif
</div>

@if($notifications->isEmpty())
    <div class="text-center py-16 bg-gray-50 rounded-2xl">
        <svg class="w-10 h-10 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
        </svg>
        <p class="text-sm text-gray-400">Belum ada notifikasi.</p>
    </div>
@else
    <div class="space-y-2">
        @foreach($notifications as $notif)
        <div class="relative flex items-start gap-3 bg-white border rounded-xl p-4 transition-all duration-200 hover:shadow-sm
            {{ $notif->link ? 'cursor-pointer hover:border-indigo-200' : '' }}
            {{ $notif->isRead() ? 'border-gray-100 opacity-70' : 'border-indigo-100 bg-indigo-50/30' }}">

            @if($notif->link)
            <a href="{{ route('admin.notifications.read', $notif->id) }}" class="absolute inset-0 z-0 rounded-xl"></a>
            @endif

            {{-- Icon --}}
            <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0
                @if($notif->color === 'green') bg-green-50 text-green-600
                @elseif($notif->color === 'emerald') bg-emerald-50 text-emerald-600
                @elseif($notif->color === 'red') bg-red-50 text-red-600
                @else bg-indigo-50 text-indigo-600
                @endif">

                @if($notif->icon === 'cart')
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                @elseif($notif->icon === 'cash')
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                @elseif($notif->icon === 'check')
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                @else
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                @endif
            </div>

            {{-- Content --}}
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 mb-0.5">
                    <p class="text-sm font-semibold text-gray-900">{{ $notif->title }}</p>
                    @unless($notif->isRead())
                        <span class="w-1.5 h-1.5 bg-indigo-500 rounded-full flex-shrink-0"></span>
                    @endunless
                </div>
                <p class="text-xs text-gray-500 leading-relaxed">{{ $notif->message }}</p>
                <p class="text-xs text-gray-400 mt-1">{{ $notif->created_at->diffForHumans() }}</p>
            </div>

            {{-- Actions --}}
            <div class="relative z-10 flex items-center gap-1 flex-shrink-0">
                @if($notif->link)
                <a href="{{ route('admin.notifications.read', $notif->id) }}"
                    class="w-7 h-7 rounded-lg flex items-center justify-center text-gray-400 hover:bg-indigo-50 hover:text-indigo-600 transition-colors"
                    title="Buka & tandai dibaca">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                </a>
                @endif
                <form method="POST" action="{{ route('admin.notifications.destroy', $notif->id) }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="w-7 h-7 rounded-lg flex items-center justify-center text-gray-400 hover:bg-red-50 hover:text-red-500 transition-colors"
                        title="Hapus notifikasi">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-4">
        {{ $notifications->links() }}
    </div>
@endif
@endsection
