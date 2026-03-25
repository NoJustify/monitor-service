<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';

defineProps({
    domains: Object,
});

const deleteDomain = (id) => {
    if (confirm('Are you sure you want to delete this domain?')) {
        router.delete(route('domains.destroy', id));
    }
};

const statusClass = (status) => {
    if (status === null) return 'bg-gray-100 text-gray-800';
    return status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
};

const statusText = (status) => {
    if (status === null) return 'Pending';
    return status ? 'Online' : 'Offline';
};
</script>

<template>
    <Head title="Domains" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Domains
                </h2>
                <Link
                    :href="route('domains.create')"
                    class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500"
                >
                    Add Domain
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <table class="min-w-full divide-y divide-gray-200" v-if="domains.data.length">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Name / URL</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Interval</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Last Check</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Checks</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                <tr v-for="domain in domains.data" :key="domain.id">
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ domain.name || domain.url }}
                                        </div>
                                        <div class="text-sm text-gray-500" v-if="domain.name">
                                            {{ domain.url }}
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <span
                                            :class="statusClass(domain.last_status)"
                                            class="inline-flex rounded-full px-2 text-xs font-semibold leading-5"
                                        >
                                            {{ statusText(domain.last_status) }}
                                        </span>
                                        <span v-if="!domain.is_active" class="ml-2 text-xs text-gray-400">(paused)</span>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                        {{ domain.check_interval }} min
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                        <span v-if="domain.last_check">
                                            {{ domain.last_check.response_time }}ms
                                        </span>
                                        <span v-else>-</span>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                        {{ domain.checks_count }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium">
                                        <Link :href="route('domains.show', domain.id)" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                            View
                                        </Link>
                                        <Link :href="route('domains.edit', domain.id)" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                            Edit
                                        </Link>
                                        <button @click="deleteDomain(domain.id)" class="text-red-600 hover:text-red-900">
                                            Delete
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <div v-else class="text-center py-8 text-gray-500">
                            No domains yet.
                            <Link :href="route('domains.create')" class="text-indigo-600 hover:text-indigo-900">
                                Add your first domain
                            </Link>
                        </div>

                        <!-- Pagination -->
                        <div v-if="domains.links && domains.links.length > 3" class="mt-6 flex justify-center">
                            <nav class="flex space-x-2">
                                <Link
                                    v-for="link in domains.links"
                                    :key="link.label"
                                    :href="link.url || '#'"
                                    :class="[
                                        'px-3 py-2 text-sm rounded-md',
                                        link.active ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200',
                                        !link.url ? 'opacity-50 cursor-not-allowed' : ''
                                    ]"
                                    v-html="link.label"
                                />
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
