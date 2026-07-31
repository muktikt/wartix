import AdminLayout from '../../Layouts/AdminLayout';
import Reveal from '../../Components/Reveal';
import RevealList from '../../Components/RevealList';
import CountUp from '../../Components/CountUp';
import { Link } from '@inertiajs/react';
import { motion } from 'framer-motion';

const STATUS_COLOR = {
    success: 'bg-green-50 text-green-700',
    waiting: 'bg-yellow-50 text-yellow-700',
    processing: 'bg-indigo-50 text-indigo-700',
    failed: 'bg-red-50 text-red-700',
    cancelled: 'bg-gray-50 text-gray-700',
};

function StatCard({ label, rawValue, color, icon, prefix = '', suffix = '' }) {
    return (
        <div className="stat-card bg-white border border-gray-100 rounded-xl p-4 transition-all duration-300 hover:shadow-sm">
            <div className="flex items-center justify-between mb-2">
                <p className="text-xs text-gray-500">{label}</p>
                <div className={`w-7 h-7 rounded-lg bg-${color}-50 flex items-center justify-center`}>
                    <svg className={`w-3.5 h-3.5 text-${color}-500`} fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d={icon} />
                    </svg>
                </div>
            </div>
            <p className="text-2xl font-semibold text-gray-900">
                <CountUp end={rawValue} prefix={prefix} suffix={suffix} />
            </p>
        </div>
    );
}

function formatDateTime(dateString) {
    const d = new Date(dateString);
    const pad = (n) => String(n).padStart(2, '0');
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
    return `${pad(d.getDate())} ${months[d.getMonth()]} ${d.getFullYear()} ${pad(d.getHours())}:${pad(d.getMinutes())}`;
}

