import { useEffect } from 'react';
import { Link } from '@inertiajs/react';
import PublicLayout from '../../Layouts/PublicLayout';
import { formatRp } from '../../utils/format';

export default function OrderSuccess({ order, telegramLinkUrl, telegramGroupLink }) {
    useEffect(() => {
        if (!telegramLinkUrl) return;
        const timer = setTimeout(() => {
            window.location.href = telegramLinkUrl;
        }, 1500);
        return () => clearTimeout(timer);
    }, [telegramLinkUrl]);

    const categoryName = order.ticket_category?.name
        ?? order.category_choices?.[0]?.ticket_category?.name
        ?? '-';

    return (
        <PublicLayout title="Order Berhasil Warindong">
            <div className="min-h-screen flex items-center justify-center px-4 py-12">
                <div className="max-w-md w-full">
                    <div className="bg-white border border-gray-100 rounded-2xl p-6 text-center">
                        <div className="w-14 h-14 bg-green-50 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg className="w-7 h-7 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <h1 className="text-lg font-bold text-gray-900 mb-1">Order Berhasil Dikirim!</h1>
                        <p className="text-sm text-gray-500 mb-5">Tim Warindong akan segera memproses order kamu.</p>

                        <div className="bg-gray-50 rounded-xl p-4 text-left mb-5 space-y-2">
                            <div className="flex justify-between text-sm">
                                <span className="text-gray-500">Order Code</span>
                                <span className="font-semibold text-gray-900">{order.order_code}</span>
                            </div>
                            <div className="flex justify-between text-sm">
                                <span className="text-gray-500">Event</span>
                                <span className="font-medium text-gray-700">{order.event?.title}</span>
                            </div>
                            <div className="flex justify-between text-sm">
                                <span className="text-gray-500">Sale Phase</span>
                                <span className="font-medium text-gray-700">{order.sale_phase?.name ?? '-'}</span>
                            </div>
                            <div className="flex justify-between text-sm">
                                <span className="text-gray-500">Kategori</span>
                                <span className="font-medium text-gray-700">{categoryName} x{order.qty}</span>
                            </div>
                            <div className="flex justify-between text-sm border-t border-gray-200 pt-2 mt-2">
                                <span className="text-gray-500">Fee Jasa</span>
                                <span className="font-semibold text-indigo-600">{formatRp(order.grand_total)}</span>
                            </div>
                        </div>

                        {telegramLinkUrl ? (
                            <div className="bg-blue-50 border border-blue-100 rounded-xl p-4 mb-5">
                                <p className="text-xs text-blue-700 mb-3 text-center">
                                    Mengalihkan ke Telegram untuk konfirmasi order...
                                </p>
                                <a href={telegramLinkUrl} className="block w-full text-center bg-[#229ED9] hover:bg-[#1e8dcc] text-white text-sm font-medium py-2.5 rounded-xl transition-colors">
                                    Buka Bot Telegram Warindong
                                </a>
                                <p className="text-xs text-blue-500 mt-2 text-center">
                                    Belum teralihkan otomatis? Klik tombol di atas.
                                </p>
                            </div>
                        ) : (
                            <div className="bg-blue-50 border border-blue-100 rounded-xl p-3 mb-5">
                                <p className="text-xs text-blue-700 text-center">
                                    Pantau status order kamu via Telegram. QRIS akan dikirim otomatis setelah tiket berhasil.
                                </p>
                            </div>
                        )}

                        <div className="flex gap-2">
                            <Link href={route('home')} className="flex-1 text-center text-sm border border-gray-200 text-gray-600 py-2.5 rounded-xl hover:bg-gray-50 transition-colors">
                                Kembali
                            </Link>
                            <a href={telegramGroupLink} target="_blank" rel="noopener noreferrer" className="flex-1 text-center text-sm bg-indigo-600 hover:bg-indigo-700 text-white py-2.5 rounded-xl transition-colors">
                                Join Telegram
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </PublicLayout>
    );
}
