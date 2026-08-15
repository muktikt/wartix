import { useMemo, useState } from 'react';
import { useForm, Link, Head } from '@inertiajs/react';
import PublicLayout from '../../../Layouts/PublicLayout';
import { formatDate, formatRp } from '../../../utils/format';

const STATUS_LABEL = {
    ongoing: { label: 'Proses', className: 'bg-green-50 text-green-700' },
    slot_penuh: { label: 'Slot Penuh', className: 'bg-rose-50 text-rose-700' },
    finished: { label: 'Finished', className: 'bg-gray-100 text-gray-500' },
    upcoming: { label: 'Upcoming', className: 'bg-indigo-50 text-indigo-700' },
};

function TcModal({ onClose }) {
    return (
        <div className="fixed inset-0 z-[9999] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm transition-all duration-300">
            <div className="bg-white rounded-2xl max-w-sm w-full p-6 shadow-2xl">
                <div className="flex items-center justify-center w-12 h-12 mx-auto mb-4 bg-amber-50 rounded-full text-amber-500">
                    <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <h3 className="text-center text-sm font-bold text-gray-900 mb-2">Pemberitahuan Penting</h3>
                <p className="text-center text-xs text-gray-500 leading-relaxed mb-6">
                    Harap membaca deskripsi event terlebih dahulu karena di dalamnya terdapat Syarat &amp; Ketentuan (Terms and Conditions) sebelum melakukan pemesanan.
                </p>
                <button onClick={onClose} className="w-full bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold py-2.5 rounded-xl transition-colors">
                    Oke, Saya Mengerti
                </button>
            </div>
        </div>
    );
}

