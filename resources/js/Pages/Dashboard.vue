<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    stats: Object,
    recentChecks: Array,
    offlineDomains: Array,
});

const formatDate = (date) => new Date(date).toLocaleString();

const statusClass = (success) => {
    return success ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
};
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 space-y-6">
                <!-- Stats -->
                <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <div class="text-sm font-medium text-gray-500">Total Domains</div>
                        <div class="mt-1 text-3xl font-semibold text-gray-900">{{ stats.total }}</div>
                    </div>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <div class="text-sm font-medium text-gray-500">Active</div>
                        <div class="mt-1 text-3xl font-semibold text-gray-900">{{ stats.active }}</div>
                    </div>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <div class="text-sm font-medium text-gray-500">Online</div>
                        <div class="mt-1 text-3xl font-semibold text-green-600">{{ stats.online }}</div>
                    </div>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <div class="text-sm font-medium text-gray-500">Offline</div>
                        <div class="mt-1 text-3xl font-semibold text-red-600">{{ stats.offline }}</div>
                    </div>
                </div>

                <!-- Offline Domains Alert -->
                <div v-if="offlineDomains.length" class="bg-red-50 border border-red-200 rounded-lg p-4">
                    <h3 class="text-lg font-medium text-red-800 mb-2">⚠️ Offline Domains</h3>
                    <ul class="space-y-1">
                        <li v-for="domain in offlineDomains" :key="domain.id" class="flex items-center justify-between">
                            <Link :href="route('domains.show', domain.id)" class="text-red-700 hover:underline">
                                {{ domain.name || domain.url }}
                            </Link>
                            <span class="text-sm text-red-600">
                                {{ domain.last_check?.error || 'No response' }}
                            </span>
                        </li>
                    </ul>
                </div>

                <!-- Recent Checks -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Recent Checks</h3>
                        <table class="min-w-full divide-y divide-gray-200" v-if="recentChecks.length">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium uppercase text-gray-500">Domain</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium uppercase text-gray-500">Status</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium uppercase text-gray-500">Code</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium uppercase text-gray-500">Time</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium uppercase text-gray-500">Date</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <tr v-for="check in recentChecks" :key="check.id">
                                    <td class="px-4 py-2 text-sm">
                                        <Link :href="route('domains.show', check.domain_id)" class="text-indigo-600 hover:underline">
                                            {{ check.domain?.name || check.domain?.url }}
                                        </Link>
                                    </td>
                                    <td class="px-4 py-2">
                                        <span :class="statusClass(check.is_success)" class="inline-flex rounded-full px-2 text-xs font-semibold">
                                            {{ check.is_success ? 'OK' : 'FAIL' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2 text-sm text-gray-500">{{ check.status_code || '-' }}</td>
                                    <td class="px-4 py-2 text-sm text-gray-500">{{ check.response_time }}ms</td>
                                    <td class="px-4 py-2 text-sm text-gray-500">{{ formatDate(check.created_at) }}</td>
                                </tr>
                            </tbody>
                        </table>
                        <div v-else class="text-center py-8 text-gray-500">
                            No checks yet.
                            <Link :href="route('domains.create')" class="text-indigo-600 hover:underline">Add your first domain</Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
