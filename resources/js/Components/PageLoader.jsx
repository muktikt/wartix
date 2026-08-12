import React from 'react';
import { motion, AnimatePresence } from 'framer-motion';

export default function PageLoader({ isDone }) {
    return (
        <AnimatePresence>
            {!isDone && (
                <motion.div
                    initial={{ opacity: 1 }}
                    exit={{ opacity: 0, transition: { duration: 0.8, ease: 'easeInOut' } }}
                    className="fixed inset-0 z-50 flex flex-col items-center justify-center bg-slate-950 text-white select-none pointer-events-none"
                >
                    {/* Glowing Logo Animation */}
                    <motion.div
                        initial={{ scale: 0.8, opacity: 0 }}
                        animate={{ scale: [0.9, 1.1, 1], opacity: 1 }}
                        transition={{ duration: 1, repeat: Infinity, repeatType: 'reverse' }}
                        className="relative flex items-center justify-center mb-6"
                    >
                        <div className="w-20 h-20 rounded-2xl bg-gradient-to-tr from-indigo-500 via-purple-500 to-pink-500 blur-xl opacity-70 animate-pulse" />
                        <div className="absolute w-16 h-16 rounded-2xl bg-slate-900 border border-slate-700/80 flex items-center justify-center shadow-2xl">
                            <span className="text-3xl font-extrabold bg-gradient-to-tr from-indigo-400 to-purple-400 bg-clip-text text-transparent">
                                W
                            </span>
                        </div>
                    </motion.div>

                    {/* Brand Name Animation */}
                    <motion.h1
                        initial={{ y: 20, opacity: 0 }}
                        animate={{ y: 0, opacity: 1 }}
                        transition={{ duration: 0.5, delay: 0.2 }}
                        className="text-2xl font-bold tracking-wider text-slate-100 mb-2"
                    >
                        WARINDONG<span className="text-indigo-400">.</span>
                    </motion.h1>

                    {/* Subtitle / Progress Line */}
                    <motion.div
                        initial={{ width: 0 }}
                        animate={{ width: 140 }}
                        transition={{ duration: 1.2, ease: "easeInOut" }}
                        className="h-1 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 rounded-full"
                    />
                </motion.div>
            )}
        </AnimatePresence>
    );
}
