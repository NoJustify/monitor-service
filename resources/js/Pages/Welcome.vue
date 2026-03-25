<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
});
</script>

<template>
    <Head title="Monitor Service" />
    <div class="min-h-screen bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-900">
        <header class="px-6 py-6 lg:px-8">
            <nav class="flex items-center justify-between max-w-7xl mx-auto">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-indigo-500">
                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <span class="text-xl font-bold text-white">Monitor</span>
                </div>
                <div v-if="canLogin" class="flex items-center gap-4">
                    <Link v-if="$page.props.auth.user" :href="route('dashboard')" class="text-sm font-medium text-white hover:text-indigo-300">
                        Dashboard
                    </Link>
                    <template v-else>
                        <Link :href="route('login')" class="text-sm font-medium text-white hover:text-indigo-300">
                            Log in
                        </Link>
                        <Link v-if="canRegister" :href="route('register')" class="rounded-lg bg-indigo-500 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-400">
                            Get Started
                        </Link>
                    </template>
                </div>
            </nav>
        </header>

        <main>
            <section class="py-20 px-6 lg:px-8">
                <div class="max-w-4xl mx-auto text-center">
                    <h1 class="text-4xl sm:text-6xl font-bold text-white mb-6">
                        Website Monitoring
                        <span class="text-indigo-400">Made Simple</span>
                    </h1>
                    <p class="text-xl text-slate-300 mb-10 max-w-2xl mx-auto">
                        Monitor your websites 24/7. Get instant alerts when something goes wrong.
                        Track uptime, response times, and more.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <Link v-if="canRegister" :href="route('register')" class="rounded-lg bg-indigo-500 px-8 py-3 text-lg font-semibold text-white hover:bg-indigo-400 transition">
                            Start Free
                        </Link>
                        <Link v-if="canLogin && !$page.props.auth.user" :href="route('login')" class="rounded-lg border border-slate-600 px-8 py-3 text-lg font-semibold text-white hover:bg-slate-800 transition">
                            Sign In
                        </Link>
                    </div>
                </div>
            </section>

            <section class="py-16 px-6 lg:px-8">
                <div class="max-w-6xl mx-auto">
                    <div class="grid md:grid-cols-3 gap-8">
                        <div class="bg-slate-800/50 rounded-2xl p-8 border border-slate-700">
                            <div class="w-12 h-12 bg-green-500/20 rounded-lg flex items-center justify-center mb-4">
                                <svg class="w-6 h-6 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-white mb-2">Uptime Monitoring</h3>
                            <p class="text-slate-400">Check your sites every minute. Know the moment they go down.</p>
                        </div>

                        <div class="bg-slate-800/50 rounded-2xl p-8 border border-slate-700">
                            <div class="w-12 h-12 bg-yellow-500/20 rounded-lg flex items-center justify-center mb-4">
                                <svg class="w-6 h-6 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-white mb-2">Instant Alerts</h3>
                            <p class="text-slate-400">Get notified via Email or Telegram when issues occur.</p>
                        </div>

                        <div class="bg-slate-800/50 rounded-2xl p-8 border border-slate-700">
                            <div class="w-12 h-12 bg-blue-500/20 rounded-lg flex items-center justify-center mb-4">
                                <svg class="w-6 h-6 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-white mb-2">Response Time</h3>
                            <p class="text-slate-400">Track performance metrics and response times over time.</p>
                        </div>
                    </div>
                </div>
            </section>

            <section class="py-16 px-6 lg:px-8">
                <div class="max-w-4xl mx-auto">
                    <h2 class="text-3xl font-bold text-white text-center mb-12">How It Works</h2>
                    <div class="space-y-8">
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 w-8 h-8 bg-indigo-500 rounded-full flex items-center justify-center text-white font-bold">1</div>
                            <div>
                                <h3 class="text-lg font-semibold text-white">Add Your Domain</h3>
                                <p class="text-slate-400">Enter your website URL and configure check intervals.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 w-8 h-8 bg-indigo-500 rounded-full flex items-center justify-center text-white font-bold">2</div>
                            <div>
                                <h3 class="text-lg font-semibold text-white">We Monitor 24/7</h3>
                                <p class="text-slate-400">Our system checks your site at your chosen interval.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 w-8 h-8 bg-indigo-500 rounded-full flex items-center justify-center text-white font-bold">3</div>
                            <div>
                                <h3 class="text-lg font-semibold text-white">Get Notified</h3>
                                <p class="text-slate-400">Receive instant alerts when your site goes down.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <footer class="py-8 px-6 border-t border-slate-800">
            <div class="max-w-7xl mx-auto text-center text-slate-500 text-sm">
                Monitor Service
            </div>
        </footer>
    </div>
</template>
