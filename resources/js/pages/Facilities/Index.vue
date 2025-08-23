<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { useAuth } from '@/utils/auth';
const user = useAuth();

// eslint-disable-next-line @typescript-eslint/no-unused-vars
const props = defineProps({
    facilities: Object
})
</script>

<template>
    <AppLayout>
        <Head title="Index" />
        <div class="min-h-screen bg-gray-50 py-8 dark:bg-gray-900">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Manage Facilities</h1>
                    <p class="mt-2 text-gray-600 dark:text-gray-300">View and manage facilities</p>
                </div>

                <!-- Create Job Button (Conditional) -->
                <div class="mb-6 flex justify-end">
                    <Link
                        v-if="user.hasPermission('create-facility')"
                        class="flex items-center rounded-md bg-blue-600 px-4 py-2.5 font-medium text-white transition hover:bg-blue-700 focus:ring-2 focus:ring-blue-400 focus:outline-none"
                        :href="route('facilities.create')"
                    >
                        Create Facility
                    </Link>
                </div>

                <!-- Jobs Grid -->
                <div class="mb-8 grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                    <div
                        v-for="facility in facilities"
                        :key="facility.id"
                        class="rounded-lg border border-transparent bg-white p-6 shadow transition-all hover:shadow-lg dark:border-gray-700 dark:bg-gray-800 dark:shadow-gray-700/25 dark:hover:shadow-gray-700/40"
                    >
                        <div class="mb-3 flex items-start justify-between">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ facility.name }}</h3>
                            <span class="rounded-full bg-blue-100 px-2.5 py-1 text-xs font-medium text-blue-800 dark:bg-blue-900/50 dark:text-blue-300">
                                {{ facility.name }}
                            </span>
                        </div>

                        <p class="mb-2 text-gray-600 dark:text-gray-300">{{ facility.name }}</p>
                        <p class="mb-3 text-sm text-gray-500 dark:text-gray-400">📍 {{ facility.location }}</p>
                        <p class="mb-3 text-sm text-gray-500 dark:text-gray-400">📍 {{ facility.email }}</p>
                        <p class="mb-3 text-sm text-gray-500 dark:text-gray-400">📍 {{ facility.contact_number }}</p>


                        <div class="flex items-center justify-between">


                            <Link
                                class="rounded-md bg-blue-600 px-4 py-2 text-sm text-white transition-colors hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:bg-blue-500 dark:hover:bg-blue-600 dark:focus:ring-offset-gray-800"
                            >
                                View Details
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
