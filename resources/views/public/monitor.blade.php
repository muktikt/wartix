@extends('layouts.app')
@section('title', 'Realtime Monitor Wartix')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-10">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6 reveal-drop">
        <div>
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 bg-emerald-500 rounded-full animate-pulse"></span>
                <h1 class="text-xl font-bold text-gray-900">Realtime Success Monitor</h1>
            </div>
            <p class="text-xs text-gray-500 mt-1">Pantau status transaksi order yang berhasil secara realtime</p>
        </div>
        <span class="text-xs bg-gray-100 text-gray-600 px-3 py-1.5 rounded-lg font-medium self-start sm:self-auto">Data tersensor untuk privasi</span>
    </div>

    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden reveal-drop" data-delay="150">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-gray-600">
                <thead class="bg-gray-50 border-b border-gray-100 text-gray-500 font-semibold uppercase tracking-wider">
                    <tr>
                        <th class="px-5 py-3.5">Status</th>
                        <th class="px-5 py-3.5">Email</th>
                        <th class="px-5 py-3.5">Event</th>
                        <th class="px-5 py-3.5">Phase</th>
                        <th class="px-5 py-3.5">Kategori</th>
                        <th class="px-5 py-3.5">Qty</th>
                        <th class="px-5 py-3.5 text-right">Waktu</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white" id="monitorList">
                    @forelse($logs as $log)
                    <tr class="hover:bg-gray-50/70 transition-colors">
                        <td class="px-5 py-3.5 whitespace-nowrap">
                            <span class="bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-semibold px-2.5 py-0.5 rounded-full">
                                SUCCESS
                            </span>
                        </td>
                        <td class="px-5 py-3.5 font-medium text-gray-900 whitespace-nowrap font-mono">
                            {{ $log['email'] }}
                        </td>
                        <td class="px-5 py-3.5 font-medium text-gray-800 whitespace-nowrap">
                            {{ $log['event'] }}
                        </td>
                        <td class="px-5 py-3.5 text-gray-600 whitespace-nowrap">
                            {{ $log['phase'] }}
                        </td>
                        <td class="px-5 py-3.5 text-gray-600 whitespace-nowrap">
                            {{ $log['category'] }}
                        </td>
                        <td class="px-5 py-3.5 text-gray-600 whitespace-nowrap">
                            <span class="bg-gray-100 text-gray-700 px-2 py-0.5 rounded font-medium">x{{ $log['qty'] }}</span>
                        </td>
                        <td class="px-5 py-3.5 text-right text-gray-400 whitespace-nowrap">
                            {{ $log['time'] }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-12 text-gray-400">
                            Belum ada data sukses. Monitor akan aktif saat event berlangsung.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
if (window.Echo) {
    window.Echo.channel('success-monitor-public')
        .listen('.success.log.created', (data) => {
            const container = document.getElementById('monitorList');
            if (!container) return;
            const tr = document.createElement('tr');
            tr.className = 'hover:bg-gray-50/70 transition-colors bg-emerald-50/30 animate-pulse';
            tr.innerHTML = `
                <td class="px-5 py-3.5 whitespace-nowrap">
                    <span class="bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-semibold px-2.5 py-0.5 rounded-full">
                        SUCCESS
                    </span>
                </td>
                <td class="px-5 py-3.5 font-medium text-gray-900 whitespace-nowrap font-mono">${data.publicData.email}</td>
                <td class="px-5 py-3.5 font-medium text-gray-800 whitespace-nowrap">${data.publicData.event}</td>
                <td class="px-5 py-3.5 text-gray-600 whitespace-nowrap">${data.publicData.phase}</td>
                <td class="px-5 py-3.5 text-gray-600 whitespace-nowrap">${data.publicData.category}</td>
                <td class="px-5 py-3.5 text-gray-600 whitespace-nowrap"><span class="bg-gray-100 text-gray-700 px-2 py-0.5 rounded font-medium">x${data.publicData.qty}</span></td>
                <td class="px-5 py-3.5 text-right text-gray-400 whitespace-nowrap">baru saja</td>
            `;
            container.prepend(tr);
            setTimeout(() => tr.classList.remove('animate-pulse', 'bg-emerald-50/30'), 2500);
        });
}
</script>
@endpush