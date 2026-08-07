import { useState } from 'react';
import { Head, Link, usePage, router } from '@inertiajs/react';
import { AnimatePresence, motion } from 'framer-motion';

const NAV_GROUPS = [
    {
        label: 'Main',
        items: [
            { name: 'admin.dashboard', label: 'Dashboard', match: 'admin.dashboard', icon: 'M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z' },
            { name: 'admin.events.index', label: 'Events', match: 'admin.events.*', icon: 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z' },
            { name: 'admin.orders.index', label: 'Orders', match: 'admin.orders.*', icon: 'M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z' },
        ],
    },
    {
        label: 'Monitor',
        items: [
            { name: 'admin.monitor.index', label: 'Realtime Monitor', match: 'admin.monitor.*', icon: 'M13 10V3L4 14h7v7l9-11h-7z' },
            { name: 'admin.reports.index', label: 'Reports', match: 'admin.reports.*', icon: 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z' },
        ],
    },
    {
        label: 'Settings',
        items: [
            { name: 'admin.integrations.index', label: 'Integration', match: 'admin.integrations.*', icon: 'M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1' },
            { name: 'admin.statistics.index', label: 'Statistics', match: 'admin.statistics.*', icon: 'M11 3.055A9.001 9.001 0 1020.945 13H11V3.055zM20.488 9H15V3.512A9.025 9.025 0 0120.488 9z' },
        ],
    },
];

function routeIs(pattern) {
    return route().current(pattern);
}

function NavIcon({ d }) {
    return (
        <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d={d} />
        </svg>
    );
}

export default function AdminLayout({ children, title }) {
    const { props } = usePage();
    const admin = props.auth?.admin;
    const unreadCount = props.adminNotifUnreadCount ?? 0;
    const flash = props.flash ?? {};
    const [sidebarOpen, setSidebarOpen] = useState(false);
    const [sidebarCollapsed, setSidebarCollapsed] = useState(() => {
        if (typeof window !== 'undefined') {
            return localStorage.getItem('admin_sidebar_collapsed') === 'true';
        }
        return false;
    });

    const toggleSidebarCollapsed = () => {
        setSidebarCollapsed((prev) => {
            const next = !prev;
            if (typeof window !== 'undefined') {
                localStorage.setItem('admin_sidebar_collapsed', String(next));
            }
            return next;
        });
    };

    function logout(e) {
        e.preventDefault();
        router.post(route('admin.logout'));
    }

    return (
        <>
            <Head title={`${title ? title + ' - ' : ''}Wartix Admin`} />

            <div className="flex h-screen overflow-hidden bg-gray-50">
                {/* Backdrop for mobile */}
                <AnimatePresence>
                    {sidebarOpen && (
                        <motion.div
                            initial={{ opacity: 0 }}
                            animate={{ opacity: 1 }}
                            exit={{ opacity: 0 }}
                            transition={{ duration: 0.2 }}
                            onClick={() => setSidebarOpen(false)}
                            className="fixed inset-0 z-30 bg-gray-900/50 backdrop-blur-sm md:hidden"
                        />
                    )}
                </AnimatePresence>

                {/* SIDEBAR */}
                <aside
                    className={`fixed inset-y-0 left-0 z-40 bg-white border-r border-gray-100 flex flex-col flex-shrink-0 transition-all duration-300 ease-in-out transform md:static ${
                        sidebarCollapsed ? 'md:w-16' : 'md:w-56'
                    } ${sidebarOpen ? 'translate-x-0 w-56' : '-translate-x-full md:translate-x-0'}`}
                >
                    <div className="h-14 flex items-center justify-between px-3 border-b border-gray-100">
                        <div className="flex items-center gap-2.5 overflow-hidden">
                            <img
                                src="/images/logo-w.png"
                                alt="Wartix"
                                className="h-7 w-auto flex-shrink-0"
                            />
                            {!sidebarCollapsed && (
                                <span className="text-xs bg-indigo-50 text-indigo-600 px-1.5 py-0.5 rounded font-medium flex-shrink-0">
                                    Admin
                                </span>
                            )}
                        </div>

                        {/* Desktop collapse toggle button inside sidebar header */}
                        <button
                            onClick={toggleSidebarCollapsed}
                            className="hidden md:flex items-center justify-center w-7 h-7 rounded-lg text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 transition-all cursor-pointer flex-shrink-0"
                            title={sidebarCollapsed ? "Buka Sidebar" : "Kecilkan Sidebar"}
                        >
                            <svg className={`w-4 h-4 transition-transform duration-300 ${sidebarCollapsed ? 'rotate-180' : ''}`} fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                            </svg>
                        </button>

                        {/* Mobile drawer close button */}
                        <button onClick={() => setSidebarOpen(false)} className="text-gray-400 hover:text-gray-600 focus:outline-none md:hidden p-1 cursor-pointer" title="Tutup Menu">
                            <svg className="w-5 h-5 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <nav className="flex-1 py-3 overflow-y-auto">
                        {NAV_GROUPS.map((group) => (
                            <div key={group.label} className={`px-2 mt-3 mb-1 first:mt-0`}>
                                {!sidebarCollapsed && (
                                    <p className="text-xs text-gray-400 uppercase tracking-wider px-2 mb-1 hidden md:block">{group.label}</p>
                                )}
                                <p className="text-xs text-gray-400 uppercase tracking-wider px-2 mb-1 md:hidden">{group.label}</p>
                                {group.items.map((item) => {
                                    const active = routeIs(item.match);
                                    return (
                                        <a
                                            key={item.name}
                                            href={route(item.name)}
                                            title={item.label}
                                            className={`sidebar-link flex items-center gap-2.5 px-2.5 py-2 rounded-lg text-sm transition-all ${
                                                sidebarCollapsed ? 'md:justify-center md:px-0' : ''
                                            } ${active ? 'bg-indigo-50 text-indigo-600 font-medium' : 'text-gray-600 hover:bg-gray-50'}`}
                                        >
                                            <NavIcon d={item.icon} />
                                            <span className={`${sidebarCollapsed ? 'md:hidden' : ''}`}>{item.label}</span>
                                        </a>
                                    );
                                })}
                            </div>
                        ))}
                    </nav>

                    <div className="p-3 border-t border-gray-100">
                        <div className={`flex items-center ${sidebarCollapsed ? 'md:justify-center' : 'gap-2.5'}`}>
                            <div className="w-7 h-7 rounded-full bg-indigo-600 flex items-center justify-center text-white text-xs font-semibold flex-shrink-0" title={admin?.name ?? 'Admin'}>
                                {(admin?.name ?? 'A').charAt(0).toUpperCase()}
                            </div>
                            <div className={`flex-1 min-w-0 ${sidebarCollapsed ? 'md:hidden' : ''}`}>
                                <p className="text-xs font-medium text-gray-900 truncate">{admin?.name ?? 'Admin'}</p>
                                <p className="text-xs text-gray-400 truncate">{admin?.role ?? 'admin'}</p>
                            </div>
                            <form onSubmit={logout} className={`${sidebarCollapsed ? 'md:hidden' : ''}`}>
                                <button type="submit" className="text-gray-400 hover:text-red-500 transition-colors" title="Logout">
                                    <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </aside>

                {/* MAIN CONTENT */}
                <div className="flex-1 flex flex-col min-w-0 overflow-hidden">
                    <header className="h-14 bg-white border-b border-gray-100 flex items-center px-4 sm:px-5 gap-3 flex-shrink-0">
                        {/* Mobile drawer button */}
                        <button onClick={() => setSidebarOpen(true)} className="text-gray-500 hover:text-gray-600 focus:outline-none md:hidden p-1">
                            <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>

                        <h1 className="text-sm font-semibold text-gray-900 flex-1 truncate">{title ?? 'Dashboard'}</h1>

                        <a
                            href={route('admin.notifications.index')}
                            title={`${unreadCount} notifikasi belum dibaca`}
                            className="w-8 h-8 rounded-lg border border-gray-100 flex items-center justify-center relative hover:bg-gray-50 transition-all duration-300"
                        >
                            <svg className="w-4 h-4 text-gray-500 hover:text-indigo-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            <AnimatePresence>
                                {unreadCount > 0 && (
                                    <motion.span
                                        initial={{ scale: 0 }}
                                        animate={{ scale: [1, 1.15, 1] }}
                                        transition={{ duration: 1.6, repeat: Infinity, ease: 'easeInOut' }}
                                        className="absolute -top-0.5 -right-0.5 min-w-[16px] h-4 bg-red-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center px-1"
                                    >
                                        {unreadCount > 99 ? '99+' : unreadCount}
                                    </motion.span>
                                )}
                            </AnimatePresence>
                        </a>
                    </header>

                    {/* Page Content — fades + slides in on every navigation */}
                    <motion.main
                        key={typeof window !== 'undefined' ? window.location.pathname : 'page'}
                        initial={{ opacity: 0, y: 24 }}
                        animate={{ opacity: 1, y: 0 }}
                        transition={{ duration: 0.85, ease: [0.16, 1, 0.3, 1] }}
                        className="flex-1 overflow-y-auto p-5"
                    >
                        <AnimatePresence>
                            {flash.success && (
                                <motion.div
                                    initial={{ opacity: 0, height: 0 }}
                                    animate={{ opacity: 1, height: 'auto' }}
                                    exit={{ opacity: 0, height: 0 }}
                                    className="mb-4 bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-3 rounded-lg overflow-hidden"
                                >
                                    {flash.success}
                                </motion.div>
                            )}
                            {flash.error && (
                                <motion.div
                                    initial={{ opacity: 0, height: 0 }}
                                    animate={{ opacity: 1, height: 'auto' }}
                                    exit={{ opacity: 0, height: 0 }}
                                    className="mb-4 bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3 rounded-lg overflow-hidden"
                                >
                                    {flash.error}
                                </motion.div>
                            )}
                        </AnimatePresence>

                        {children}
                    </motion.main>
                </div>
            </div>
        </>
    );
}