export default function Dashboard({ stats, recentOrders }) {
    const cards = [
        { label: 'Total Orders', rawValue: stats.total_orders, color: 'indigo', icon: 'M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z' },
        { label: 'Success Orders', rawValue: stats.success_orders, color: 'green', icon: 'M5 13l4 4L19 7' },
        { label: 'Pending Orders', rawValue: stats.pending_orders, color: 'yellow', icon: 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z' },
        { label: 'Active Events', rawValue: stats.active_events, color: 'purple', icon: 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z' },
    ];

    return (
        <AdminLayout title="Dashboard">
            {stats.pending_link_count > 0 && (
                <motion.div
                    initial={{ opacity: 0, y: -8 }}
                    animate={{ opacity: 1, y: 0 }}
                    className="bg-yellow-50 border border-yellow-100 rounded-xl p-3 mb-6 flex items-center gap-2"
                >
                    <svg className="w-4 h-4 text-yellow-600 flex-shrink-0 animate-pulse-soft" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span className="text-xs text-yellow-700">
                        <strong>{stats.pending_link_count}</strong> order sedang menunggu konfirmasi Telegram (auto-cancel dalam 10 menit jika tidak diklik).
                    </span>
                </motion.div>
            )}

            <RevealList className="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                {cards.map((card) => (
                    <RevealList.Item key={card.label}>
                        <StatCard {...card} />
                    </RevealList.Item>
                ))}
            </RevealList>

            <RevealList className="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6" delay={0.1}>
                <RevealList.Item>
                    <StatCard label="Total Revenue" rawValue={stats.total_revenue} prefix="Rp " color="emerald" icon="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </RevealList.Item>
                <RevealList.Item>
                    <StatCard label="Success Rate" rawValue={stats.success_rate} suffix="%" color="blue" icon="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </RevealList.Item>
            </RevealList>

            <Reveal delay={0.15}>
                <div className="bg-white border border-gray-100 rounded-xl p-4 mb-4">
                    <div className="flex items-center justify-between mb-4">
                        <h2 className="text-sm font-semibold text-gray-900">Recent Orders</h2>
                        <a href={route('admin.orders.index')} className="text-xs text-indigo-600 hover:underline transition-colors duration-200">Lihat semua</a>
                    </div>

                    {recentOrders.length === 0 ? (
                        <p className="text-sm text-gray-400 text-center py-6">Belum ada order.</p>
                    ) : (
                        <div className="overflow-x-auto">
                            <table className="w-full min-w-[500px]">
                                <thead>
                                    <tr className="border-b border-gray-50">
                                        <th className="text-left text-xs text-gray-400 font-medium pb-2">Order</th>
                                        <th className="text-left text-xs text-gray-400 font-medium pb-2">Event</th>
                                        <th className="text-left text-xs text-gray-400 font-medium pb-2">Kategori</th>
                                        <th className="text-left text-xs text-gray-400 font-medium pb-2">Status</th>
                                        <th className="text-left text-xs text-gray-400 font-medium pb-2">Tanggal</th>
                                    </tr>
                                </thead>
                                <tbody className="divide-y divide-gray-50">
                                    {recentOrders.map((order, i) => (
                                        <motion.tr
                                            key={order.id}
                                            initial={{ opacity: 0, x: -8 }}
                                            animate={{ opacity: 1, x: 0 }}
                                            transition={{ duration: 0.3, delay: i * 0.04 }}
                                            className="hover:bg-gray-50 transition-colors"
                                        >
                                            <td className="py-2.5 text-xs font-medium text-gray-900">{order.order_code}</td>
                                            <td className="py-2.5 text-xs text-gray-600">{order.event?.title ?? '-'}</td>
                                            <td className="py-2.5 text-xs text-gray-600">{order.ticket_category?.name ?? '-'} x{order.qty}</td>
                                            <td className="py-2.5">
                                                <span className={`text-xs px-2 py-0.5 rounded-md font-medium ${STATUS_COLOR[order.order_status] ?? 'bg-gray-50 text-gray-700'}`}>
                                                    {order.order_status.charAt(0).toUpperCase() + order.order_status.slice(1)}
                                                </span>
                                            </td>
                                            <td className="py-2.5 text-xs text-gray-400">{formatDateTime(order.created_at)}</td>
                                        </motion.tr>
                                    ))}
                                </tbody>
                            </table>
                        </div>
                    )}
                </div>
            </Reveal>

            <Reveal delay={0.2}>
                <div className="bg-white border border-gray-100 rounded-xl p-4">
                    <h2 className="text-sm font-semibold text-gray-900 mb-3">Quick Actions</h2>
                    <div className="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <a href={route('admin.events.builder.create')} className="flex items-center gap-2 p-3 border border-gray-100 rounded-xl hover:bg-indigo-50 hover:border-indigo-200 transition-all duration-300 group hover-lift">
                            <svg className="w-4 h-4 text-indigo-600 transition-transform duration-300 group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 4v16m8-8H4" />
                            </svg>
                            <span className="text-xs font-medium text-gray-700 group-hover:text-indigo-700 transition-colors duration-200">Add Event</span>
                        </a>
                        <a href={route('admin.reports.index')} className="flex items-center gap-2 p-3 border border-gray-100 rounded-xl hover:bg-indigo-50 hover:border-indigo-200 transition-all duration-300 group hover-lift">
                            <svg className="w-4 h-4 text-indigo-600 transition-transform duration-300 group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                            <span className="text-xs font-medium text-gray-700 group-hover:text-indigo-700 transition-colors duration-200">Reports</span>
                        </a>
                        <a href={route('admin.integrations.index')} className="flex items-center gap-2 p-3 border border-gray-100 rounded-xl hover:bg-indigo-50 hover:border-indigo-200 transition-all duration-300 group hover-lift">
                            <svg className="w-4 h-4 text-indigo-600 transition-transform duration-300 group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                            </svg>
                            <span className="text-xs font-medium text-gray-700 group-hover:text-indigo-700 transition-colors duration-200">Integration</span>
                        </a>
                    </div>
                </div>
            </Reveal>
        </AdminLayout>
    );
}
