import { useState } from 'react';
import { Link } from '@inertiajs/react';
import PublicLayout from '../../Layouts/PublicLayout';
import EventCard from '../../Components/EventCard';

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
        { value: `${stats.success_rate}%`, label: 'Success Rate', sub: 'Akun sukses' },
        { value: Number(stats.total_accounts).toLocaleString('id-ID'), label: 'Total Accounts', sub: 'Akun yang pernah order' },
        { value: Number(stats.success_accounts).toLocaleString('id-ID'), label: 'Success Accounts', sub: 'Akun yang berhasil' },
        { value: stats.active_events, label: 'Active Events', sub: 'Event berlangsung' },
    ];

    return (
        <PublicLayout title="Wartix Priority Ticket Assistance">
            {/* HERO */}
            <section className="animated-gradient py-10 px-4">
                <div className="max-w-7xl mx-auto grid md:grid-cols-2 gap-10 items-start">
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
                            <div className="bg-white border border-gray-100 rounded-2xl p-5 w-full shadow-sm hover-glow transition-all duration-300">
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
                                    <a href={WHATSAPP_LINK} target="_blank" rel="noopener noreferrer" className="group flex items-center justify-center w-9 h-9 rounded-full bg-white border border-gray-200 text-gray-400 hover:text-white hover:bg-[#25D366] hover:border-[#25D366] transition-all duration-300 hover:scale-110 hover:-translate-y-0.5 shadow-sm hover:shadow-md hover:shadow-emerald-500/20 active:scale-95 btn-press" title="WhatsApp Group">
                                        <i className="fa-brands fa-whatsapp text-base transition-transform duration-300 group-hover:scale-110"></i>
                                    </a>
                                    <a href={X_LINK} target="_blank" rel="noopener noreferrer" className="group flex items-center justify-center w-9 h-9 rounded-full bg-white border border-gray-200 text-gray-400 hover:text-white hover:bg-black hover:border-black transition-all duration-300 hover:scale-110 hover:-translate-y-0.5 shadow-sm hover:shadow-md hover:shadow-black/20 active:scale-95 btn-press" title="X (Twitter)">
                                        <i className="fa-brands fa-x-twitter text-sm transition-transform duration-300 group-hover:scale-110"></i>
                                    </a>
                                    <a href={TIKTOK_LINK} target="_blank" rel="noopener noreferrer" className="group flex items-center justify-center w-9 h-9 rounded-full bg-white border border-gray-200 text-gray-400 hover:text-white hover:bg-zinc-900 hover:border-zinc-900 transition-all duration-300 hover:scale-110 hover:-translate-y-0.5 shadow-sm hover:shadow-md hover:shadow-black/20 active:scale-95 btn-press" title="TikTok">
                                        <i className="fa-brands fa-tiktok text-sm transition-transform duration-300 group-hover:scale-110"></i>
                                    </a>
                                    <span className="w-px h-5 bg-gray-200"></span>
                                    <a href="#" onClick={(e) => e.preventDefault()} className="group flex items-center justify-center w-9 h-9 rounded-full bg-gray-50 border border-gray-150 text-gray-300 cursor-not-allowed opacity-50 transition-all duration-300" title="Instagram (Segera)">
                                        <i className="fa-brands fa-instagram text-base"></i>
                                    </a>
                                    <a href="#" onClick={(e) => e.preventDefault()} className="group flex items-center justify-center w-9 h-9 rounded-full bg-gray-50 border border-gray-150 text-gray-300 cursor-not-allowed opacity-50 transition-all duration-300" title="Threads (Segera)">
                                        <i className="fa-brands fa-threads text-sm"></i>
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
                                <div className="text-3xl font-bold text-indigo-600 mb-0.5">{stat.value}</div>
                                <div className="text-sm font-medium text-gray-900">{stat.label}</div>
                                <div className="text-xs text-gray-400 mt-0.5">{stat.sub}</div>
                            </div>
                        ))}
                    </div>
                </div>
            </section>

            {/* ACTIVE EVENTS */}
            <section className="py-14 px-4" id="active-events">
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
                        <div className="text-center py-16 bg-gray-50 rounded-2xl">
                            <p className="text-gray-400">Belum ada event aktif saat ini.</p>
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
            <section className="bg-gray-900 py-12 px-4" id="monitor">
                <div className="max-w-7xl mx-auto">
                    <div className="flex items-center gap-3 mb-6">
                        <div className="w-2 h-2 bg-green-400 rounded-full live-indicator"></div>
                        <h2 className="text-lg font-semibold text-white">Realtime Success Monitor</h2>
                        <span className="text-xs text-gray-500 ml-auto">Data tersensor untuk privasi pengguna</span>
                    </div>
                    <div className="space-y-2">
                        {recentSuccess.length ? recentSuccess.map((log) => (
                            <div key={log.id} className="flex items-center gap-3 bg-gray-800 rounded-xl px-4 py-2.5 text-sm overflow-x-auto hover:bg-gray-750 transition-colors duration-200">
                                <span className="bg-green-500/20 text-green-400 text-xs font-semibold px-2 py-0.5 rounded flex-shrink-0">SUCCESS</span>
                                <span className="text-white font-medium flex-shrink-0">{log.email}</span>
                                <span className="text-gray-600">|</span>
                                <span className="text-gray-300 flex-shrink-0">{log.event?.title ?? '-'}</span>
                                <span className="text-gray-600">|</span>
                                <span className="text-gray-400 flex-shrink-0">{log.sale_phase?.name ?? '-'}</span>
                                <span className="text-gray-600">|</span>
                                <span className="text-gray-400 flex-shrink-0">{log.ticket_category?.name ?? '-'}</span>
                                <span className="text-gray-600">|</span>
                                <span className="text-gray-400 flex-shrink-0">x{log.qty}</span>
                            </div>
                        )) : (
                            <div className="text-center py-8 text-gray-500 text-sm">
                                Belum ada data sukses. Monitor akan aktif saat event berlangsung.
                            </div>
                        )}
                    </div>
                    <div className="text-center mt-4">
                        <Link href={route('monitor')} className="text-sm text-indigo-400 hover:text-indigo-300 transition-colors duration-200 group inline-flex items-center gap-1">
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

                    <div className="grid md:grid-cols-2 gap-6">
                        <div className="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm hover-glow transition-all duration-300">
                            <div className="flex items-center justify-between mb-4">
                                <span className="text-sm font-semibold text-gray-900">Panduan Order</span>
                                <span className="flex items-center gap-1 text-xs text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-full font-medium">4 Step</span>
                            </div>
                            <div className="space-y-3">
                                {ORDER_STEPS.map((step) => (
                                    <div key={step.num} className="flex gap-3">
                                        <div className="w-7 h-7 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center text-xs font-semibold flex-shrink-0 transition-transform duration-300 hover:scale-110">
                                            {step.num}
                                        </div>
                                        <div>
                                            <p className="text-sm font-medium text-gray-900 mb-0.5">{step.title}</p>
                                            <p className="text-xs text-gray-500 leading-relaxed">{step.desc}</p>
                                        </div>
                                    </div>
                                ))}
                            </div>
                        </div>

                        <div className="bg-gradient-to-br from-indigo-50 via-white to-purple-50 border border-gray-100 rounded-2xl p-5 shadow-sm hover-glow transition-all duration-300">
                            <div className="flex items-center gap-2 mb-4">
                                <span className="text-sm font-semibold text-gray-900">Ringkas Alur</span>
                            </div>
                            <div className="space-y-3">
                                {ORDER_STEPS.map((step) => (
                                    <div key={step.num} className="flex items-start gap-3">
                                        <div className="mt-0.5 w-6 h-6 rounded-full bg-white border border-indigo-100 text-indigo-600 flex items-center justify-center text-[11px] font-semibold flex-shrink-0 transition-all duration-300 hover:bg-indigo-50 hover:scale-110">
                                            {step.num}
                                        </div>
                                        <div>
                                            <p className="text-sm font-medium text-gray-900">{step.title}</p>
                                            <p className="text-xs text-gray-500 leading-relaxed">{step.desc}</p>
                                        </div>
                                    </div>
                                ))}
                            </div>
                        </div>
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
                                    <span className="text-sm font-medium text-gray-900">{faq.q}</span>
                                    <ChevronIcon open={openFaq === i} />
                                </button>
                                {openFaq === i && (
                                    <div className="px-5 pb-4">
                                        <p className="text-sm text-gray-500 leading-relaxed">{faq.a}</p>
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
