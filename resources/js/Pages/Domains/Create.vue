<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    methods: Array,
    notifyOptions: Array,
});

const form = useForm({
    url: '',
    name: '',
    check_interval: 5,
    timeout: 10,
    http_method: 'GET',
    expected_content: '',
    notify_channel: 'email',
    is_active: true,
    history_days: 30,
});

const submit = () => {
    form.post(route('domains.store'));
};
</script>

<template>
    <Head title="Add Domain" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Add Domain
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-2xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <form @submit.prevent="submit" class="p-6 space-y-6">
                        <div>
                            <InputLabel for="url" value="URL *" />
                            <TextInput
                                id="url"
                                type="url"
                                class="mt-1 block w-full"
                                v-model="form.url"
                                required
                                placeholder="https://example.com"
                            />
                            <InputError class="mt-2" :message="form.errors.url" />
                        </div>

                        <div>
                            <InputLabel for="name" value="Name (optional)" />
                            <TextInput
                                id="name"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.name"
                                placeholder="My Website"
                            />
                            <InputError class="mt-2" :message="form.errors.name" />
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <InputLabel for="check_interval" value="Check Interval (min)" />
                                <TextInput
                                    id="check_interval"
                                    type="number"
                                    class="mt-1 block w-full"
                                    v-model="form.check_interval"
                                    min="1"
                                    max="1440"
                                />
                                <InputError class="mt-2" :message="form.errors.check_interval" />
                            </div>

                            <div>
                                <InputLabel for="timeout" value="Timeout (sec)" />
                                <TextInput
                                    id="timeout"
                                    type="number"
                                    class="mt-1 block w-full"
                                    v-model="form.timeout"
                                    min="1"
                                    max="60"
                                />
                                <InputError class="mt-2" :message="form.errors.timeout" />
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <InputLabel for="http_method" value="HTTP Method" />
                                <select
                                    id="http_method"
                                    v-model="form.http_method"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                >
                                    <option v-for="m in methods" :key="m.value" :value="m.value">
                                        {{ m.value }}
                                    </option>
                                </select>
                                <InputError class="mt-2" :message="form.errors.http_method" />
                            </div>

                            <div>
                                <InputLabel for="notify_channel" value="Notify Channel" />
                                <select
                                    id="notify_channel"
                                    v-model="form.notify_channel"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                >
                                    <option v-for="opt in notifyOptions" :key="opt.value" :value="opt.value">
                                        {{ opt.label }}
                                    </option>
                                </select>
                                <InputError class="mt-2" :message="form.errors.notify_channel" />
                            </div>
                        </div>

                        <div>
                            <InputLabel for="history_days" value="History (days)" />
                            <TextInput
                                id="history_days"
                                type="number"
                                class="mt-1 block w-full"
                                v-model="form.history_days"
                                min="1"
                                max="365"
                            />
                            <p class="mt-1 text-sm text-gray-500">How long to keep check history</p>
                            <InputError class="mt-2" :message="form.errors.history_days" />
                        </div>

                        <div>
                            <InputLabel for="expected_content" value="Expected Content (optional)" />
                            <textarea
                                id="expected_content"
                                v-model="form.expected_content"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                rows="3"
                                placeholder="Text that should be present on the page"
                            ></textarea>
                            <InputError class="mt-2" :message="form.errors.expected_content" />
                        </div>

                        <div class="flex items-center">
                            <input
                                id="is_active"
                                type="checkbox"
                                v-model="form.is_active"
                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                            />
                            <label for="is_active" class="ml-2 text-sm text-gray-600">
                                Active (enable monitoring)
                            </label>
                        </div>

                        <div class="flex items-center justify-end gap-4">
                            <Link :href="route('domains.index')" class="text-sm text-gray-600 hover:text-gray-900">
                                Cancel
                            </Link>
                            <PrimaryButton :disabled="form.processing">
                                Create Domain
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
