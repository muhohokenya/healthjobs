<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

// eslint-disable-next-line @typescript-eslint/no-unused-vars
const props = defineProps({
    errors: Object,
});

const form = useForm({
    name: 'Outspan Hospital',
    email: 'muhohodev@gmail.com',
    licence_number: 'BU202502224',
    contact_number: '0712666716',
    location: 'Nyeri, Opposite Outspan Plaza',
});
</script>

<template>
    <Head title="Index" />
    <AppLayout>
        <div class="min-h-screen bg-gray-50 py-8 dark:bg-gray-900">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Create New Facility</h1>
                    <p class="mt-2 text-gray-600 dark:text-gray-300">View and manage facilities</p>
                </div>

                <!-- Create Job Button (Conditional) -->
                <div class="mb-6 flex justify-end">
                    <Link
                        class="flex items-center rounded-md bg-blue-600 px-4 py-2.5 font-medium text-white transition hover:bg-blue-700 focus:ring-2 focus:ring-blue-400 focus:outline-none"
                        :href="route('facilities.index')"
                    >
                        Back
                    </Link>
                </div>

                <form @submit.prevent="form.post(route('facilities.store'))" class="space-y-6 rounded-lg bg-white p-8 shadow-lg dark:bg-gray-800">
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <!-- Name -->
                        <div>
                            <label for="name" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Facility Name *</label>
                            <input
                                v-model="form.name"
                                type="text"
                                name="name"
                                id="name"
                                placeholder="e.g. Name"
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-gray-900 placeholder-gray-500 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-400 dark:focus:ring-blue-400"
                            />
                            <div v-if="errors.name" class="mt-1 text-sm text-red-500">{{ errors.name }}</div>
                        </div>

                        <!-- email -->
                        <div>
                            <label for="email" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Contact Email *</label>
                            <input
                                v-model="form.email"
                                type="text"
                                name="email"
                                id="email"
                                placeholder="e.g. john@gmail.com."
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-gray-900 placeholder-gray-500 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-400 dark:focus:ring-blue-400"
                            />
                            <div v-if="errors.email" class="mt-1 text-sm text-red-500">{{ errors.email }}</div>
                        </div>
                    </div>


                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <!-- Licence -->
                        <div>
                            <label for="licence_number" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Licence Number *</label>
                            <input
                                v-model="form.licence_number"
                                type="text"
                                name="name"
                                id="name"
                                placeholder="e.g. Name"
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-gray-900 placeholder-gray-500 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-400 dark:focus:ring-blue-400"
                            />
                            <div v-if="errors.licence_number" class="mt-1 text-sm text-red-500">{{ errors.licence_number }}</div>
                        </div>

                        <!-- Licence -->
                        <div>
                            <label for="licence_number" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Location *</label>
                            <input
                                v-model="form.location"
                                type="text"
                                name="name"
                                id="name"
                                placeholder="e.g. Name"
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-gray-900 placeholder-gray-500 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-400 dark:focus:ring-blue-400"
                            />
                            <div v-if="errors.location" class="mt-1 text-sm text-red-500">{{ errors.location }}</div>
                        </div>

                        <!-- email -->
                        <div>
                            <label for="email" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Contact Number *</label>
                            <input
                                v-model="form.contact_number"
                                type="text"
                                name="email"
                                id="email"
                                placeholder="e.g. john@gmail.com."
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-gray-900 placeholder-gray-500 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-400 dark:focus:ring-blue-400"
                            />
                            <div v-if="errors.email" class="mt-1 text-sm text-red-500">{{ errors.email }}</div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="flex w-full items-center justify-center rounded-lg bg-blue-600 px-6 py-2 text-lg font-semibold text-white transition hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:outline-none disabled:opacity-50 dark:bg-blue-500 dark:hover:bg-blue-600 dark:focus:ring-blue-400 dark:focus:ring-offset-gray-800"
                        >
                            <svg
                                v-if="form.processing"
                                class="mr-2 h-5 w-5 animate-spin text-white"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                            >
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path
                                    class="opacity-75"
                                    fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                                ></path>
                            </svg>
                            {{ form.processing ? 'Creating Job...' : 'Create Facility' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
