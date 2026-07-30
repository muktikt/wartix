import { Head, Link } from '@inertiajs/react';

const TELEGRAM_LINK = 'https://t.me/wartixdotcom';
const WHATSAPP_LINK = 'https://chat.whatsapp.com/CBgJ9tYH2F08OlteajZcBJ?s=cl&p=i&ilr=4';
const X_LINK = 'https://x.com/wartixcom';
const TIKTOK_LINK = 'https://www.tiktok.com/@wartix.com';

function TelegramIcon({ className }) {
    return (
        <svg className={className} viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
            <path d="M9.04 15.38 8.9 19.33c.42 0 .6-.18.83-.4l1.98-1.9 4.1 3c.75.42 1.29.2 1.48-.7l2.68-12.63c.24-1.12-.4-1.56-1.14-1.28L3.8 9.56c-1.1.43-1.08 1.05-.2 1.33l4.05 1.26 9.4-5.92c.44-.29.84-.13.51.17z" />
        </svg>
    );
}

export default function PublicLayout({ children, title, description }) {
    return (
        <>
            <Head>
                <title>{title ? `${title} - Wartix` : 'Wartix Priority Ticket Assistance'}</title>
                <meta
                    name="description"
                    content={description || 'Wartix membantu kamu mendapatkan tiket konser, festival, dan fanmeeting dengan Priority Access, Realtime Monitoring, dan update via Telegram.'}
                />
            </Head>

            {/* NAVBAR */}
            <nav className="sticky top-0 z-50 bg-white/95 backdrop-blur border-b border-gray-100">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="flex items-center h-14 gap-6">
                        <Link href={route('home')} className="flex items-center gap-2 flex-shrink-0 -ml-5 sm:-ml-8 group btn-press">
                            <img
                                src="/images/logo-w.png"
                                alt="Wartix"
                                className="h-10 sm:h-11 w-auto max-w-[210px] object-contain transition-transform duration-300 group-hover:scale-110 group-hover:rotate-3"
                            />
                            <span className="hidden sm:inline text-sm font-semibold text-gray-900 transition-colors duration-200 group-hover:text-indigo-600">Wartix</span>
                        </Link>

                        <div className="hidden md:flex items-center gap-1 flex-1">
                            <a href={`${route('home')}#active-events`} className="px-3 py-1.5 text-sm rounded-lg transition-colors text-gray-600 hover:text-gray-900 hover:bg-gray-50 btn-press">
                                Events
                            </a>
                            <Link href={route('monitor')} className="px-3 py-1.5 text-sm rounded-lg transition-colors text-gray-600 hover:text-gray-900 hover:bg-gray-50 btn-press">
                                Realtime Monitor
                            </Link>
                            <a href={`${route('home')}#cara-order`} className="px-3 py-1.5 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-lg transition-colors btn-press">
                                Cara Order
                            </a>
                            <a href={`${route('home')}#faq`} className="px-3 py-1.5 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-lg transition-colors btn-press">
                                FAQ
                            </a>
                        </div>

                        <div className="ml-auto flex items-center gap-2">
                            <a
                                href={TELEGRAM_LINK}
                                target="_blank"
                                rel="noopener noreferrer"
                                className="flex items-center gap-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-3.5 py-1.5 rounded-lg transition-colors btn-press"
                            >
                                <TelegramIcon className="w-4 h-4" />
                                Join Telegram
                            </a>
                        </div>
                    </div>
                </div>
            </nav>

            {/* PAGE CONTENT */}
            <div className="animate-fade-in">{children}</div>

            {/* FOOTER */}
            <footer className="bg-gray-900 text-gray-400 py-12 px-4">
                <div className="max-w-7xl mx-auto">
                    <div className="grid grid-cols-2 md:grid-cols-4 gap-8 mb-8">
                        <div>
                            <div className="flex items-center gap-2 mb-3">
                                <span className="text-sm font-semibold text-white">Wartix</span>
                            </div>
                            <p className="text-xs leading-relaxed text-gray-400 mb-4">
                                Platform Ticket Assistance untuk event high-demand.
                                Priority Access, Realtime Monitoring, dan update via Telegram.
                            </p>
                        </div>
                        <div>
                            <h4 className="text-xs font-semibold text-white uppercase tracking-wider mb-3">Product</h4>
                            <ul className="space-y-2 text-xs">
                                <li><Link href={route('events.index')} className="hover:text-white transition-colors duration-200">Events</Link></li>
                                <li><a href={`${route('home')}#monitor`} className="hover:text-white transition-colors duration-200">Realtime Monitor</a></li>
                                <li><a href={`${route('home')}#cara-order`} className="hover:text-white transition-colors duration-200">Cara Order</a></li>
                                <li><a href={`${route('home')}#faq`} className="hover:text-white transition-colors duration-200">FAQ</a></li>
                            </ul>
                        </div>
                        <div>
                            <h4 className="text-xs font-semibold text-white uppercase tracking-wider mb-3">Community</h4>
                            <ul className="space-y-2 text-xs">
                                <li><a href={TELEGRAM_LINK} target="_blank" rel="noopener noreferrer" className="hover:text-white transition-colors duration-200">Telegram Channel</a></li>
                                <li><a href={WHATSAPP_LINK} target="_blank" rel="noopener noreferrer" className="hover:text-white transition-colors duration-200">WhatsApp Group</a></li>
                                <li><a href={X_LINK} target="_blank" rel="noopener noreferrer" className="hover:text-white transition-colors duration-200">X (Twitter)</a></li>
                                <li><a href={TIKTOK_LINK} target="_blank" rel="noopener noreferrer" className="hover:text-white transition-colors duration-200">TikTok</a></li>
                            </ul>
                        </div>
                        <div>
                            <h4 className="text-xs font-semibold text-white uppercase tracking-wider mb-3">Legal</h4>
                            <ul className="space-y-2 text-xs">
                                <li><a href="#" className="hover:text-white transition-colors duration-200">Terms of Service</a></li>
                                <li><a href="#" className="hover:text-white transition-colors duration-200">Privacy Policy</a></li>
                                <li><a href="#" className="hover:text-white transition-colors duration-200">Refund Policy</a></li>
                            </ul>
                        </div>
                    </div>
                    <div className="border-t border-gray-800 pt-6 flex flex-col sm:flex-row items-center justify-between gap-2">
                        <p className="text-xs">&copy; {new Date().getFullYear()} Wartix. All rights reserved.</p>
                        <p className="text-xs">Event Assistance Platform</p>
                    </div>
                </div>
            </footer>

            {/* Floating Telegram Button */}
            <a
                href={TELEGRAM_LINK}
                target="_blank"
                rel="noopener noreferrer"
                className="fixed bottom-6 right-6 w-12 h-12 bg-[#229ED9] hover:bg-[#1e8dcc] text-white rounded-full flex items-center justify-center shadow-lg hover:shadow-xl transition-all duration-300 z-50 float-btn-glow hover:scale-110 btn-press"
            >
                <TelegramIcon className="w-6 h-6" />
            </a>
        </>
    );
}
