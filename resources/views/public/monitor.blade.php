@extends('layouts.app')
@section('title', 'Realtime Monitor — Wartix')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-10">
    <div class="flex items-center gap-3 mb-6">
        <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
        <h1 class="text-xl font-bold text-gray-900">Realtime Success Monitor</h1>
        <span class="ml-auto text-xs text-gray-400">Data tersensor untuk privasi</span>
    </div>

    <div class="bg-gray-900 rounded-2xl overflow-hidden">
        <div class="px-5 py-3 border-b border-gray-800 flex items-center gap-2">
            <span class="text-xs text-gray-500 font-mono">STATUS</span>
            <span class="text-xs text-gray-500 font-mono ml-4">EMAIL</span>
            <span class="text-xs text-gray-500 font-mono ml-auto">EVENT | PHASE | CAT | QTY</span>
        </div>
        <div class="divide-y divide-gray-800">
            @forelse($logs as $log)
            <div class="flex items-center gap-3 px-5 py-3 text-sm hover:bg-gray-800/50 transition-colors">
                <span class="bg-green-500/20 text-green-400 text-xs font-mono font-semibold px-2 py-0.5 rounded flex-shrink-0">SUCCESS</span>
                <span class="text-white font-mono text-xs flex-shrink-0 w-36 truncate">{{ $log['email'] }}</span>
                <span class="text-gray-600 text-xs">|</span>
                <span class="text-gray-300 text-xs flex-shrink-0 truncate max-w-[140px]">{{ $log['event'] }}</span>
                <span class="text-gray-600 text-xs">|</span>
                <span class="text-gray-400 text-xs flex-shrink-0">{{ $log['phase'] }}</span>
                <span class="text-gray-600 text-xs">|</span>
                <span class="text-gray-400 text-xs flex-shrink-0">{{ $log['category'] }}</span>
                <span class="text-gray-600 text-xs">|</span>
                <span class="text-gray-400 text-xs flex-shrink-0">x{{ $log['qty'] }}</span>
                <span class="text-gray-600 text-xs ml-auto">{{ $log['time'] }}</span>
            </div>
            @empty
            <div class="text-center py-16 text-gray-500 text-sm">
                Belum ada data sukses. Monitor akan aktif saat event berlangsung.
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection