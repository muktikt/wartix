import { useEffect, useState } from 'react';
import { Head, Link } from '@inertiajs/react';
import { formatRp } from '../../utils/format';

function TelegramIcon({ className }) {
    return (
        <svg className={className} viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
            <path d="M9.04 15.38 8.9 19.33c.42 0 .6-.18.83-.4l1.98-1.9 4.1 3c.75.42 1.29.2 1.48-.7l2.68-12.63c.24-1.12-.4-1.56-1.14-1.28L3.8 9.56c-1.1.43-1.08 1.05-.2 1.33l4.05 1.26 9.4-5.92c.44-.29.84-.13.51.17z" />
        </svg>
    );
}

function CopyIcon({ className }) {
    return (
        <svg className={className} fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
        </svg>
    );
}

function CheckIcon({ className }) {
    return (
        <svg className={className} fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M5 13l4 4L19 7" />
        </svg>
    );
}

export default function OrderSuccess({ order, telegramLinkUrl, telegramGroupLink }) {
    const [copied, setCopied] = useState(false);

    useEffect(() => {
        if (!telegramLinkUrl) return;
        const timer = setTimeout(() => {
            window.location.href = telegramLinkUrl;
        }, 1800);
        return () => clearTimeout(timer);
    }, [telegramLinkUrl]);

    const handleCopyCode = () => {
        if (!order?.order_code) return;
        navigator.clipboard.writeText(order.order_code);
        setCopied(true);
        setTimeout(() => setCopied(false), 2000);
    };

    const categoryName = order.ticket_category?.name
        ?? order.category_choices?.[0]?.ticket_category?.name
        ?? '-';

    const isCancelled = order.order_status === 'cancelled';

    return (
        <>
            <Head>
                <title>{isCancelled ? 'Order Dibatalkan' : 'Order Berhasil Dikirim'} - Warindong</title>
                <meta name="robots" content="noindex,nofollow" />
            </Head>

            <div className="min-h-screen bg-slate-900/5 sm:bg-slate-100/60 flex items-center justify-center p-3 sm:p-6 select-none relative overflow-hidden">
                {/* Subtle decorative background blur shapes */}
                <div className="absolute -top-32 -left-32 w-80 h-80 bg-indigo-200/30 rounded-full blur-3xl pointer-events-none" />
                <div className="absolute -bottom-32 -right-32 w-80 h-80 bg-blue-200/30 rounded-full blur-3xl pointer-events-none" />

                <div className="w-full max-w-md bg-white border border-gray-100 rounded-3xl shadow-xl shadow-slate-200/60 p-6 sm:p-8 relative z-10 animate-fade-in-up">
                    
                    {/* Status Icon */}
                    {isCancelled ? (
                        <div className="w-16 h-16 bg-red-50 text-red-500 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-red-100 shadow-sm">
                            <svg className="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </div>
                    ) : (
                        <div className="w-16 h-16 bg-emerald-50 text-emerald-500 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-emerald-100 shadow-sm">
                            <CheckIcon className="w-8 h-8" />
                        </div>
                    )}

                    {/* Header */}
                    <div className="text-center mb-6">
                        <h1 className="text-xl sm:text-2xl font-bold text-gray-900 tracking-tight">
                            {isCancelled ? 'Order Dibatalkan' : 'Order Berhasil Dikirim!'}
                        </h1>
                        <p className="text-xs sm:text-sm text-gray-500 mt-1.5 leading-relaxed">
                            {isCancelled 
                                ? 'Waktu konfirmasi Telegram telah berakhir (10 menit) sehingga order dibatalkan otomatis.'
                                : 'Tim Warindong akan segera memproses order kamu.'
                            }
                        </p>
                    </div>

                    {/* Order Details Cart Card */}
                    <div className="bg-gray-50/80 border border-gray-100 rounded-2xl p-4 sm:p-5 mb-5 space-y-3">
                        {/* Order Code Row */}
                        <div className="flex items-center justify-between gap-3 pb-3 border-b border-gray-100">
                            <span className="text-xs sm:text-sm text-gray-500 font-medium shrink-0">Order Code</span>
                            <button
                                type="button"
                                onClick={handleCopyCode}
                                className="group inline-flex items-center gap-1.5 bg-white hover:bg-gray-100 border border-gray-200 px-2.5 py-1 rounded-lg text-xs font-mono font-bold text-gray-900 transition-colors cursor-pointer shadow-2xs"
                                title="Klik untuk salin order code"
                            >
                                <span>{order.order_code}</span>
                                {copied ? (
                                    <span className="text-[10px] text-emerald-600 font-sans font-medium flex items-center gap-0.5">
                                        <CheckIcon className="w-3 h-3" /> Disalin
                                    </span>
                                ) : (
                                    <CopyIcon className="w-3 h-3 text-gray-400 group-hover:text-gray-600 transition-colors" />
                                )}
                            </button>
                        </div>

                        {/* Event Title Row */}
                        <div className="flex items-start justify-between gap-3 text-xs sm:text-sm">
                            <span className="text-gray-500 font-medium shrink-0 min-w-[75px] pt-0.5">Event</span>
                            <span className="font-semibold text-gray-900 text-right flex-1 break-words leading-snug">
                                {order.event?.title || '-'}
                            </span>
                        </div>

                        {/* Sale Phase Row */}
                        <div className="flex items-start justify-between gap-3 text-xs sm:text-sm">
                            <span className="text-gray-500 font-medium shrink-0 min-w-[75px] pt-0.5">Sale Phase</span>
                            <span className="font-medium text-gray-800 text-right flex-1 break-words leading-snug">
                                {order.sale_phase?.name ?? '-'}
                            </span>
                        </div>

                        {/* Category & Qty Row */}
                        <div className="flex items-start justify-between gap-3 text-xs sm:text-sm">
                            <span className="text-gray-500 font-medium shrink-0 min-w-[75px]">Kategori</span>
                            <span className="font-medium text-gray-800 text-right flex-1 break-words">
                                {categoryName} <span className="text-indigo-600 font-semibold">x{order.qty}</span>
                            </span>
                        </div>

                        {/* Fee Total Row */}
                        <div className="flex items-center justify-between gap-3 pt-3 border-t border-gray-200">
                            <span className="text-xs sm:text-sm font-semibold text-gray-700">Fee Jasa</span>
                            <span className="text-base sm:text-lg font-bold text-indigo-600">
                                {formatRp(order.grand_total)}
                            </span>
                        </div>
                    </div>

                    {/* Telegram Redirect / Action Banner */}
                    {!isCancelled && telegramLinkUrl ? (
                        <div className="bg-sky-50 border border-sky-100 rounded-2xl p-4 mb-5 text-center">
                            <p className="text-xs text-sky-800 font-medium mb-3 flex items-center justify-center gap-1.5">
                                <span className="w-2 h-2 rounded-full bg-sky-500 animate-ping inline-block" />
                                Mengalihkan ke Telegram untuk konfirmasi order...
                            </p>
                            <a
                                href={telegramLinkUrl}
                                className="inline-flex items-center justify-center gap-2 w-full bg-[#229ED9] hover:bg-[#1e8dcc] active:scale-[0.98] text-white text-sm font-semibold py-2.5 px-4 rounded-xl transition-all shadow-md shadow-sky-200"
                            >
                                <TelegramIcon className="w-4 h-4" />
                                <span>Buka Bot Telegram Warindong</span>
                            </a>
                            <p className="text-[11px] text-sky-600/80 mt-2">
                                Belum teralihkan otomatis? Klik tombol di atas.
                            </p>
                        </div>
                    ) : (
                        <div className="bg-blue-50/70 border border-blue-100 rounded-2xl p-3.5 mb-5 text-center">
                            <p className="text-xs text-blue-700 leading-relaxed">
                                Pantau status order kamu via Telegram. QRIS akan dikirim otomatis setelah tiket berhasil.
                            </p>
                        </div>
                    )}

                    {/* Action Buttons */}
                    <div className="flex items-center gap-2.5">
                        <Link
                            href={route('home')}
                            className="flex-1 text-center text-xs sm:text-sm font-medium border border-gray-200 hover:border-gray-300 text-gray-700 py-2.5 rounded-xl hover:bg-gray-50 active:scale-[0.98] transition-all"
                        >
                            Kembali
                        </Link>
                        <a
                            href={telegramGroupLink}
                            target="_blank"
                            rel="noopener noreferrer"
                            className="flex-1 inline-flex items-center justify-center gap-1.5 text-xs sm:text-sm font-semibold bg-indigo-600 hover:bg-indigo-700 active:scale-[0.98] text-white py-2.5 rounded-xl transition-all shadow-md shadow-indigo-200"
                        >
                            <TelegramIcon className="w-3.5 h-3.5" />
                            <span>Join Telegram</span>
                        </a>
                    </div>

                </div>
            </div>
        </>
    );
}
