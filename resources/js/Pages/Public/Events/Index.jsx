import { useState, useEffect, useRef } from 'react';
import { Link, router } from '@inertiajs/react';
import PublicLayout from '../../../Layouts/PublicLayout';
import EventCard from '../../../Components/EventCard';

export default function EventsIndex({ events, filters }) {
    const [search, setSearch] = useState(filters?.q ?? '');
    const [isLoading, setIsLoading] = useState(false);
    const isInitialMount = useRef(true);

    useEffect(() => {
        // Skip debounce on initial component mount
        if (isInitialMount.current) {
            isInitialMount.current = false;
            return;
        }

        setIsLoading(true);
        const timer = setTimeout(() => {
            router.get(
                route('events.index'),
                search ? { q: search } : {},
                {
                    preserveState: true,
                    preserveScroll: true,
                    replace: true,
                    only: ['events', 'filters'],
                    onFinish: () => setIsLoading(false),
                }
            );
        }, 350);

        return () => {
            clearTimeout(timer);
            setIsLoading(false);
        };
    }, [search]);

    const handleClear = () => {
        setSearch('');
        if (filters?.q) {
            setIsLoading(true);
            router.get(
                route('events.index'),
                {},
                {
                    preserveState: true,
                    preserveScroll: true,
                    replace: true,
                    only: ['events', 'filters'],
                    onFinish: () => setIsLoading(false),
                }
            );
        }
    };

    return (
        <PublicLayout title="Explore Events | Warindong">
            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
                {/* Header */}
                <div className="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-8">
                    <div>
                        <div className="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-50 border border-indigo-100 text-indigo-700 text-xs font-semibold mb-3">
                            <span className="w-2 h-2 rounded-full bg-indigo-600 animate-pulse"></span>
                            Live Ticket Assistance
                        </div>
                        <h1 className="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">Explore Events</h1>
                        <p className="text-sm text-gray-500 mt-1">Temukan event konser, festival, dan fanmeeting favorit kamu</p>
                    </div>

                    {/* Total Events Counter */}
                    <div className="text-xs font-medium text-gray-500 bg-white border border-gray-200 px-3.5 py-2 rounded-xl shadow-xs self-start md:self-auto">
                        Total: <span className="font-bold text-gray-900">{events.total ?? events.data.length}</span> Event
                    </div>
                </div>

                {/* Search Bar */}
                <div className="relative mb-8">
                    <div className="relative flex items-center">
                        <div className="absolute left-4 pointer-events-none text-gray-400">
                            {isLoading ? (
                                <svg className="animate-spin w-5 h-5 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4"></circle>
                                    <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            ) : (
                                <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            )}
                        </div>

                        <input
                            type="text"
                            value={search}
                            onChange={(e) => setSearch(e.target.value)}
                            placeholder="Cari event, artis, kota, venue..."
                            className="w-full pl-12 pr-12 py-3.5 text-sm sm:text-base border border-gray-200 rounded-2xl bg-white shadow-xs focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all placeholder:text-gray-400"
                        />

                        {search && (
                            <button
                                type="button"
                                onClick={handleClear}
                                title="Hapus pencarian"
                                className="absolute right-4 p-1.5 rounded-full text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors"
                            >
                                <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        )}
                    </div>

                    {/* Active search summary */}
                    {filters?.q && (
                        <div className="mt-3 flex items-center justify-between text-xs text-gray-500 px-1">
                            <span>
                                Hasil pencarian untuk: <strong className="text-gray-900 font-semibold">"{filters.q}"</strong> ({events.total ?? events.data.length} event ditemukan)
                            </span>
                            <button
                                type="button"
                                onClick={handleClear}
                                className="text-indigo-600 hover:text-indigo-700 font-medium underline underline-offset-2"
                            >
                                Tampilkan Semua Event
                            </button>
                        </div>
                    )}
                </div>

                {/* Results */}
                {events.data.length === 0 ? (
                    <div className="text-center py-20 bg-white border border-gray-100 rounded-3xl p-8 shadow-xs">
                        <div className="w-16 h-16 bg-gray-50 text-gray-400 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-gray-100">
                            <svg className="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <h3 className="text-base font-bold text-gray-900 mb-1">Event Tidak Ditemukan</h3>
                        <p className="text-sm text-gray-500 max-w-md mx-auto mb-6">
                            {filters?.q
                                ? `Tidak ada event yang sesuai dengan kata kunci "${filters.q}". Coba gunakan kata kunci lain.`
                                : 'Saat ini belum ada event yang tersedia.'}
                        </p>
                        {filters?.q && (
                            <button
                                type="button"
                                onClick={handleClear}
                                className="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold px-5 py-2.5 rounded-xl transition-all shadow-sm hover:shadow"
                            >
                                Reset Pencarian
                            </button>
                        )}
                    </div>
                ) : (
                    <>
                        <div className="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                            {events.data.map((event) => (
                                <EventCard key={event.id} event={event} />
                            ))}
                        </div>

                        {events.links && events.links.length > 3 && (
                            <div className="mt-10 flex flex-wrap items-center justify-center gap-1.5">
                                {events.links.map((link, i) => (
                                    link.url ? (
                                        <Link
                                            key={i}
                                            href={link.url}
                                            preserveScroll
                                            className={`px-3.5 py-2 text-xs font-medium rounded-xl border transition-all ${
                                                link.active
                                                    ? 'bg-indigo-600 text-white border-indigo-600 shadow-sm'
                                                    : 'border-gray-200 text-gray-600 bg-white hover:bg-gray-50'
                                            }`}
                                            dangerouslySetInnerHTML={{ __html: link.label }}
                                        />
                                    ) : (
                                        <span
                                            key={i}
                                            className="px-3.5 py-2 text-xs rounded-xl border border-gray-100 text-gray-300 bg-gray-50/50"
                                            dangerouslySetInnerHTML={{ __html: link.label }}
                                        />
                                    )
                                ))}
                            </div>
                        )}
                    </>
                )}
            </div>
        </PublicLayout>
    );
}
