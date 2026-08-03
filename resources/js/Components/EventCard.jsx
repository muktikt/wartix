import { Link } from '@inertiajs/react';
import { formatDate, formatRp } from '../utils/format';

const STATUS_BADGE = {
    ongoing: { label: 'Proses', className: 'bg-green-500/90 text-white animate-pulse-soft' },
    slot_penuh: { label: 'Slot Penuh', className: 'bg-rose-500/90 text-white' },
    finished: { label: 'Finished', className: 'bg-gray-600/90 text-white' },
    upcoming: { label: 'Aktif', className: 'bg-indigo-600/90 text-white' },
};

export default function EventCard({ event }) {
    const badge = STATUS_BADGE[event.status] ?? STATUS_BADGE.upcoming;
    const phases = event.sale_phases ?? [];
    const categories = event.ticket_categories ?? [];
    const minFee = categories.length ? Math.min(...categories.map((c) => c.fee_per_ticket)) : null;

    return (
        <div className="relative group rounded-[32px] overflow-hidden bg-gray-900 border-4 border-white dark:border-gray-800 shadow-xl hover:shadow-2xl hover:-translate-y-1.5 transition-all duration-300 flex flex-col justify-end min-h-[380px]">
            {/* Banner Image & Gradient Overlay */}
            <div className="absolute inset-0 z-0">
                {event.banner_image ? (
                    <img
                        src={`/storage/${event.banner_image}`}
                        alt={event.title}
                        className="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
                    />
                ) : (
                    <div className="w-full h-full bg-gradient-to-br from-indigo-950 via-purple-950 to-slate-900 flex items-center justify-center p-6 text-center">
                        <span className="text-white/50 text-base font-semibold">{event.title}</span>
                    </div>
                )}
                {/* Dark gradient overlay */}
                <div className="absolute inset-0 bg-gradient-to-t from-black via-black/85 via-50% to-transparent"></div>
            </div>

            {/* Top Badges */}
            <div className="absolute top-4 left-4 z-10">
                <span className={`text-xs font-semibold px-3 py-1 rounded-full backdrop-blur-md ${badge.className}`}>{badge.label}</span>
            </div>

            {/* Content Overlay */}
            <div className="relative z-10 p-5 flex flex-col gap-2.5">
                <div className="flex items-start justify-between gap-3">
                    <div className="min-w-0 flex-1">
                        <h3 className="font-bold text-white text-lg leading-snug truncate drop-shadow-sm">{event.title}</h3>
                        {event.artist_name && (
                            <p className="text-xs text-white/70 font-medium truncate mt-0.5">{event.artist_name}</p>
                        )}
                    </div>
                    {minFee !== null && (
                        <span className="bg-white/20 backdrop-blur-md text-white text-xs font-semibold px-3 py-1 rounded-full shrink-0 border border-white/10">
                            Fee {formatRp(minFee)}
                        </span>
                    )}
                </div>

                <div className="flex items-center gap-3 text-xs text-white/75">
                    <span className="flex items-center gap-1 truncate">
                        <svg className="w-3.5 h-3.5 text-white/60 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        </svg>
                        {event.venue}, {event.city}
                    </span>
                    <span className="text-white/40">•</span>
                    <span className="flex items-center gap-1 shrink-0">
                        <svg className="w-3.5 h-3.5 text-white/60 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        {formatDate(event.event_date)}
                    </span>
                </div>

                <div className="flex flex-wrap items-center gap-1.5 pt-1">
                    {phases.slice(0, 2).map((phase) => (
                        <span key={phase.id} className="text-[11px] bg-white/15 backdrop-blur-md text-white/90 border border-white/10 px-3 py-1 rounded-full font-medium">
                            {phase.name}
                        </span>
                    ))}
                    {phases.length > 2 && (
                        <span className="text-[11px] bg-white/10 backdrop-blur-md text-white/75 border border-white/10 px-2 py-1 rounded-full font-medium">
                            +{phases.length - 2}
                        </span>
                    )}

                    {event.status !== 'finished' && (
                        <span className="text-[11px] bg-white/15 backdrop-blur-md text-white/90 border border-white/10 px-3 py-1 rounded-full font-medium">
                            Slot: <strong className={(event.available_slots ?? 0) > 0 ? 'text-green-300' : 'text-rose-300'}>
                                {event.total_slots !== null && event.total_slots !== undefined ? `${event.available_slots ?? 0}/${event.total_slots}` : '∞'}
                            </strong>
                        </span>
                    )}
                </div>

                <Link
                    href={route('events.show', event.slug)}
                    className="mt-2 w-full bg-white hover:bg-gray-100 active:scale-[0.98] text-gray-900 font-bold py-3 rounded-full text-center text-sm transition-all duration-200 shadow-lg hover:shadow-xl flex items-center justify-center gap-2"
                >
                    Detail Event
                </Link>
            </div>
        </div>
    );
}
