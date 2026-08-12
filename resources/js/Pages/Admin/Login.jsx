import { Head, useForm, usePage } from '@inertiajs/react';
import { motion } from 'framer-motion';

export default function Login() {
    const { flash } = usePage().props;
    const { data, setData, post, processing, errors } = useForm({
        email: '',
        password: '',
        remember: false,
    });

    function submit(e) {
        e.preventDefault();
        post(route('admin.login.post'));
    }

    return (
        <>
            <Head title="Login Warindong Admin" />
            <div className="bg-gradient-to-br from-indigo-50 via-white to-purple-50 min-h-screen flex items-center justify-center font-sans">
                <motion.div
                    initial={{ opacity: 0, y: 16 }}
                    animate={{ opacity: 1, y: 0 }}
                    transition={{ duration: 0.4, ease: 'easeOut' }}
                    className="w-full max-w-sm px-4"
                >
                    <div className="text-center mb-8">
                        <div className="flex items-center justify-center mb-3">
                            <img src="/images/logo-w.png" alt="Warindong" className="h-14 w-auto" />
                        </div>
                        <h1 className="text-xl font-semibold text-gray-900">Warindong Admin</h1>
                        <p className="text-sm text-gray-500 mt-1">Masuk ke panel admin Warindong</p>
                    </div>

                    <motion.div
                        initial={{ opacity: 0, scale: 0.97 }}
                        animate={{ opacity: 1, scale: 1 }}
                        transition={{ duration: 0.4, delay: 0.1, ease: 'easeOut' }}
                        className="bg-white rounded-2xl border border-gray-100 shadow-sm p-6"
                    >
                        {flash?.error && (
                            <div className="mb-4 bg-red-50 border border-red-200 text-red-600 text-sm px-4 py-3 rounded-xl">{flash.error}</div>
                        )}
                        {flash?.success && (
                            <div className="mb-4 bg-green-50 border border-green-200 text-green-600 text-sm px-4 py-3 rounded-xl">{flash.success}</div>
                        )}

                        <form onSubmit={submit}>
                            <div className="mb-4">
                                <label className="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
                                <input
                                    type="email"
                                    value={data.email}
                                    onChange={(e) => setData('email', e.target.value)}
                                    placeholder="admin@warindong.com"
                                    className={`w-full px-3.5 py-2.5 text-sm border rounded-xl bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition ${errors.email ? 'border-red-300 bg-red-50' : 'border-gray-200'}`}
                                    required
                                    autoFocus
                                />
                                {errors.email && <p className="mt-1.5 text-xs text-red-500">{errors.email}</p>}
                            </div>

                            <div className="mb-5">
                                <label className="block text-sm font-medium text-gray-700 mb-1.5">Password</label>
                                <input
                                    type="password"
                                    value={data.password}
                                    onChange={(e) => setData('password', e.target.value)}
                                    placeholder="••••••••"
                                    className={`w-full px-3.5 py-2.5 text-sm border rounded-xl bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition ${errors.password ? 'border-red-300 bg-red-50' : 'border-gray-200'}`}
                                    required
                                />
                                {errors.password && <p className="mt-1.5 text-xs text-red-500">{errors.password}</p>}
                            </div>

                            <div className="flex items-center mb-5">
                                <input
                                    type="checkbox"
                                    id="remember"
                                    checked={data.remember}
                                    onChange={(e) => setData('remember', e.target.checked)}
                                    className="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                                />
                                <label htmlFor="remember" className="ml-2 text-sm text-gray-600">Ingat saya</label>
                            </div>

                            <button
                                type="submit"
                                disabled={processing}
                                className="w-full bg-indigo-600 hover:bg-indigo-700 disabled:opacity-60 text-white text-sm font-medium py-2.5 rounded-xl transition-colors duration-200"
                            >
                                {processing ? 'Memproses...' : 'Masuk ke Dashboard'}
                            </button>
                        </form>
                    </motion.div>

                    <p className="text-center text-xs text-gray-400 mt-5">&copy; {new Date().getFullYear()} Warindong. All rights reserved.</p>
                </motion.div>
            </div>
        </>
    );
}