export default function EventShow({ event, totalSlots, availableSlots, eventStats, activeOrder, fieldConfig }) {
    const [showTcModal, setShowTcModal] = useState(event.status === 'upcoming');
    const [categoryChoices, setCategoryChoices] = useState([{ ticket_category_id: '' }]);
    const [guestNiks, setGuestNiks] = useState({});
    const [clientError, setClientError] = useState('');

    const hasMembershipPhase = event.sale_phases?.some((p) => p.name.toLowerCase().includes('membership'));

    const form = useForm({
        event_id: event.id,
        sale_phase_id: '',
        membership_code: '',
        qty: 1,
        title: '',
        gender: '',
        birth_date: '',
        city: '',
        payment_method: '',
        full_name: '',
        phone_number: '',
        email: '',
        identity_number: '',
        telegram_username: '',
        social_media_screenshot: null,
        custom_fields: {},
    });

    const selectedPhase = event.sale_phases?.find((p) => String(p.id) === String(form.data.sale_phase_id));
    const showMembershipField = selectedPhase && selectedPhase.name.toLowerCase().includes('membership');

    const primaryCategory = event.ticket_categories?.find((c) => String(c.id) === String(categoryChoices[0]?.ticket_category_id));

    const estimate = useMemo(() => {
        if (!primaryCategory) return null;
        const qty = Number(form.data.qty) || 1;
        const totalFee = primaryCategory.fee_per_ticket * qty;
        const totalPrice = (primaryCategory.ticket_price || 0) * qty;
        const showPrice = primaryCategory.payment_mode === 'full_payment' && primaryCategory.ticket_price > 0;
        return {
            fee: totalFee,
            price: totalPrice,
            showPrice,
            grandTotal: showPrice ? totalFee + totalPrice : totalFee,
        };
    }, [primaryCategory, form.data.qty]);

    function addBackupCategory() {
        setCategoryChoices([...categoryChoices, { ticket_category_id: '' }]);
    }

    function removeBackupCategory(index) {
        setCategoryChoices(categoryChoices.filter((_, i) => i !== index));
    }

    function updateBackupCategory(index, value) {
        const next = [...categoryChoices];
        next[index] = { ticket_category_id: value };
        setCategoryChoices(next);
    }

    const platformFields = {
        title: fieldConfig.platformsWithTitle.includes(event.platform_type),
        gender: fieldConfig.platformsWithGender.includes(event.platform_type),
        birthDate: fieldConfig.platformsWithBirthDate.includes(event.platform_type),
        city: fieldConfig.platformsWithCity.includes(event.platform_type),
        paymentMethod: fieldConfig.platformsWithPaymentMethod.includes(event.platform_type),
    };

    const activeCustomFields = (event.custom_fields ?? []).filter((f) => f.is_active);
    const qty = Number(form.data.qty) || 1;
    const guestSlots = event.guest_enabled && event.guest_mode === 'multi_guest'
        ? Array.from({ length: Math.max(0, qty - 1) }, (_, i) => i + 2)
        : [];

    function submit(e) {
        e.preventDefault();

        // Client-side sanity check for the pieces React state manages
        // outside form.data (category choices, guest NIKs).
        if (!categoryChoices[0]?.ticket_category_id) {
            setClientError('Mohon pilih kategori tiket utama.');
            return;
        }
        if (guestSlots.some((i) => !guestNiks[i] || guestNiks[i].trim().length !== 16)) {
            setClientError('Mohon lengkapi NIK (16 digit) untuk semua tiket tambahan.');
            return;
        }
        setClientError('');

        form.transform((data) => ({
            ...data,
            category_choices: categoryChoices
                .filter((c) => c.ticket_category_id)
                .map((c, i) => ({ ticket_category_id: c.ticket_category_id, priority: i + 1 })),
            ...Object.fromEntries(guestSlots.map((i) => [`guest_nik_${i}`, guestNiks[i] || ''])),
        }));

        form.post(route('orders.store'), { forceFormData: true });
    }

    const badge = STATUS_LABEL[event.status] ?? STATUS_LABEL.upcoming;

    const eventSchema = {
        '@context': 'https://schema.org',
        '@type': 'Event',
        'name': event.title,
        'description': event.description || `Priority Ticket Assistance untuk ${event.title}`,
        'startDate': event.event_date ? new Date(event.event_date).toISOString() : undefined,
        'eventStatus': 'https://schema.org/EventScheduled',
        'eventAttendanceMode': 'https://schema.org/OfflineEventAttendanceMode',
        'location': {
            '@type': 'Place',
            'name': event.venue || 'Venue',
            'address': {
                '@type': 'PostalAddress',
                'addressLocality': event.city || 'Indonesia',
                'addressCountry': 'ID'
            }
        },
        'image': event.banner_image ? [`/storage/${event.banner_image}`] : undefined,
        'performer': event.artist_name ? {
            '@type': 'PerformingGroup',
            'name': event.artist_name
        } : undefined,
        'organizer': {
            '@type': 'Organization',
            'name': 'Warindong',
            'url': 'https://warindong.com'
        },
        'offers': event.ticket_categories?.map(cat => ({
            '@type': 'Offer',
            'name': cat.name,
            'price': cat.fee_per_ticket || 0,
            'priceCurrency': 'IDR',
            'availability': availableSlots > 0 ? 'https://schema.org/InStock' : 'https://schema.org/SoldOut'
        }))
    };

    return (
        <PublicLayout title={event.title}>
            <Head>
                <title>{`${event.title} - Priority Ticket Assistance | Warindong`}</title>
                <meta name="description" content={`Dapatkan jasa war tiket & priority assistance untuk ${event.title} (${event.artist_name || ''}) di ${event.venue || ''}, ${event.city || ''}.`} />
                <meta property="og:title" content={`${event.title} - Warindong`} />
                <meta property="og:description" content={`Jasa war tiket & priority assistance untuk ${event.title}.`} />
                {event.banner_image && <meta property="og:image" content={`/storage/${event.banner_image}`} />}
                <script type="application/ld+json">
                    {JSON.stringify(eventSchema)}
                </script>
            </Head>
            <div className="max-w-5xl mx-auto px-4 py-8">
                <div className="grid md:grid-cols-3 gap-8 md:h-[calc(100vh-8rem)] md:items-stretch">

                    {/* Left */}
                    <div className="md:col-span-2 space-y-6 md:h-full md:overflow-y-auto md:pr-4">
                        <div className="rounded-2xl overflow-hidden bg-gradient-to-br from-indigo-900 to-purple-900 aspect-video flex items-center justify-center">
                            {event.banner_image ? (
                                <img src={`/storage/${event.banner_image}`} loading="lazy" decoding="async" className="w-full h-full object-cover" alt={event.title} />
                            ) : (
                                <span className="text-white/50 text-lg font-medium">{event.title}</span>
                            )}
                        </div>

                        <div className="bg-white border border-gray-100 rounded-2xl p-5">
                            <div className="flex items-start justify-between mb-3">
                                <h1 className="text-xl font-bold text-gray-900">{event.title}</h1>
                                <span className={`text-xs px-2.5 py-1 rounded-full font-medium ${badge.className}`}>
                                    {event.status === 'ongoing' ? 'Proses' : event.status === 'slot_penuh' ? 'Slot Penuh' : badge.label}
                                </span>
                            </div>
                            <div className="grid grid-cols-2 gap-3 text-sm mb-4">
                                <div className="flex items-center gap-2 text-gray-500">
                                    <svg className="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    </svg>
                                    {event.venue}, {event.city}
                                </div>
                                <div className="flex items-center gap-2 text-gray-500">
                                    <svg className="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    {formatDate(event.event_date)}
                                </div>
                            </div>

                            {event.status !== 'finished' && totalSlots !== null && (
                                <div className="mb-4 p-3 bg-gradient-to-br from-indigo-50 to-indigo-100/40 border border-indigo-100/60 rounded-xl flex items-center justify-between">
                                    <div>
                                        <span className="text-[10px] font-semibold text-indigo-500 uppercase tracking-wider block">Slot Tersedia</span>
                                        <div className="flex items-baseline gap-1">
                                            <span className="text-lg font-bold text-indigo-900">{availableSlots}</span>
                                            <span className="text-xs font-medium text-indigo-400">/ {totalSlots} tiket</span>
                                        </div>
                                    </div>
                                    <div className={`inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-semibold ${availableSlots > 0 ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200'}`}>
                                        <span className={`w-1.5 h-1.5 rounded-full ${availableSlots > 0 ? 'bg-emerald-500' : 'bg-rose-500'} animate-pulse`}></span>
                                        {availableSlots > 0 ? 'Tersedia' : 'Penuh'}
                                    </div>
                                </div>
                            )}

                            {event.description && (
                                <p className="text-sm text-gray-500 leading-relaxed whitespace-pre-line">{event.description}</p>
                            )}
                        </div>

                        {event.seatplan_image && (
                            <div className="bg-white border border-gray-100 rounded-2xl p-5">
                                <h3 className="text-sm font-semibold text-gray-900 mb-3">Denah Tempat Duduk</h3>
                                <img src={`/storage/${event.seatplan_image}`} loading="lazy" decoding="async" className="w-full rounded-xl" alt="Seatplan" />
                            </div>
                        )}

                        <div className="bg-white border border-gray-100 rounded-2xl p-5">
                            <h3 className="text-sm font-semibold text-gray-900 mb-4">Sale Phase &amp; Kategori</h3>
                            {event.sale_phases?.length > 0 && (
                                <div className="flex flex-wrap gap-2 mb-4">
                                    {event.sale_phases.map((phase) => (
                                        <span key={phase.id} className="text-xs bg-indigo-50 text-indigo-700 px-3 py-1 rounded-full font-medium">{phase.name}</span>
                                    ))}
                                </div>
                            )}
                            <div className="space-y-2">
                                {event.ticket_categories?.map((cat) => (
                                    <div key={cat.id} className="flex items-center justify-between gap-4 p-3 bg-gray-50 rounded-xl">
                                        <span className="text-xs sm:text-sm font-medium text-gray-900 leading-tight">{cat.name}</span>
                                        <span className="text-xs sm:text-sm font-semibold text-indigo-600 flex-shrink-0 whitespace-nowrap text-right">{formatRp(cat.fee_per_ticket)}/tiket</span>
                                    </div>
                                ))}
                            </div>
                        </div>
                    </div>

                    {/* Right Column */}
                    <div className="md:col-span-1 md:h-full md:overflow-y-auto md:pr-2">
                        {event.status === 'finished' ? (
                            <div className="bg-white border border-gray-100 rounded-2xl p-5">
                                <div className="flex items-center gap-2 mb-5">
                                    <h3 className="text-sm font-semibold text-gray-900">Hasil Event</h3>
                                    <span className="ml-auto text-xs bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full font-medium">Finished</span>
                                </div>
                                <div className="space-y-3 mb-5">
                                    <div className="bg-indigo-50 rounded-xl p-4">
                                        <span className="text-[10px] font-semibold text-indigo-500 uppercase tracking-wider block mb-1">Total Akun Order</span>
                                        <span className="text-2xl font-bold text-indigo-900">{Number(eventStats.total_accounts).toLocaleString('id-ID')}</span>
                                        <span className="text-xs text-indigo-400 ml-1">akun</span>
                                    </div>
                                    <div className="bg-green-50 rounded-xl p-4">
                                        <span className="text-[10px] font-semibold text-green-500 uppercase tracking-wider block mb-1">Akun Berhasil</span>
                                        <span className="text-2xl font-bold text-green-900">{Number(eventStats.success_accounts).toLocaleString('id-ID')}</span>
                                        <span className="text-xs text-green-400 ml-1">akun</span>
                                    </div>
                                    <div className="bg-gradient-to-br from-amber-50 to-orange-50 rounded-xl p-4">
                                        <span className="text-[10px] font-semibold text-amber-500 uppercase tracking-wider block mb-1">Success Rate</span>
                                        <div className="flex items-baseline gap-1">
                                            <span className="text-2xl font-bold text-amber-900">{eventStats.success_rate}</span>
                                            <span className="text-sm font-semibold text-amber-600">%</span>
                                        </div>
                                        <div className="mt-2 h-2 bg-amber-100 rounded-full overflow-hidden">
                                            <div
                                                className={`h-full rounded-full transition-all duration-500 ${eventStats.success_rate >= 80 ? 'bg-green-500' : eventStats.success_rate >= 50 ? 'bg-amber-500' : 'bg-red-400'}`}
                                                style={{ width: `${Math.min(eventStats.success_rate, 100)}%` }}
                                            ></div>
                                        </div>
                                    </div>
                                </div>
                                <div className="text-center py-3 bg-gray-50 rounded-xl">
                                    <p className="text-xs text-gray-400">Event ini telah selesai.</p>
                                    <p className="text-xs text-gray-400">Terima kasih atas partisipasi Anda!</p>
                                </div>
                            </div>
                        ) : event.status === 'slot_penuh' ? (
                            <div className="bg-white border border-gray-100 rounded-2xl p-6 text-center">
                                <div className="w-16 h-16 bg-rose-50 rounded-full flex items-center justify-center mx-auto mb-4 text-rose-500">
                                    <svg className="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                </div>
                                <h3 className="text-base font-bold text-gray-900 mb-1">Slot Penuh</h3>
                                <p className="text-xs text-gray-500 leading-relaxed mb-6">
                                    Mohon maaf, kuota tiket untuk event ini telah terpenuhi dan pendaftaran telah ditutup.
                                </p>
                                <Link href={route('events.index')} className="inline-flex justify-center w-full bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold py-2.5 rounded-xl transition-colors">
                                    Lihat Event Lainnya
                                </Link>
                            </div>
                        ) : event.status === 'ongoing' ? (
                            <div className="bg-white border border-gray-100 rounded-2xl p-6 text-center">
                                <div className="w-16 h-16 bg-amber-50 rounded-full flex items-center justify-center mx-auto mb-4 text-amber-500">
                                    <svg className="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                </div>
                                <h3 className="text-base font-bold text-gray-900 mb-1">Event Berlangsung</h3>
                                <p className="text-xs text-gray-500 leading-relaxed mb-6">
                                    Pendaftaran telah ditutup karena event saat ini sedang berlangsung (Proses).
                                </p>
                                <Link href={route('events.index')} className="inline-flex justify-center w-full bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold py-2.5 rounded-xl transition-colors">
                                    Lihat Event Lainnya
                                </Link>
                            </div>
                        ) : activeOrder ? (
                            <div className="bg-white border border-indigo-100 rounded-2xl p-6 text-center shadow-sm">
                                <div className="w-16 h-16 bg-indigo-50 rounded-full flex items-center justify-center mx-auto mb-4 text-indigo-600">
                                    <svg className="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <h3 className="text-base font-bold text-gray-900 mb-1">Sudah Melakukan Order</h3>
                                <p className="text-xs text-gray-500 leading-relaxed mb-4">
                                    Perangkat Anda telah memiliki order aktif untuk event ini (Kode Order: <span className="font-mono font-semibold text-indigo-600">{activeOrder.order_code}</span>).
                                </p>
                                <div className="p-3 bg-gray-50 rounded-xl text-xs text-gray-600 mb-5">
                                    Status saat ini: <span className="font-semibold text-indigo-600">{activeOrder.order_status.charAt(0).toUpperCase() + activeOrder.order_status.slice(1)}</span>
                                </div>
                                <Link href={route('order.success', activeOrder.order_code)} className="inline-flex justify-center w-full bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold py-2.5 rounded-xl transition-colors mb-2">
                                    Lihat Detail Order Anda
                                </Link>
                                <p className="text-[11px] text-gray-400">
                                    Jika order Anda dibatalkan/di-cancel oleh admin, Anda dapat melakukan pemesanan ulang untuk event ini.
                                </p>
                            </div>
                        ) : (
                            <div className="bg-white border border-gray-100 rounded-2xl p-5">
                                <h3 className="text-sm font-semibold text-gray-900 mb-4">Form Order</h3>

                                <form onSubmit={submit} encType="multipart/form-data">
                                    {(clientError || Object.keys(form.errors).length > 0) && (
                                        <div className="mb-4 p-4 bg-red-50 border-l-4 border-red-500 rounded-xl">
                                            <h3 className="text-xs font-semibold text-red-800 mb-1">Ada kesalahan pengisian form:</h3>
                                            <ul className="list-disc list-inside text-xs text-red-700 space-y-0.5">
                                                {clientError && <li>{clientError}</li>}
                                                {Object.values(form.errors).map((msg, i) => <li key={i}>{msg}</li>)}
                                            </ul>
                                        </div>
                                    )}

                                    {/* Sale Phase */}
                                    <div className="mb-3">
                                        <label className="block text-xs font-medium text-gray-700 mb-1.5">Sale Phase</label>
                                        <select
                                            required
                                            value={form.data.sale_phase_id}
                                            onChange={(e) => form.setData('sale_phase_id', e.target.value)}
                                            className="w-full text-sm border border-gray-200 rounded-xl px-3 py-2.5 bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                        >
                                            <option value="">Pilih sale phase</option>
                                            {event.sale_phases?.map((phase) => (
                                                phase.slot_limit !== null ? (
                                                    phase.available_slots > 0 ? (
                                                        <option key={phase.id} value={phase.id}>{phase.name} (Sisa {phase.available_slots} slot)</option>
                                                    ) : (
                                                        <option key={phase.id} value={phase.id} disabled className="text-gray-400">{phase.name} (Penuh)</option>
                                                    )
                                                ) : (
                                                    <option key={phase.id} value={phase.id}>{phase.name}</option>
                                                )
                                            ))}
                                        </select>
                                    </div>

                                    {hasMembershipPhase && showMembershipField && (
                                        <div className="mb-3">
                                            <label className="block text-xs font-medium text-gray-700 mb-1.5">Kode Membership <span className="text-red-500">*</span></label>
                                            <input
                                                type="text"
                                                value={form.data.membership_code}
                                                onChange={(e) => form.setData('membership_code', e.target.value)}
                                                placeholder="Masukkan kode membership Anda"
                                                className="w-full text-sm border border-gray-200 rounded-xl px-3 py-2.5 bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                            />
                                        </div>
                                    )}

                                    {/* Ticket Category */}
                                    <div className="mb-4">
                                        <label className="block text-xs font-medium text-gray-700 mb-1.5">Kategori Tiket</label>
                                        <div className="space-y-2">
                                            <div>
                                                <label className="block text-xs text-gray-500 mb-1">Pilihan Utama <span className="text-red-500">*</span></label>
                                                <select
                                                    required
                                                    value={categoryChoices[0]?.ticket_category_id || ''}
                                                    onChange={(e) => updateBackupCategory(0, e.target.value)}
                                                    className="w-full text-sm border border-gray-200 rounded-xl px-3 py-2.5 bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                                >
                                                    <option value="">Pilih kategori utama</option>
                                                    {event.ticket_categories?.map((cat) => (
                                                        <option key={cat.id} value={cat.id}>{cat.name} — {formatRp(cat.fee_per_ticket)}/tiket</option>
                                                    ))}
                                                </select>
                                            </div>

                                            {categoryChoices.slice(1).map((choice, i) => (
                                                <div key={i + 1} className="flex gap-2 items-end mt-2">
                                                    <div className="flex-1">
                                                        <label className="block text-xs text-gray-500 mb-1">Cadangan {i + 1}</label>
                                                        <select
                                                            value={choice.ticket_category_id}
                                                            onChange={(e) => updateBackupCategory(i + 1, e.target.value)}
                                                            className="w-full text-sm border border-gray-200 rounded-xl px-3 py-2.5 bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                                        >
                                                            <option value="">Pilih kategori cadangan</option>
                                                            {event.ticket_categories?.map((cat) => (
                                                                <option key={cat.id} value={cat.id}>{cat.name} — {formatRp(cat.fee_per_ticket)}/tiket</option>
                                                            ))}
                                                        </select>
                                                    </div>
                                                    <button
                                                        type="button"
                                                        onClick={() => removeBackupCategory(i + 1)}
                                                        className="mb-0.5 text-red-400 hover:text-red-600 text-xs px-2 py-2.5 border border-red-100 rounded-xl hover:bg-red-50 transition-colors"
                                                    >
                                                        Hapus
                                                    </button>
                                                </div>
                                            ))}
                                        </div>

                                        <button type="button" onClick={addBackupCategory} className="w-full mt-2 border border-dashed border-indigo-300 text-indigo-600 text-xs py-2 rounded-xl hover:bg-indigo-50 transition-colors">
                                            + Tambah Kategori Cadangan
                                        </button>
                                        <p className="text-xs text-gray-400 mt-2">Fee final sesuai kategori yang berhasil. QRIS dikirim otomatis setelah sukses.</p>
                                    </div>

                                    {/* Qty */}
                                    <div className="mb-4">
                                        <label className="block text-xs font-medium text-gray-700 mb-1.5">Jumlah Tiket</label>
                                        <select
                                            required
                                            value={form.data.qty}
                                            onChange={(e) => form.setData('qty', e.target.value)}
                                            className="w-full text-sm border border-gray-200 rounded-xl px-3 py-2.5 bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                        >
                                            {Array.from({ length: event.max_ticket_per_order }, (_, i) => i + 1).map((n) => (
                                                <option key={n} value={n}>{n} Tiket</option>
                                            ))}
                                        </select>
                                    </div>

                                    {estimate && (
                                        <div className="mb-4 bg-indigo-50 rounded-xl p-3">
                                            <div className="flex justify-between text-xs text-gray-600 mb-1">
                                                <span>Fee Jasa</span>
                                                <span>{formatRp(estimate.fee)}</span>
                                            </div>
                                            {estimate.showPrice && (
                                                <div className="flex justify-between text-xs text-gray-600 mb-1">
                                                    <span>Harga Tiket</span>
                                                    <span>{formatRp(estimate.price)}</span>
                                                </div>
                                            )}
                                            <div className="flex justify-between text-sm font-semibold text-indigo-700 border-t border-indigo-100 pt-1 mt-1">
                                                <span>Total</span>
                                                <span>{formatRp(estimate.grandTotal)}</span>
                                            </div>
                                        </div>
                                    )}

                                    <hr className="border-gray-100 mb-4" />

                                    {platformFields.title && (
                                        <div className="mb-3">
                                            <label className="block text-xs font-medium text-gray-700 mb-1.5">Gelar <span className="text-red-500">*</span></label>
                                            <select required value={form.data.title} onChange={(e) => form.setData('title', e.target.value)} className="w-full text-sm border border-gray-200 rounded-xl px-3 py-2.5 bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                                <option value="">Pilih gelar</option>
                                                {Object.entries(fieldConfig.titleOptions).map(([value, label]) => (
                                                    <option key={value} value={value}>{label}</option>
                                                ))}
                                            </select>
                                        </div>
                                    )}

                                    {platformFields.gender && (
                                        <div className="mb-3">
                                            <label className="block text-xs font-medium text-gray-700 mb-1.5">Gender <span className="text-red-500">*</span></label>
                                            <select required value={form.data.gender} onChange={(e) => form.setData('gender', e.target.value)} className="w-full text-sm border border-gray-200 rounded-xl px-3 py-2.5 bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                                <option value="">Pilih gender</option>
                                                {Object.entries(fieldConfig.genderOptions).map(([value, label]) => (
                                                    <option key={value} value={value}>{label}</option>
                                                ))}
                                            </select>
                                        </div>
                                    )}

                                    {platformFields.birthDate && (
                                        <div className="mb-3">
                                            <label className="block text-xs font-medium text-gray-700 mb-1.5">Tanggal Lahir <span className="text-red-500">*</span></label>
                                            <input
                                                type="date"
                                                required
                                                value={form.data.birth_date}
                                                onChange={(e) => form.setData('birth_date', e.target.value)}
                                                max={new Date(Date.now() - 86400000).toISOString().slice(0, 10)}
                                                className="w-full text-sm border border-gray-200 rounded-xl px-3 py-2.5 bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                            />
                                        </div>
                                    )}

                                    {platformFields.city && (
                                        <div className="mb-3">
                                            <label className="block text-xs font-medium text-gray-700 mb-1.5">Kota <span className="text-red-500">*</span></label>
                                            <input
                                                type="text"
                                                required
                                                value={form.data.city}
                                                onChange={(e) => form.setData('city', e.target.value)}
                                                placeholder="Kota domisili"
                                                className="w-full text-sm border border-gray-200 rounded-xl px-3 py-2.5 bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                            />
                                        </div>
                                    )}

                                    {platformFields.paymentMethod && (
                                        <div className="mb-3">
                                            <label className="block text-xs font-medium text-gray-700 mb-1.5">Metode Pembayaran <span className="text-red-500">*</span></label>
                                            <select required value={form.data.payment_method} onChange={(e) => form.setData('payment_method', e.target.value)} className="w-full text-sm border border-gray-200 rounded-xl px-3 py-2.5 bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                                <option value="">Pilih metode pembayaran</option>
                                                {Object.values(fieldConfig.paymentMethodGroups).flatMap((options) => Object.entries(options)).map(([value, label]) => (
                                                    <option key={value} value={value}>{label}</option>
                                                ))}
                                            </select>
                                        </div>
                                    )}

                                    <div className="mb-3">
                                        <label className="block text-xs font-medium text-gray-700 mb-1.5">Nama Lengkap</label>
                                        <input type="text" required value={form.data.full_name} onChange={(e) => form.setData('full_name', e.target.value)} placeholder="Sesuai KTP" className="w-full text-sm border border-gray-200 rounded-xl px-3 py-2.5 bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                                    </div>

                                    <div className="mb-3">
                                        <label className="block text-xs font-medium text-gray-700 mb-1.5">Nomor Ponsel</label>
                                        <input type="tel" required value={form.data.phone_number} onChange={(e) => form.setData('phone_number', e.target.value)} placeholder="08xxxxxxxxxx" className="w-full text-sm border border-gray-200 rounded-xl px-3 py-2.5 bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                                    </div>

                                    <div className="mb-3">
                                        <label className="block text-xs font-medium text-gray-700 mb-1.5">Email</label>
                                        <input type="email" required value={form.data.email} onChange={(e) => form.setData('email', e.target.value)} placeholder="email@example.com" className="w-full text-sm border border-gray-200 rounded-xl px-3 py-2.5 bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                                    </div>

                                    {event.identity_mode === 'nik_only' && (
                                        <div className="mb-3">
                                            <label className="block text-xs font-medium text-gray-700 mb-1.5">Nomor KTP / NIK</label>
                                            <input type="text" required minLength={16} maxLength={16} value={form.data.identity_number} onChange={(e) => form.setData('identity_number', e.target.value)} placeholder="16 digit NIK" className="w-full text-sm border border-gray-200 rounded-xl px-3 py-2.5 bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                                        </div>
                                    )}

                                    <div className="mb-3">
                                        <label className="block text-xs font-medium text-gray-700 mb-1.5">Username Instagram <span className="text-red-500">*</span></label>
                                        <div className="relative">
                                            <span className="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">@</span>
                                            <input type="text" required value={form.data.telegram_username} onChange={(e) => form.setData('telegram_username', e.target.value)} placeholder="username" className="w-full text-sm border border-gray-200 rounded-xl pl-7 pr-3 py-2.5 bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                                        </div>
                                        <p className="text-xs text-gray-400 mt-1">Wajib akun Instagram (bukan TikTok/X/Threads)</p>
                                    </div>

                                    <div className="mb-4">
                                        <label className="block text-xs font-medium text-gray-700 mb-1.5">Screenshot Akun Instagram <span className="text-red-500">*</span></label>
                                        <input
                                            type="file"
                                            required
                                            accept="image/*"
                                            onChange={(e) => form.setData('social_media_screenshot', e.target.files[0])}
                                            className="w-full text-sm border border-gray-200 rounded-xl px-3 py-2.5 bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                        />
                                        <p className="text-xs text-gray-400 mt-1">Screenshot profil Instagram kamu untuk verifikasi (JPG/PNG, maks 2MB)</p>
                                    </div>

                                    {activeCustomFields.length > 0 && (
                                        <>
                                            <hr className="border-gray-100 mb-4" />
                                            {activeCustomFields.map((field) => (
                                                <div key={field.id} className="mb-3">
                                                    <label className="block text-xs font-medium text-gray-700 mb-1.5">
                                                        {field.label} {field.is_required && <span className="text-red-500">*</span>}
                                                    </label>
                                                    {field.field_type === 'select' ? (
                                                        <select
                                                            required={field.is_required}
                                                            value={form.data.custom_fields[field.id] || ''}
                                                            onChange={(e) => form.setData('custom_fields', { ...form.data.custom_fields, [field.id]: e.target.value })}
                                                            className="w-full text-sm border border-gray-200 rounded-xl px-3 py-2.5 bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                                        >
                                                            <option value="">Pilih {field.label}</option>
                                                            {(field.options ?? []).map((opt) => <option key={opt} value={opt}>{opt}</option>)}
                                                        </select>
                                                    ) : field.field_type === 'textarea' ? (
                                                        <textarea
                                                            rows={3}
                                                            required={field.is_required}
                                                            value={form.data.custom_fields[field.id] || ''}
                                                            onChange={(e) => form.setData('custom_fields', { ...form.data.custom_fields, [field.id]: e.target.value })}
                                                            className="w-full text-sm border border-gray-200 rounded-xl px-3 py-2.5 bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                                        />
                                                    ) : (
                                                        <input
                                                            type={field.field_type === 'password' ? 'password' : field.field_type === 'number' ? 'number' : 'text'}
                                                            required={field.is_required}
                                                            value={form.data.custom_fields[field.id] || ''}
                                                            onChange={(e) => form.setData('custom_fields', { ...form.data.custom_fields, [field.id]: e.target.value })}
                                                            className="w-full text-sm border border-gray-200 rounded-xl px-3 py-2.5 bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                                        />
                                                    )}
                                                </div>
                                            ))}
                                        </>
                                    )}

                                    {guestSlots.length > 0 && (
                                        <div className="mb-4">
                                            <hr className="border-gray-100 mb-4" />
                                            <p className="text-xs font-semibold text-gray-700 mb-3">Data Guest Tambahan</p>
                                            <div className="space-y-2">
                                                {guestSlots.map((i) => (
                                                    <div key={i}>
                                                        <label className="block text-xs text-gray-600 mb-1">Tiket {i} — Nomor KTP / NIK</label>
                                                        <input
                                                            type="text"
                                                            required
                                                            minLength={16}
                                                            maxLength={16}
                                                            value={guestNiks[i] || ''}
                                                            onChange={(e) => setGuestNiks({ ...guestNiks, [i]: e.target.value })}
                                                            placeholder="16 digit NIK"
                                                            className="w-full text-sm border border-gray-200 rounded-xl px-3 py-2 bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                                        />
                                                    </div>
                                                ))}
                                            </div>
                                        </div>
                                    )}

                                    <button type="submit" disabled={form.processing} className="w-full bg-indigo-600 hover:bg-indigo-700 disabled:opacity-60 text-white text-sm font-semibold py-3 rounded-xl transition-colors">
                                        {form.processing ? 'Mengirim...' : 'Submit Order'}
                                    </button>

                                    <p className="text-xs text-gray-400 text-center mt-3">
                                        Dengan submit, kamu menyetujui syarat &amp; ketentuan Warindong
                                    </p>
                                </form>
                            </div>
                        )}
                    </div>
                </div>
            </div>

            {showTcModal && <TcModal onClose={() => setShowTcModal(false)} />}
        </PublicLayout>
    );
}
