import { useState } from 'react';
import { Link, router } from '@inertiajs/react';
import PublicLayout from '../../../Layouts/PublicLayout';
import EventCard from '../../../Components/EventCard';

const PLATFORMS = [
    { value: 'tiketcom', label: 'Tiket.com' },
    { value: 'loket', label: 'Loket' },
    { value: 'yesplis', label: 'Yesplis' },
    { value: 'goers', label: 'Goers' },
    { value: 'fasticket', label: 'Fasticket' },
    { value: 'custom', label: 'Custom' },
];

const STATUSES = [
    { value: 'upcoming', label: 'Upcoming' },
    { value: 'ongoing', label: 'Ongoing' },
    { value: 'finished', label: 'Finished' },
];

export default function EventsIndex({ events, cities, types, filters }) {
    const [form, setForm] = useState({
        q: filters.q ?? '',
        city: filters.city ?? '',
        type: filters.type ?? '',
        platform: filters.platform ?? '',
        status: filters.status ?? '',
    });

    const hasActiveFilters = Object.values(filters).some(Boolean);

    function submit(e) {
        e?.preventDefault();
        router.get(route('events.index'), form, { preserveState: true, preserveScroll: true });
    }

    function reset() {
        router.get(route('events.index'));
    }

    return (
        <PublicLayout title="Events Warindong">
            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
                <div className="mb-8">
                    <h1 className="text-2xl font-bold text-gray-900 mb-2">Explore Events</h1>
                    <p className="text-sm text-gray-500">Temukan event konser, festival, dan fanmeeting</p>
                </div>

                {/* Search */}
                <form onSubmit={submit} className="mb-6">
                    <div className="flex gap-3 mb-3">
                        <div className="flex-1 relative">
                            <svg className="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <input
                                type="text"
                                value={form.q}
                                onChange={(e) => setForm({ ...form, q: e.target.value })}
                                placeholder="Cari event, artis, kota, venue, sale phase, atau kategori tiket..."
                                className="w-full pl-10 pr-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white"
                            />
                        </div>
                        <button type="submit" className="bg-indigo-600 text-white text-sm px-5 py-2.5 rounded-xl hover:bg-indigo-700">
                            Cari
                        </button>
                        {hasActiveFilters && (
                            <button type="button" onClick={reset} className="text-sm text-gray-500 px-4 py-2.5 border border-gray-200 rounded-xl hover:bg-gray-50">
                                Reset
                            </button>
                        )}
                    </div>

                    <div className="flex flex-wrap gap-2">
                        <select
                            value={form.city}
                            onChange={(e) => setForm({ ...form, city: e.target.value })}
                            className="text-xs border border-gray-200 rounded-lg px-2.5 py-1.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white"
                        >
                            <option value="">Semua Kota</option>
                            {cities.map((city) => <option key={city} value={city}>{city}</option>)}
                        </select>
                        <select
                            value={form.type}
                            onChange={(e) => setForm({ ...form, type: e.target.value })}
                            className="text-xs border border-gray-200 rounded-lg px-2.5 py-1.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white"
                        >
                            <option value="">Semua Jenis</option>
                            {types.map((type) => <option key={type} value={type}>{type}</option>)}
                        </select>
                        <select
                            value={form.platform}
                            onChange={(e) => setForm({ ...form, platform: e.target.value })}
                            className="text-xs border border-gray-200 rounded-lg px-2.5 py-1.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white"
                        >
                            <option value="">Semua Platform</option>
                            {PLATFORMS.map((p) => <option key={p.value} value={p.value}>{p.label}</option>)}
                        </select>
                        <select
                            value={form.status}
                            onChange={(e) => setForm({ ...form, status: e.target.value })}
                            className="text-xs border border-gray-200 rounded-lg px-2.5 py-1.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white"
                        >
                            <option value="">Semua Status</option>
                            {STATUSES.map((s) => <option key={s.value} value={s.value}>{s.label}</option>)}
                        </select>
                        <button type="submit" className="text-xs bg-gray-100 text-gray-600 px-3 py-1.5 rounded-lg hover:bg-gray-200">
                            Apply Filter
                        </button>
                    </div>
                </form>

                {/* Results */}
                {events.data.length === 0 ? (
                    <div className="text-center py-16 bg-white border border-gray-100 rounded-2xl">
                        <svg className="w-12 h-12 text-gray-200 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <p className="text-gray-400 text-sm">Tidak ada event yang ditemukan.</p>
                        {filters.q && <p className="text-gray-400 text-xs mt-1">Coba kata kunci lain atau hapus filter.</p>}
                    </div>
                ) : (
                    <>
                        <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-5">
                            {events.data.map((event) => <EventCard key={event.id} event={event} />)}
                        </div>

                        {events.links.length > 3 && (
                            <div className="mt-6 flex flex-wrap items-center justify-center gap-1">
                                {events.links.map((link, i) => (
                                    link.url ? (
                                        <Link
                                            key={i}
                                            href={link.url}
                                            preserveScroll
                                            className={`px-3 py-1.5 text-xs rounded-lg border ${link.active ? 'bg-indigo-600 text-white border-indigo-600' : 'border-gray-200 text-gray-600 hover:bg-gray-50'}`}
                                            dangerouslySetInnerHTML={{ __html: link.label }}
                                        />
                                    ) : (
                                        <span
                                            key={i}
                                            className="px-3 py-1.5 text-xs rounded-lg border border-gray-100 text-gray-300"
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
