<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    domain: Object,
});

const statusClass = (success) => {
    return success ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
};

const formatDate = (date) => {
    return new Date(date).toLocaleString();
};
</script>

<template>
    <Head :title="domain.name || domain.url" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-semibold leading-tight text-gray-800">
                        {{ domain.name || domain.url }}
                    </h2>
                    <p class="text-sm text-gray-500" v-if="domain.name">{{ domain.url }}</p>
                </div>
                <div class="flex gap-2">
                    <Link
                        :href="route('domains.edit', domain.id)"
                        class="rounded-md bg-gray-100 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-200"
                    >
                        Edit
                    </Link>
                    <Link
                        :href="route('domains.index')"
                        class="rounded-md bg-gray-100 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-200"
                    >
                        Back
                    </Link>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 space-y-6">
                <!-- Domain Info -->
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Settings</h3>
                        <dl class="grid grid-cols-2 gap-4 sm:grid-cols-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">HTTP Method</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ domain.http_method }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Interval</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ domain.check_interval }} min</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Timeout</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ domain.timeout }} sec</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Notify Channel</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ domain.notify_channel }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Status</dt>
                                <dd class="mt-1">
                                    <span :class="domain.is_active ? 'text-green-600' : 'text-gray-400'">
                                        {{ domain.is_active ? 'Active' : 'Paused' }}
                                    </span>
                                </dd>
                            </div>
                            <div v-if="domain.expected_content" class="col-span-2">
                                <dt class="text-sm font-medium text-gray-500">Expected Content</dt>
                                <dd class="mt-1 text-sm text-gray-900 truncate">{{ domain.expected_content }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Check History -->
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Check History</h3>

                        <table class="min-w-full divide-y divide-gray-200" v-if="domain.checks?.length">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Code</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Response Time</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Content</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Error</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                <tr v-for="check in domain.checks" :key="check.id">
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                        {{ formatDate(check.created_at) }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <span
                                            :class="statusClass(check.is_success)"
                                            class="inline-flex rounded-full px-2 text-xs font-semibold leading-5"
                                        >
                                            {{ check.is_success ? 'OK' : 'FAIL' }}
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                        {{ check.status_code || '-' }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                        {{ check.response_time ? check.response_time + 'ms' : '-' }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm">
                                        <span v-if="check.content_matched === true" class="text-green-600">✓</span>
                                        <span v-else-if="check.content_matched === false" class="text-red-600">✗</span>
                                        <span v-else class="text-gray-400">-</span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-red-600 max-w-xs truncate">
                                        {{ check.error || '-' }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <div v-else class="text-center py-8 text-gray-500">
                            No checks yet. The first check will run soon.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
