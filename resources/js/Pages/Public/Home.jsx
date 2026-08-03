import { useState } from 'react';
import { Link } from '@inertiajs/react';
import PublicLayout from '../../Layouts/PublicLayout';
import EventCard from '../../Components/EventCard';
import CountUp from '../../Components/CountUp';

const WHATSAPP_LINK = 'https://chat.whatsapp.com/CBgJ9tYH2F08OlteajZcBJ?s=cl&p=i&ilr=4';
const X_LINK = 'https://x.com/wartixcom';
const TIKTOK_LINK = 'https://www.tiktok.com/@wartix.com';
const TELEGRAM_LINK = 'https://t.me/wartixdotcom';

const ORDER_STEPS = [
    { num: '1', title: 'Pilih event', desc: 'Buka daftar event yang tersedia, lalu pilih event yang mau kamu amankan.' },
    { num: '2', title: 'Buka detail event', desc: 'Cek sale phase, kategori tiket, fee jasa, dan informasi event.' },
    { num: '3', title: 'Isi form order', desc: 'Lengkapi data diri, jumlah tiket, dan detail yang dibutuhkan.' },
    { num: '4', title: 'Tunggu proses', desc: 'Setelah order masuk, pantau status di monitor dan Telegram.' },
];

const FAQS = [
    { q: 'Apa itu Wartix?', a: 'Wartix adalah platform Ticket Assistance yang membantu kamu mendapatkan tiket konser, festival, dan fanmeeting high-demand dengan layanan profesional dan update realtime via Telegram.' },
    { q: 'Apakah ada jaminan berhasil?', a: 'Kami menampilkan success rate berdasarkan data akun yang benar-benar masuk dan berhasil. Hasil tetap bergantung pada ketersediaan tiket di platform resmi.' },
    { q: 'Kapan saya membayar fee jasa?', a: 'Pembayaran fee jasa dilakukan setelah tiket berhasil didapatkan. QRIS akan dikirim otomatis ke Telegram kamu begitu proses berhasil.' },
    { q: 'Data saya aman?', a: 'Ya, data kamu dienkripsi dan hanya digunakan untuk keperluan reservasi tiket. Data sensitif tidak pernah ditampilkan secara publik.' },
    { q: 'Bagaimana cara memantau status order?', a: 'Kamu akan mendapat notifikasi langsung via Telegram. Selain itu, kamu juga bisa memantau di halaman Realtime Monitor kami.' },
];

function ChevronIcon({ open }) {
    return (
        <svg className={`w-4 h-4 text-gray-400 transition-transform duration-300 flex-shrink-0 ${open ? 'rotate-180' : ''}`} fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M19 9l-7 7-7-7" />
        </svg>
    );
}

