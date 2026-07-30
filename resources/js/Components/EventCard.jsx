import { Link } from '@inertiajs/react';
import { formatDate, formatRp } from '../utils/format';

const STATUS_BADGE = {
    ongoing: { label: 'Proses', className: 'bg-green-500 text-white animate-pulse-soft' },
    slot_penuh: { label: 'Slot Penuh', className: 'bg-rose-500 text-white' },
    finished: { label: 'Finished', className: 'bg-gray-500 text-white' },
    upcoming: { label: 'Aktif', className: 'bg-indigo-500 text-white' },
};

export default function EventCard({ event }) {
    const badge = STATUS_BADGE[event.status] ?? STATUS_BADGE.upcoming;
    const phases = event.sale_phases ?? [];
    const categories = event.ticket_categories ?? [];
    const minFee = categories.length ? Math.min(...categories.map((c) => c.fee_per_ticket)) : null;
    const hasAccountStats = event.total_accounts !== undefined && event.total_accounts !== null;

    return (
        <div className="bg-white border border-gray-100 rounded-2xl overflow-hidden hover-lift hover-glow hover:border-indigo-200/50 hover:shadow-xl transition-all duration-300 group">
            {/* Banner */}
            <div className="relative h-40 bg-gradient-to-br from-indigo-900 to-purple-900 overflow-hidden">
                {event.banner_image ? (
                    <img
                        src={`/storage/${event.banner_image}`}
                        alt={event.title}
                        className="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                    />
                ) : (
                    <div className="absolute inset-0 flex items-center justify-center">
                        <span className="text-white/60 text-sm font-medium text-center px-4">{event.title}</span>
                    </div>
                )}
                <div className="absolute top-3 left-3">
                    <span className={`text-xs font-semibold px-2.5 py-1 rounded-full ${badge.className}`}>{badge.label}</span>
                </div>
            </div>

            {/* Body */}
            <div className="p-4">
                <h3 className="font-semibold text-gray-900 text-sm mb-0.5 truncate">{event.title}</h3>
                <p className="text-xs text-gray-400 mb-3">{event.artist_name}</p>

                <div className="space-y-1.5 mb-3">
                    <div className="flex items-center gap-1.5 text-xs text-gray-500">
                        <svg className="w-3.5 h-3.5 text-indigo-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        </svg>
                        {event.venue}, {event.city}
                    </div>
                    <div className="flex items-center gap-1.5 text-xs text-gray-500">
                        <svg className="w-3.5 h-3.5 text-indigo-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        {formatDate(event.event_date)}
                    </div>
                </div>

                {phases.length > 0 && (
                    <div className="flex flex-wrap gap-1 mb-3">
                        {phases.slice(0, 3).map((phase) => (
                            <span key={phase.id} className="text-xs bg-indigo-50 text-indigo-600 px-2 py-0.5 rounded-md">{phase.name}</span>
                        ))}
                        {phases.length > 3 && (
                            <span className="text-xs bg-gray-50 text-gray-500 px-2 py-0.5 rounded-md">+{phases.length - 3}</span>
                        )}
                    </div>
                )}

                {minFee !== null && (
                    <p className="text-xs text-gray-400 mb-3">
                        Fee mulai <span className="font-semibold text-gray-700">{formatRp(minFee)}/tiket</span>
                    </p>
                )}

                {hasAccountStats && (
                    <div className="flex items-center justify-between gap-2 text-xs mb-3">
                        <span className="text-gray-500">
                            {Number(event.success_accounts ?? 0).toLocaleString('id-ID')} sukses / {Number(event.total_accounts ?? 0).toLocaleString('id-ID')} akun
                        </span>
                        <span className={`font-semibold ${(event.success_rate ?? 0) >= 80 ? 'text-green-600' : 'text-indigo-600'}`}>
                            {event.success_rate ?? 0}%
                        </span>
                    </div>
                )}

                {event.status !== 'finished' && (
                    <div className="flex items-center justify-between gap-2 text-xs mb-3 p-2 bg-indigo-50 rounded-lg">
                        <span className="text-indigo-700">Slot Tersedia</span>
                        {event.total_slots !== null && event.total_slots !== undefined ? (
                            <span className={`font-bold ${(event.available_slots ?? 0) > 0 ? 'text-green-600' : 'text-red-600'}`}>
                                {event.available_slots ?? 0}/{event.total_slots}
                            </span>
                        ) : (
                            <span className="font-bold text-indigo-600">&#8734; (tak terbatas)</span>
                        )}
                    </div>
                )}

                <Link
                    href={route('events.show', event.slug)}
                    className="block w-full text-center bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-medium py-2 rounded-lg transition-all duration-300 hover:shadow-md hover:shadow-indigo-500/20 btn-press"
                >
                    View Detail
                </Link>
            </div>
        </div>
    );
}
