import { useEffect, useState } from 'react';
import PublicLayout from '../../Layouts/PublicLayout';

export default function Monitor({ logs: initialLogs = [] }) {
    const [logs, setLogs] = useState(initialLogs);

    useEffect(() => {
        if (window.Echo) {
            const channel = window.Echo.channel('success-monitor-public')
                .listen('.success.log.created', (data) => {
                    const newLog = {
                        email: data.publicData?.email ?? 'us***@example.com',
                        event: data.publicData?.event ?? '-',
                        phase: data.publicData?.phase ?? '-',
                        category: data.publicData?.category ?? '-',
                        qty: data.publicData?.qty ?? 1,
                        time: 'baru saja',
                    };
                    setLogs((prev) => [newLog, ...prev]);
                });

            return () => {
                channel.stopListening('.success.log.created');
            };
        }
    }, []);

    return (
        <PublicLayout title="Realtime Success Monitor">
            <div className="max-w-5xl mx-auto px-4 py-10">
                <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
                    <div>
                        <div className="flex items-center gap-2">
                            <span className="w-2.5 h-2.5 bg-emerald-500 rounded-full animate-pulse"></span>
                            <h1 className="text-xl font-bold text-gray-900">Realtime Success Monitor</h1>
                        </div>
                        <p className="text-xs text-gray-500 mt-1">Pantau status transaksi order yang berhasil secara realtime</p>
                    </div>
                    <span className="text-xs bg-gray-100 text-gray-600 px-3 py-1.5 rounded-lg font-medium self-start sm:self-auto">
                        Data tersensor untuk privasi
                    </span>
                </div>

                <div className="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
                    <div className="overflow-x-auto">
                        <table className="w-full text-left text-xs text-gray-600">
                            <thead class="bg-gray-50 border-b border-gray-100 text-gray-500 font-semibold uppercase tracking-wider">
                                <tr>
                                    <th className="px-5 py-3.5">Status</th>
                                    <th className="px-5 py-3.5">Email</th>
                                    <th className="px-5 py-3.5">Event</th>
                                    <th className="px-5 py-3.5">Phase</th>
                                    <th className="px-5 py-3.5">Kategori</th>
                                    <th className="px-5 py-3.5">Qty</th>
                                    <th className="px-5 py-3.5 text-right">Waktu</th>
                                </tr>
                            </thead>
                            <tbody className="divide-y divide-gray-100 bg-white">
                                {logs.length ? (
                                    logs.map((log, index) => (
                                        <tr key={index} className="hover:bg-gray-50/70 transition-colors">
                                            <td className="px-5 py-3.5 whitespace-nowrap">
                                                <span className="bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-semibold px-2.5 py-0.5 rounded-full">
                                                    SUCCESS
                                                </span>
                                            </td>
                                            <td className="px-5 py-3.5 font-medium text-gray-900 whitespace-nowrap font-mono">
                                                {log.email}
                                            </td>
                                            <td className="px-5 py-3.5 font-medium text-gray-800 whitespace-nowrap">
                                                {log.event}
                                            </td>
                                            <td className="px-5 py-3.5 text-gray-600 whitespace-nowrap">
                                                {log.phase}
                                            </td>
                                            <td className="px-5 py-3.5 text-gray-600 whitespace-nowrap">
                                                {log.category}
                                            </td>
                                            <td className="px-5 py-3.5 text-gray-600 whitespace-nowrap">
                                                <span className="bg-gray-100 text-gray-700 px-2 py-0.5 rounded font-medium">x{log.qty}</span>
                                            </td>
                                            <td className="px-5 py-3.5 text-right text-gray-400 whitespace-nowrap">
                                                {log.time}
                                            </td>
                                        </tr>
                                    ))
                                ) : (
                                    <tr>
                                        <td colSpan="7" className="text-center py-12 text-gray-400">
                                            Belum ada data sukses. Monitor akan aktif saat event berlangsung.
                                        </td>
                                    </tr>
                                )}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </PublicLayout>
    );
}