export default function Home({ stats, activeEvents, recentSuccess }) {
    const [openFaq, setOpenFaq] = useState(null);
    const previewSuccess = recentSuccess.slice(0, 4);

    const statsDisplay = [
        { value: stats.success_rate, suffix: '%', label: 'Success Rate', sub: 'Akun sukses' },
        { value: Number(stats.total_accounts), label: 'Total Accounts', sub: 'Akun yang pernah order' },
        { value: Number(stats.success_accounts), label: 'Success Accounts', sub: 'Akun yang berhasil' },
        { value: Number(stats.active_events), label: 'Active Events', sub: 'Event berlangsung' },
    ];

    return (
        <PublicLayout title="Wartix Priority Ticket Assistance">
            {/* HERO */}
            <section className="relative overflow-hidden bg-gradient-to-r from-[#f8f9ff] via-[#f1f3fe] to-[#e8edff] py-14 px-4 sm:px-6 lg:px-8 border-b border-gray-100/80">
                {/* Right 3D Blue-Purple Gradient Shape */}
                <div className="absolute -right-28 -top-10 w-[620px] h-[620px] rounded-full bg-gradient-to-bl from-[#3b82f6] via-[#6366f1] to-[#7c3aed] opacity-90 blur-2xl pointer-events-none"></div>

                {/* Bottom-Left Pink-Purple Soft Glow */}
                <div className="absolute -left-28 -bottom-10 w-[480px] h-[480px] rounded-full bg-gradient-to-tr from-pink-300/35 via-purple-300/30 to-indigo-200/20 blur-3xl pointer-events-none"></div>

                {/* Ambient Center Overlay */}
                <div className="absolute inset-0 bg-gradient-to-b from-transparent via-white/10 to-white/40 pointer-events-none"></div>

                <div className="relative z-10 max-w-7xl mx-auto grid md:grid-cols-2 gap-10 items-start">
                    <div>
                        <div className="flex flex-wrap gap-2 mb-5">
                            <span className="inline-flex items-center gap-1.5 text-xs font-medium bg-indigo-50 text-indigo-700 px-3 py-1 rounded-full animate-fade-in-down">
                                Priority Access
                            </span>
                            <span className="inline-flex items-center gap-1.5 text-xs font-medium bg-purple-50 text-purple-700 px-3 py-1 rounded-full animate-fade-in-down anim-delay-100">
                                Ticket Assistance
                            </span>
                            <span className="inline-flex items-center gap-1.5 text-xs font-medium bg-green-50 text-green-700 px-3 py-1 rounded-full animate-fade-in-down anim-delay-200">
                                Realtime Monitoring
                            </span>
                        </div>

                        <h1 className="text-4xl md:text-5xl font-bold text-gray-900 leading-tight mb-4 animate-fade-in-down anim-delay-150">
                            Priority Ticket Assistance<br />
                            <span className="text-indigo-600 animate-text-shine">for High-Demand Events</span>
                        </h1>

                        <p className="text-gray-500 text-base leading-relaxed mb-8 max-w-md animate-fade-in anim-delay-300">
                            Wartix membantu kamu mendapatkan tiket konser, festival, dan fanmeeting impian dengan layanan Ticket Assistance, Realtime Monitoring, dan notifikasi langsung via Telegram.
                        </p>

                        <div className="flex flex-wrap gap-3 animate-fade-in anim-delay-450">
                            <a href="#active-events" className="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm px-5 py-2.5 rounded-xl transition-all duration-300 hover-lift hover:shadow-lg hover:shadow-indigo-500/25 btn-press">
                                View Active Events
                            </a>
                            <a href={TELEGRAM_LINK} target="_blank" rel="noopener noreferrer" className="inline-flex items-center gap-2 bg-white hover:bg-gray-50 text-gray-700 border border-gray-200 font-medium text-sm px-5 py-2.5 rounded-xl transition-all duration-300 hover-lift hover:border-indigo-200 btn-press">
                                Join Telegram Channel
                            </a>
                        </div>
                    </div>

                    <div className="flex justify-center md:justify-end animate-scale-in anim-delay-150">
                        <div className="w-full max-w-sm flex flex-col gap-4">
                            <div className="bg-white/95 backdrop-blur-md border border-white/80 rounded-2xl p-5 w-full shadow-xl hover-glow transition-all duration-300">
                                <div className="flex items-center justify-between mb-4">
                                    <div className="flex items-center gap-2">
                                        <span className="text-sm font-semibold text-gray-900">Preview Realtime Monitor</span>
                                    </div>
                                    <span className="flex items-center gap-1 text-xs text-green-600 bg-green-50 px-2 py-0.5 rounded-full font-medium">
                                        <span className="w-1.5 h-1.5 bg-green-500 rounded-full live-indicator"></span>
                                        Live
                                    </span>
                                </div>

                                <div className="text-xs text-gray-500 leading-relaxed mb-4">
                                    Klik <span className="font-medium text-gray-700">Lihat detail</span> untuk langsung turun ke bagian Realtime Success Monitor di dashboard ini.
                                </div>

                                <div className="space-y-2">
                                    {previewSuccess.length ? previewSuccess.map((log) => (
                                        <div key={log.id} className="rounded-xl border border-gray-100 bg-gray-50 px-3 py-2 transition-transform duration-300 hover:scale-[1.02]">
                                            <div className="flex items-center gap-2 mb-1">
                                                <span className="bg-green-500/15 text-green-600 text-[10px] font-semibold px-2 py-0.5 rounded">SUCCESS</span>
                                                <span className="text-xs text-gray-500 truncate">{log.email}</span>
                                            </div>
                                            <div className="flex items-center gap-2 text-xs text-gray-700">
                                                <span className="font-medium truncate">{log.event?.title ?? '-'}</span>
                                                <span className="text-gray-300">&bull;</span>
                                                <span className="truncate">{log.sale_phase?.name ?? '-'}</span>
                                                <span className="text-gray-300">&bull;</span>
                                                <span>x{log.qty}</span>
                                            </div>
                                        </div>
                                    )) : (
                                        <div className="rounded-xl border border-dashed border-gray-200 bg-gray-50 px-3 py-6 text-center text-xs text-gray-400">
                                            Belum ada data sukses untuk ditampilkan.
                                        </div>
                                    )}
                                </div>

                                <a href="#monitor" className="mt-4 inline-flex items-center gap-2 text-sm font-medium text-indigo-600 hover:text-indigo-700 transition-colors duration-200 group btn-press">
                                    Lihat detail
                                    <svg className="w-4 h-4 transition-transform duration-200 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </div>

                            <div className="flex flex-wrap items-center justify-center md:justify-start gap-3 px-1">
                                <span className="text-xs font-semibold text-gray-500 uppercase tracking-wider">Follow Us:</span>
                                <div className="flex items-center gap-2.5">
                                    <a href={WHATSAPP_LINK} target="_blank" rel="noopener noreferrer" className="group flex items-center justify-center w-9 h-9 rounded-full bg-white border border-gray-200 text-gray-500 hover:text-white hover:bg-[#25D366] hover:border-[#25D366] transition-all duration-300 hover:scale-110 hover:-translate-y-0.5 shadow-sm hover:shadow-md hover:shadow-emerald-500/20 active:scale-95 btn-press" title="WhatsApp Group">
                                        <svg className="w-4 h-4 fill-current transition-transform duration-300 group-hover:scale-110" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z" /></svg>
                                    </a>
                                    <a href={X_LINK} target="_blank" rel="noopener noreferrer" className="group flex items-center justify-center w-9 h-9 rounded-full bg-white border border-gray-200 text-gray-500 hover:text-white hover:bg-black hover:border-black transition-all duration-300 hover:scale-110 hover:-translate-y-0.5 shadow-sm hover:shadow-md hover:shadow-black/20 active:scale-95 btn-press" title="X (Twitter)">
                                        <svg className="w-3.5 h-3.5 fill-current transition-transform duration-300 group-hover:scale-110" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z" /></svg>
                                    </a>
                                    <a href={TIKTOK_LINK} target="_blank" rel="noopener noreferrer" className="group flex items-center justify-center w-9 h-9 rounded-full bg-white border border-gray-200 text-gray-500 hover:text-[#FE2C55] hover:border-[#FE2C55] transition-all duration-300 hover:scale-110 hover:-translate-y-0.5 shadow-sm hover:shadow-md hover:shadow-rose-500/20 active:scale-95 btn-press" title="TikTok">
                                        <svg className="w-3.5 h-3.5 fill-current transition-transform duration-300 group-hover:scale-110" viewBox="0 0 24 24"><path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.98-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.07-1.3 1.8-.24.84-.06 1.77.47 2.46.58.78 1.52 1.25 2.49 1.25.75-.01 1.48-.28 2.05-.76.77-.63 1.22-1.6 1.24-2.61.02-4.52.01-9.04.01-13.56z" /></svg>
                                    </a>
                                    <span className="w-px h-5 bg-gray-200"></span>
                                    <a href="#" onClick={(e) => e.preventDefault()} className="group flex items-center justify-center w-9 h-9 rounded-full bg-gray-50 border border-gray-150 text-gray-400 cursor-not-allowed opacity-50 transition-all duration-300" title="Instagram (Segera)">
                                        <svg className="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" /></svg>
                                    </a>
                                    <a href="#" onClick={(e) => e.preventDefault()} className="group flex items-center justify-center w-9 h-9 rounded-full bg-gray-50 border border-gray-150 text-gray-400 cursor-not-allowed opacity-50 transition-all duration-300" title="Threads (Segera)">
                                        <svg className="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M12.004 0C5.373 0 0 5.373 0 12s5.373 12 12 12c4.717 0 8.784-2.723 10.702-6.666-.35-.205-.724-.38-1.127-.514-1.704 3.473-5.26 5.867-9.575 5.867-5.918 0-10.686-4.768-10.686-10.687S6.086 1.313 12.004 1.313c5.312 0 9.718 3.864 10.536 8.986.173 1.085.076 2.148-.27 3.078-.518 1.394-1.637 2.378-3.07 2.701-1.258.283-2.585-.028-3.328-.781-.592-.6-.827-1.464-.664-2.435.156-.931.761-1.798 1.704-2.441.879-.6 1.983-.984 3.197-1.109-.074-.755-.308-1.392-.703-1.892-.682-.864-1.821-1.309-3.393-1.321-1.579 0-2.825.485-3.602 1.401-.735.868-1.077 2.083-1.018 3.612.059 1.528.513 2.729 1.35 3.567.82.822 1.956 1.256 3.376 1.291.688.017 1.378-.066 2.05-.246.126.335.297.653.511.947.382.525.868.948 1.445 1.259 1.066.574 2.326.689 3.548.324 1.957-.585 3.5-1.996 4.232-3.868.49-1.253.642-2.673.439-4.116-1.066-6.666-6.844-11.716-13.785-11.716zm2.49 11.233c-.785.088-1.503.351-2.078.761-.555.396-.889.877-.97 1.393-.075.48.026.877.29 1.155.305.32.791.465 1.449.432.96-.048 1.724-.378 2.274-.981.366-.402.57-.91.606-1.512-.505.025-1.029.109-1.571.752z" /></svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {/* STATS */}
            <section className="border-y border-gray-100 bg-white">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="grid grid-cols-2 md:grid-cols-4 divide-x divide-gray-100">
                        {statsDisplay.map((stat) => (
                            <div key={stat.label} className="py-8 px-6 flex flex-col items-center justify-center text-center transition-all duration-300 hover:bg-gray-50">
                                <div className="text-3xl font-bold text-indigo-600 mb-0.5">
                                    <CountUp end={stat.value} suffix={stat.suffix ?? ''} />
                                </div>
                                <div className="text-sm font-medium text-gray-900">{stat.label}</div>
                                <div className="text-xs text-gray-400 mt-0.5">{stat.sub}</div>
                            </div>
                        ))}
                    </div>
                </div>
            </section>

            {/* ACTIVE EVENTS */}
            <section className="py-14 px-4 bg-white" id="active-events">
                <div className="max-w-7xl mx-auto">
                    <div className="flex items-end justify-between mb-8">
                        <div>
                            <h2 className="text-2xl font-bold text-gray-900">Active Events</h2>
                            <p className="text-sm text-gray-500 mt-1">Event yang sedang tersedia untuk order</p>
                        </div>
                        <Link href={route('events.index')} className="text-sm text-indigo-600 hover:text-indigo-700 font-medium flex items-center gap-1 group transition-colors duration-200 btn-press">
                            Lihat semua
                            <svg className="w-4 h-4 transition-transform duration-200 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </Link>
                    </div>

                    {activeEvents.length === 0 ? (
                        <div className="bg-white border border-gray-200/80 rounded-[32px] min-h-[380px] p-8 flex flex-col items-center justify-center text-center shadow-xs hover:border-indigo-200 transition-colors duration-300">
                            <div className="w-16 h-16 rounded-full bg-indigo-50 border border-indigo-100 flex items-center justify-center mb-4">
                                <svg className="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="1.5" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 001.106 1.789L4.106 14.21A2 2 0 003 16v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 00-1.106-1.789L19.894 11.79A2 2 0 0021 10V7a2 2 0 00-2-2H5z" />
                                </svg>
                            </div>
                            <h3 className="text-gray-900 font-bold text-base mb-1">Belum ada event aktif saat ini</h3>
                            <p className="text-gray-400 text-xs max-w-sm">Event baru yang tersedia untuk dibeli akan muncul di sini secara otomatis.</p>
                        </div>
                    ) : (
                        <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-5">
                            {activeEvents.map((event) => (
                                <EventCard key={event.id} event={event} />
                            ))}
                        </div>
                    )}
                </div>
            </section>

            {/* REALTIME MONITOR */}
            <section className="bg-gray-50/80 border-t border-gray-200/60 py-12 px-4 scroll-mt-20" id="monitor">
                <div className="max-w-7xl mx-auto">
                    <div className="flex items-center gap-3 mb-6">
                        <div className="w-2.5 h-2.5 bg-emerald-500 rounded-full live-indicator"></div>
                        <h2 className="text-lg font-bold text-gray-900">Realtime Success Monitor</h2>
                        <span className="text-xs text-gray-400 ml-auto">Data tersensor untuk privasi pengguna</span>
                    </div>

                    <div className="bg-white border border-gray-200/80 rounded-2xl shadow-xs overflow-hidden">
                        <div className="overflow-x-auto">
                            <table className="w-full text-left text-xs text-gray-600">
                                <thead className="bg-gray-50/90 border-b border-gray-100 text-gray-400 font-bold uppercase tracking-wider text-[11px]">
                                    <tr>
                                        <th className="px-5 py-4">STATUS</th>
                                        <th className="px-5 py-4">EMAIL</th>
                                        <th className="px-5 py-4">EVENT</th>
                                        <th className="px-5 py-4">PHASE</th>
                                        <th className="px-5 py-4">KATEGORI</th>
                                        <th className="px-5 py-4">QTY</th>
                                        <th className="px-5 py-4 text-right">WAKTU</th>
                                    </tr>
                                </thead>
                                <tbody className="divide-y divide-gray-100 bg-white">
                                    {recentSuccess.length ? (
                                        recentSuccess.slice(0, 5).map((log) => (
                                            <tr key={log.id} className="hover:bg-gray-50/70 transition-colors">
                                                <td className="px-5 py-3.5 whitespace-nowrap">
                                                    <span className="bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-semibold px-2.5 py-0.5 rounded-full">
                                                        SUCCESS
                                                    </span>
                                                </td>
                                                <td className="px-5 py-3.5 font-medium text-gray-900 whitespace-nowrap font-mono">
                                                    {log.email}
                                                </td>
                                                <td className="px-5 py-3.5 font-medium text-gray-800 whitespace-nowrap">
                                                    {log.event?.title ?? '-'}
                                                </td>
                                                <td className="px-5 py-3.5 text-gray-600 whitespace-nowrap">
                                                    {log.sale_phase?.name ?? '-'}
                                                </td>
                                                <td className="px-5 py-3.5 text-gray-600 whitespace-nowrap">
                                                    {log.ticket_category?.name ?? '-'}
                                                </td>
                                                <td className="px-5 py-3.5 text-gray-600 whitespace-nowrap">
                                                    <span className="bg-gray-100 text-gray-700 px-2 py-0.5 rounded font-medium">x{log.qty}</span>
                                                </td>
                                                <td className="px-5 py-3.5 text-right text-gray-400 whitespace-nowrap">
                                                    baru saja
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

                    <div className="text-center mt-6">
                        <Link href={route('monitor')} className="text-sm text-indigo-600 hover:text-indigo-700 font-medium transition-colors duration-200 group inline-flex items-center gap-1">
                            Lihat semua di Realtime Monitor
                            <svg className="w-4 h-4 transition-transform duration-200 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </Link>
                    </div>
                </div>
            </section>

            {/* CARA ORDER */}
            <section className="py-14 px-4 bg-white" id="cara-order">
                <div className="max-w-7xl mx-auto">
                    <div className="flex items-center gap-3 mb-6">
                        <div className="w-2 h-2 bg-indigo-500 rounded-full live-indicator"></div>
                        <h2 className="text-lg font-semibold text-gray-900">Cara Order</h2>
                        <span className="text-xs text-gray-500 ml-auto">Langkah order dari awal sampai selesai</span>
                    </div>

                    <div className="grid md:grid-cols-2 lg:grid-cols-4 gap-4">
                        {ORDER_STEPS.map((step) => (
                            <div key={step.num} className="bg-gray-50 rounded-2xl p-5 border border-gray-100/80 transition-all duration-300 hover:bg-white hover:shadow-lg hover:-translate-y-1">
                                <div className="w-8 h-8 rounded-xl bg-indigo-600 text-white font-bold text-sm flex items-center justify-center mb-4">
                                    {step.num}
                                </div>
                                <h3 className="font-semibold text-gray-900 text-sm mb-1">{step.title}</h3>
                                <p className="text-xs text-gray-500 leading-relaxed">{step.desc}</p>
                            </div>
                        ))}
                    </div>
                </div>
            </section>

            {/* FAQ */}
            <section className="py-14 px-4 bg-white" id="faq">
                <div className="max-w-2xl mx-auto">
                    <h2 className="text-2xl font-bold text-gray-900 text-center mb-2">FAQ</h2>
                    <p className="text-gray-500 text-sm text-center mb-8">Pertanyaan yang sering ditanyakan</p>

                    <div className="space-y-3">
                        {FAQS.map((faq, i) => (
                            <div key={faq.q} className="border border-gray-100 rounded-xl overflow-hidden hover-glow transition-all duration-300">
                                <button
                                    type="button"
                                    className="w-full flex items-center justify-between px-5 py-4 text-left hover:bg-gray-50 transition-all duration-200 btn-press"
                                    onClick={() => setOpenFaq(openFaq === i ? null : i)}
                                >
                                    <span className="font-medium text-gray-900 text-sm">{faq.q}</span>
                                    <ChevronIcon open={openFaq === i} />
                                </button>
                                {openFaq === i && (
                                    <div className="px-5 pb-4 text-xs text-gray-500 leading-relaxed border-t border-gray-50 pt-3">
                                        {faq.a}
                                    </div>
                                )}
                            </div>
                        ))}
                    </div>
                </div>
            </section>
        </PublicLayout>
    );
}
