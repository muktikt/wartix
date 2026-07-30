import React, { useState, useEffect } from 'react';
import { motion, AnimatePresence } from 'framer-motion';
import PageLoader from '../Components/PageLoader';
import confetti from 'canvas-confetti';
import { Sparkles, Zap, ShieldCheck, Ticket, RefreshCw, Eye } from 'lucide-react';

export default function Test({ message }) {
    const [loading, setLoading] = useState(true);
    const [count, setCount] = useState(0);
    const [showModal, setShowModal] = useState(false);
    const [showCard, setShowCard] = useState(true);

    useEffect(() => {
        const timer = setTimeout(() => {
            setLoading(false);
        }, 1200);
        return () => clearTimeout(timer);
    }, []);

    const triggerConfetti = () => {
        setCount(prev => prev + 1);
        confetti({
            particleCount: 80,
            spread: 60,
            origin: { y: 0.6 }
        });
    };

    // Stagger animation container untuk pembukaan halaman
    const containerVariants = {
        hidden: { opacity: 0 },
        visible: {
            opacity: 1,
            transition: {
                staggerChildren: 0.3,
                delayChildren: 1.4
            }
        }
    };

    // Animasi munculnya elemen halaman saat awal dimuat (Fade In murni dengan jarak kecil 10px)
    const itemVariants = {
        hidden: { y: 10, opacity: 0 },
        visible: { 
            y: 0, 
            opacity: 1,
            transition: { duration: 1.8, ease: "linear" } // Sangat kentara (1.8s)
        }
    };

    // Animasi Khusus Fade In & Fade Out (Di-set 1.8 Detik Linear agar benar-benar terlihat pelan di browser)
    const fade08Variants = {
        initial: { opacity: 0, scale: 0.98, y: 5 },
        animate: { 
            opacity: 1, 
            scale: 1, 
            y: 0,
            transition: { duration: 1.8, ease: "linear" } // 1.8 Detik murni
        },
        exit: { 
            opacity: 0, 
            scale: 0.98, 
            y: 5,
            transition: { duration: 1.8, ease: "linear" } // 1.8 Detik murni
        }
    };

    const features = [
        { icon: <Zap className="w-6 h-6 text-amber-400" />, title: 'Super Cepat', desc: 'Sistem booking tiket event tanpa lemot berbasis React & Inertia.' },
        { icon: <ShieldCheck className="w-6 h-6 text-emerald-400" />, title: 'Aman & Terpercaya', desc: 'Keamanan transaksi berstandar tinggi dengan konfirmasi instan.' },
        { icon: <Ticket className="w-6 h-6 text-indigo-400" />, title: 'E-Ticket Instan', desc: 'Dapatkan kode QR unik untuk akses langsung ke venue event.' },
    ];

    return (
        <div className="min-h-screen bg-slate-950 text-slate-100 flex flex-col justify-between relative overflow-hidden font-sans selection:bg-indigo-500 selection:text-white">
            
            {/* Smooth Opening Splash Loader */}
            <PageLoader isDone={!loading} />

            {/* Background Animated Blobs */}
            <motion.div 
                animate={{ 
                    scale: [1, 1.2, 1], 
                    rotate: [0, 90, 0],
                    opacity: [0.25, 0.45, 0.25] 
                }}
                transition={{ duration: 16, repeat: Infinity, ease: "easeInOut" }}
                className="absolute -top-32 -left-32 w-96 h-96 bg-indigo-600/30 rounded-full blur-3xl pointer-events-none"
            />
            <motion.div 
                animate={{ 
                    scale: [1, 1.3, 1], 
                    rotate: [0, -90, 0],
                    opacity: [0.2, 0.4, 0.2] 
                }}
                transition={{ duration: 18, repeat: Infinity, ease: "easeInOut" }}
                className="absolute -bottom-32 -right-32 w-96 h-96 bg-purple-600/30 rounded-full blur-3xl pointer-events-none"
            />

            {/* Main Animated Wrapper */}
            <motion.div
                variants={containerVariants}
                initial="hidden"
                animate="visible"
                className="w-full flex flex-col justify-between flex-1"
            >
                {/* Header Navbar */}
                <motion.header variants={itemVariants} className="container mx-auto px-6 py-6 flex justify-between items-center relative z-10">
                    <div className="flex items-center gap-2">
                        <div className="w-10 h-10 rounded-xl bg-gradient-to-tr from-indigo-500 to-purple-500 flex items-center justify-center shadow-lg shadow-indigo-500/25">
                            <Sparkles className="w-6 h-6 text-white" />
                        </div>
                        <span className="text-2xl font-bold bg-gradient-to-r from-white via-slate-200 to-slate-400 bg-clip-text text-transparent">
                            Wartix<span className="text-indigo-400">.</span>
                        </span>
                    </div>
                    <div className="flex items-center gap-3">
                        <span className="px-3 py-1 text-xs font-semibold rounded-full bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 flex items-center gap-1.5">
                            <span className="w-2 h-2 rounded-full bg-indigo-400 animate-ping" />
                            Ultra-Slow Test (1.8s Linear)
                        </span>
                    </div>
                </motion.header>

                {/* Main Hero Section */}
                <main className="container mx-auto px-6 py-12 relative z-10 max-w-5xl">
                    <div className="text-center space-y-6">
                        <motion.div variants={itemVariants} className="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-indigo-500/10 border border-indigo-500/20 text-indigo-300 text-sm font-medium">
                            <Sparkles className="w-4 h-4 text-amber-300 animate-bounce" />
                            <span>Pengalaman Baru Tiketing Modern</span>
                        </motion.div>

                        <motion.h1 variants={itemVariants} className="text-4xl md:text-6xl font-extrabold tracking-tight leading-tight">
                            Beli Tiket Event Favoritmu <br />
                            <span className="bg-gradient-to-r from-indigo-400 via-purple-400 to-pink-400 bg-clip-text text-transparent">
                                Lebih Cepat & Interaktif
                            </span>
                        </motion.h1>

                        <motion.p variants={itemVariants} className="text-slate-400 text-lg max-w-2xl mx-auto">
                            {message || 'Diuji dengan durasi 1.8 detik linear untuk memastikan efek Fade In & Fade Out terlihat sangat nyata di browser.'}
                        </motion.p>
                    </div>

                    {/* Interactive Demo Buttons & Toggle Control */}
                    <motion.div variants={itemVariants} className="mt-10 flex flex-col items-center gap-6">
                        <div className="flex flex-wrap justify-center gap-4">
                            {/* Confetti Button */}
                            <motion.button
                                whileHover={{ scale: 1.05 }}
                                whileTap={{ scale: 0.95 }}
                                onClick={triggerConfetti}
                                className="group relative px-6 py-3 rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-bold shadow-lg shadow-indigo-500/20 flex items-center gap-2"
                            >
                                <span>Confetti ({count})</span>
                                <Sparkles className="w-4 h-4 text-amber-300" />
                            </motion.button>

                            {/* Toggle Fade In/Out Card Button */}
                            <motion.button
                                whileHover={{ scale: 1.05 }}
                                whileTap={{ scale: 0.95 }}
                                onClick={() => setShowCard(!showCard)}
                                className="px-6 py-3 rounded-xl bg-slate-800 border border-slate-700 text-slate-200 font-semibold hover:border-slate-500 transition-colors flex items-center gap-2"
                            >
                                <RefreshCw className={`w-4 h-4 ${!showCard ? 'rotate-180' : ''} transition-transform duration-700`} />
                                <span>Toggle Ultra Fade ({showCard ? 'Sembunyikan' : 'Tampilkan'})</span>
                            </motion.button>

                            {/* Open Modal Button */}
                            <motion.button
                                whileHover={{ scale: 1.05 }}
                                whileTap={{ scale: 0.95 }}
                                onClick={() => setShowModal(true)}
                                className="px-6 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold shadow-lg shadow-emerald-600/20 transition-colors flex items-center gap-2"
                            >
                                <Eye className="w-4 h-4" />
                                <span>Demo Modal Ultra Fade</span>
                            </motion.button>
                        </div>

                        {/* Interactive Toggle Card with 1.8s Linear Fade In & Fade Out */}
                        <div className="w-full max-w-xl min-h-[120px] flex items-center justify-center mt-2">
                            <AnimatePresence mode="wait">
                                {showCard ? (
                                    <motion.div
                                        key="fade-08-card"
                                        variants={fade08Variants}
                                        initial="initial"
                                        animate="animate"
                                        exit="exit"
                                        className="w-full p-6 rounded-2xl bg-gradient-to-r from-slate-900 via-indigo-950/40 to-slate-900 border border-indigo-500/30 text-center shadow-xl backdrop-blur-md"
                                    >
                                        <h4 className="text-lg font-bold text-indigo-300 mb-1">✨ Ultra-Slow 1.8s Linear Fade Test</h4>
                                        <p className="text-slate-400 text-sm">
                                            Kartu ini menggunakan durasi 1.8 detik murni tanpa percepatan kurva agar Anda dapat melihat jelas proses memudarnya.
                                        </p>
                                    </motion.div>
                                ) : (
                                    <motion.div
                                        key="placeholder"
                                        initial={{ opacity: 0 }}
                                        animate={{ opacity: 0.5, transition: { duration: 1.8, ease: "linear" } }}
                                        exit={{ opacity: 0, transition: { duration: 1.8, ease: "linear" } }}
                                        className="text-slate-500 text-sm border border-dashed border-slate-800 rounded-2xl p-6 w-full text-center"
                                    >
                                        Elemen tersembunyi (Klik tombol "Toggle Ultra Fade" untuk melihat animasi Fade In 1.8s)
                                    </motion.div>
                                )}
                            </AnimatePresence>
                        </div>

                        {/* Features Grid */}
                        <div className="grid grid-cols-1 md:grid-cols-3 gap-6 w-full mt-6">
                            {features.map((item, idx) => (
                                <motion.div
                                    key={idx}
                                    variants={itemVariants}
                                    whileHover={{ y: -8, transition: { duration: 0.3 } }}
                                    className="p-6 rounded-2xl bg-slate-900/60 border border-slate-800 backdrop-blur-xl hover:border-indigo-500/40 transition-colors shadow-lg group"
                                >
                                    <div className="p-3 rounded-xl bg-slate-800/80 w-fit mb-4 group-hover:scale-110 transition-transform">
                                        {item.icon}
                                    </div>
                                    <h3 className="text-xl font-semibold text-white mb-2">{item.title}</h3>
                                    <p className="text-slate-400 text-sm leading-relaxed">{item.desc}</p>
                                </motion.div>
                            ))}
                        </div>
                    </motion.div>
                </main>

                {/* Footer */}
                <motion.footer variants={itemVariants} className="container mx-auto px-6 py-6 text-center text-slate-500 text-sm border-t border-slate-900 relative z-10">
                    Wartix Platform &copy; {new Date().getFullYear()} &bull; Built with Laravel 12, React 18 & Framer Motion
                </motion.footer>
            </motion.div>

            {/* 1.8s FADE IN & FADE OUT MODAL */}
            <AnimatePresence>
                {showModal && (
                    <div className="fixed inset-0 z-50 flex items-center justify-center p-4">
                        {/* Modal Backdrop 1.8s Fade In & Fade Out */}
                        <motion.div
                            initial={{ opacity: 0 }}
                            animate={{ opacity: 1, transition: { duration: 1.8, ease: "linear" } }}
                            exit={{ opacity: 0, transition: { duration: 1.8, ease: "linear" } }}
                            onClick={() => setShowModal(false)}
                            className="absolute inset-0 bg-slate-950/80 backdrop-blur-sm"
                        />

                        {/* Modal Content 1.8s Scale & Fade In / Fade Out */}
                        <motion.div
                            initial={{ opacity: 0, scale: 0.94, y: 15 }}
                            animate={{ 
                                opacity: 1, 
                                scale: 1, 
                                y: 0,
                                transition: { duration: 1.8, ease: "linear" } 
                            }}
                            exit={{ 
                                opacity: 0, 
                                scale: 0.94, 
                                y: 15,
                                transition: { duration: 1.8, ease: "linear" } 
                            }}
                            className="relative z-10 w-full max-w-md bg-slate-900 border border-slate-800 rounded-3xl p-6 shadow-2xl space-y-4"
                        >
                            <div className="flex items-center justify-between border-b border-slate-800 pb-3">
                                <h3 className="text-xl font-bold text-white flex items-center gap-2">
                                    <Sparkles className="w-5 h-5 text-emerald-400" />
                                    1.8s Fade Modal
                                </h3>
                                <button
                                    onClick={() => setShowModal(false)}
                                    className="text-slate-400 hover:text-white text-sm font-semibold p-1"
                                >
                                    ✕
                                </button>
                            </div>
                            <p className="text-slate-300 text-sm leading-relaxed">
                                Modal ini sekarang menggunakan durasi **1.8 detik linear** murni.
                            </p>
                            <div className="pt-2 flex justify-end">
                                <button
                                    onClick={() => setShowModal(false)}
                                    className="px-5 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-white text-sm font-semibold transition-colors"
                                >
                                    Tutup Modal
                                </button>
                            </div>
                        </motion.div>
                    </div>
                )}
            </AnimatePresence>

        </div>
    );
}
