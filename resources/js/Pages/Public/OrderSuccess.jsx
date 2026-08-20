import { Link } from '@inertiajs/react';
import PublicLayout from '../../Layouts/PublicLayout';
import { formatRp } from '../../utils/format';

export default function OrderSuccess({ order, telegramLinkUrl, telegramGroupLink }) {
    const categoryName = order?.ticket_category?.name
        ?? order?.category_choices?.[0]?.ticket_category?.name
        ?? '-';

    const isCancelled = order?.order_status === 'cancelled';
    const homeUrl = typeof route === 'function' ? route('home') : '/';

    return (
        <PublicLayout title="Order Berhasil Warindong" hideNav hideFooter hideFloating>
            <div className="min-h-screen flex items-center justify-center px-4 py-8 sm:py-12">
                <div className="max-w-md w-full">
                    <div className="bg-white border border-gray-100 rounded-3xl p-6 sm:p-7 text-center shadow-lg shadow-gray-200/50">
                        {isCancelled ? (
                            <div className="w-14 h-14 bg-red-50 text-red-500 rounded-full flex items-center justify-center mx-auto mb-4 border border-red-100">
                                <svg className="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </div>
                        ) : (
                            <div className="w-14 h-14 bg-green-50 text-green-500 rounded-full flex items-center justify-center mx-auto mb-4 border border-green-100">
                                <svg className="w-7 h-7 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                        )}

                        <h1 className="text-lg sm:text-xl font-bold text-gray-900 mb-1">
                            {isCancelled ? 'Order Dibatalkan' : 'Order Berhasil Dikirim!'}
                        </h1>
                        <p className="text-xs sm:text-sm text-gray-500 mb-5 leading-relaxed">
                            {isCancelled 
                                ? 'Waktu konfirmasi Telegram telah berakhir (10 menit) sehingga order dibatalkan otomatis.'
                                : 'Tim Warindong akan segera memproses order kamu.'
                            }
                        </p>

                        <div className="bg-gray-50 border border-gray-100 rounded-2xl p-4 text-left mb-5 space-y-2.5">
                            <div className="flex justify-between items-center text-sm gap-3">
                                <span className="text-gray-500 text-xs sm:text-sm shrink-0">Order Code</span>
                                <span className="font-semibold text-gray-900 font-mono text-xs sm:text-sm bg-white px-2 py-0.5 rounded border border-gray-200">
                                    {order?.order_code || '-'}
                                </span>
                            </div>

                            <div className="flex justify-between items-start text-sm gap-3">
                                <span className="text-gray-500 text-xs sm:text-sm shrink-0 min-w-[70px]">Event</span>
                                <span className="font-medium text-gray-800 text-right text-xs sm:text-sm break-words flex-1 leading-snug">
                                    {order?.event?.title || '-'}
                                </span>
                            </div>

                            <div className="flex justify-between items-start text-sm gap-3">
                                <span className="text-gray-500 text-xs sm:text-sm shrink-0 min-w-[70px]">Sale Phase</span>
                                <span className="font-medium text-gray-800 text-right text-xs sm:text-sm break-words flex-1 leading-snug">
                                    {order?.sale_phase?.name ?? '-'}
                                </span>
                            </div>

                            <div className="flex justify-between items-start text-sm gap-3">
                                <span className="text-gray-500 text-xs sm:text-sm shrink-0 min-w-[70px]">Kategori</span>
                                <span className="font-medium text-gray-800 text-right text-xs sm:text-sm break-words flex-1">
                                    {categoryName} x{order?.qty || 1}
                                </span>
                            </div>

                            <div className="flex justify-between items-center text-sm border-t border-gray-200 pt-2.5 mt-2.5">
                                <span className="text-gray-600 font-medium text-xs sm:text-sm">Fee Jasa</span>
                                <span className="font-bold text-indigo-600 text-sm sm:text-base">
                                    {formatRp(order?.grand_total || 0)}
                                </span>
                            </div>
                        </div>

                        {!isCancelled && telegramLinkUrl ? (
                            <div className="bg-blue-50 border border-blue-100 rounded-2xl p-4 mb-5">
                                <p className="text-xs text-blue-800 mb-3 text-center font-medium">
                                    Konfirmasi order kamu via Bot Telegram Warindong:
                                </p>
                                <a
                                    href={telegramLinkUrl}
                                    className="block w-full text-center bg-[#229ED9] hover:bg-[#1e8dcc] text-white text-sm font-semibold py-2.5 rounded-xl transition-colors shadow-md shadow-blue-200"
                                >
                                    Buka Bot Telegram Warindong
                                </a>
                                <p className="text-[11px] text-blue-600/80 mt-2 text-center">
                                    Klik tombol di atas untuk konfirmasi & aktivasi order kamu.
                                </p>
                            </div>
                        ) : (
                            <div className="bg-blue-50 border border-blue-100 rounded-2xl p-3.5 mb-5">
                                <p className="text-xs text-blue-700 text-center leading-relaxed">
                                    Pantau status order kamu via Telegram. QRIS akan dikirim otomatis setelah tiket berhasil.
                                </p>
                            </div>
                        )}

                        <div className="flex gap-2.5">
                            <Link
                                href={homeUrl}
                                className="flex-1 text-center text-xs sm:text-sm border border-gray-200 text-gray-700 py-2.5 rounded-xl hover:bg-gray-50 transition-colors font-medium"
                            >
                                Kembali
                            </Link>
                            <a
                                href={telegramGroupLink || '#'}
                                target="_blank"
                                rel="noopener noreferrer"
                                className="flex-1 text-center text-xs sm:text-sm bg-indigo-600 hover:bg-indigo-700 text-white py-2.5 rounded-xl transition-colors font-semibold shadow-md shadow-indigo-200"
                            >
                                Join Telegram
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </PublicLayout>
    );
}
