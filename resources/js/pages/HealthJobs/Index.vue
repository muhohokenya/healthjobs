<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { useAuth } from '@/utils/auth';
import { Head, Link, router } from '@inertiajs/vue3';
import { reactive } from 'vue';

const user = useAuth();

const props = defineProps({
    jobs: Object,
    locations: Object,
    filters: Object,
    isProfileComplete: Boolean,
});

const searchForm = reactive({
    search: props.filters.search || '',
    job_type: props.filters.job_type || '',
    time_filter: props.filters.time_filter || '',
    location: props.filters.location || '',
});

const search = (): void => {
    router.get(route('health-jobs.index'), searchForm, {
        preserveState: true,
        replace: true,
    });
};

const formatJobType = (type: string): string => {
    return type
        .split('-')
        .map((word) => word.charAt(0).toUpperCase() + word.slice(1))
        .join(' ');
};

// Helper function to format time ago
const formatTimeAgo = (dateString: string): string => {
    const date = new Date(dateString);
    const now = new Date();
    const diffInMinutes = Math.floor((now.getTime() - date.getTime()) / (1000 * 60));

    if (diffInMinutes < 1) return 'Just now';
    if (diffInMinutes < 60) return `${diffInMinutes} min ago`;

    const diffInHours = Math.floor(diffInMinutes / 60);
    if (diffInHours < 24) return `${diffInHours} hour${diffInHours > 1 ? 's' : ''} ago`;

    const diffInDays = Math.floor(diffInHours / 24);
    if (diffInDays < 7) return `${diffInDays} day${diffInDays > 1 ? 's' : ''} ago`;

    const diffInWeeks = Math.floor(diffInDays / 7);
    if (diffInWeeks < 4) return `${diffInWeeks} week${diffInWeeks > 1 ? 's' : ''} ago`;

    const diffInMonths = Math.floor(diffInDays / 30);
    if (diffInMonths < 12) return `${diffInMonths} month${diffInMonths > 1 ? 's' : ''} ago`;

    const diffInYears = Math.floor(diffInDays / 365);
    return `${diffInYears} year${diffInYears > 1 ? 's' : ''} ago`;
};

// Add this helper function to your script section
const truncateDescription = (description, maxLength = 150) => {
    if (!description) return '';

    // Remove HTML tags for character counting
    const textOnly = description.replace(/<[^>]*>/g, '');

    if (textOnly.length <= maxLength) {
        return description;
    }

    // Find the last complete word within the limit
    const truncated = textOnly.substring(0, maxLength);
    const lastSpace = truncated.lastIndexOf(' ');
    const finalText = lastSpace > 0 ? truncated.substring(0, lastSpace) : truncated;

    return finalText + '...';
};
</script>

<template>
    <Head title="Health Job" />

    <AppLayout>
        <div class="min-h-screen bg-gray-50 py-8 dark:bg-gray-900">
            <div class="mx-auto max-w-8xl px-4 sm:px-6 lg:px-8">
                <div class="mb-7">
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Health Jobs</h1>
                    <p class="mt-2 text-gray-600 dark:text-gray-300">Find your next healthcare opportunity</p>
                </div>

                <div v-if="!props.isProfileComplete" class="mb-6 rounded-md bg-red-100 p-4 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                    ⚠️ Kindly complete your profile to enhance the credibility of your posts.
                    <Link :href="route('profile.update')" class="ml-3 text-blue-600 underline">Complete Profile</Link>
                </div>

                <div v-if="user.hasPermission('create-job-postings')" class="mb-6 flex justify-end">
                    <Link
                        class="flex items-center rounded-md bg-blue-600 px-4 py-2.5 font-medium text-white transition hover:bg-blue-700 focus:ring-2 focus:ring-blue-400 focus:outline-none"
                        :href="route('health-jobs.create')"
                    >Create New Job</Link>
                </div>

                <div class="mb-6 rounded-lg bg-white p-6 shadow dark:bg-gray-800 dark:shadow-gray-700/25">
                    <h2 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Filter Jobs</h2>
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-5">
                        <div>
                            <label for="search" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Search</label>
                            <input
                                v-model="searchForm.search"
                                type="text"
                                id="search"
                                placeholder="Search jobs..."
                                class="w-full rounded-md border border-gray-300 bg-white px-4 py-2.5 text-gray-900 placeholder-gray-500 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-400 dark:focus:ring-blue-400"
                            />
                        </div>
                        <div>
                            <label for="time_filter" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">By Time</label>
                            <select
                                v-model="searchForm.time_filter"
                                id="time_filter"
                                class="w-full rounded-md border border-gray-300 bg-white px-4 py-2.5 text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:focus:border-blue-400 dark:focus:ring-blue-400"
                            >
                                <option value="">By time</option>
                                <option value="latest">Latest</option>
                                <option value="oldest">Oldest</option>
                            </select>
                        </div>

                        <div>
                            <label for="job_type_filter" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Job Type</label>
                            <select
                                v-model="searchForm.job_type"
                                id="job_type_filter"
                                class="w-full rounded-md border border-gray-300 bg-white px-4 py-2.5 text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:focus:border-blue-400 dark:focus:ring-blue-400"
                            >
                                <option value="">All Job Types</option>
                                <option value="full-time">Full Time</option>
                                <option value="part-time">Locum</option>
                            </select>
                        </div>
                        <div>
                            <label for="location" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Location</label>
                            <select
                                v-model="searchForm.location"
                                id="location"
                                class="w-full rounded-md border border-gray-300 bg-white px-4 py-2.5 text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:focus:border-blue-400 dark:focus:ring-blue-400"
                            >
                                <option value="">Select Location</option>
                                <option v-for="location in locations" :value="location.location" v-bind:key="location">{{location.location}}</option>
                            </select>
                        </div>
                        <div class="flex items-end">
                            <button
                                @click="search"
                                class="mr-3 w-full rounded-md bg-blue-600 px-4 py-2.5 text-white transition-colors hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:bg-blue-500 dark:hover:bg-blue-600 dark:focus:ring-offset-gray-800 cursor-pointer"
                            >
                                Apply Filters
                            </button>

                            <Link
                                class="w-full rounded-md bg-red-400 px-4 py-2.5 text-white transition-colors hover:bg-red-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:bg-red-500 dark:hover:bg-red-600 dark:focus:ring-offset-gray-800 cursor-pointer"
                                :href="route('health-jobs.index')"
                            >Clear Filters</Link>
                        </div>
                    </div>
                </div>

                <!-- Job cards section with consistent blue theme -->
                <div class="mb-8 grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                    <div
                        v-for="job in props.jobs.data"
                        :key="job.id"
                        class="group relative flex flex-col overflow-hidden rounded-xl border border-gray-200 bg-white shadow-md transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-blue-500/10 dark:border-gray-700 dark:bg-gray-800"
                    >
                        <!-- Blue gradient stripe for all jobs -->
                        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-blue-500 to-purple-500"></div>

                        <!-- Header Section -->
                        <div class="p-6 pb-4">
                            <!-- Time and License Badge Container -->
                            <div class="absolute top-4 right-4 flex items-center gap-2">
                                <!-- Small license warning badge for unlicensed users only -->
                                <div v-if="job.user.license_status !== 'active'" class="relative group/badge">
                                    <div class="flex h-5 w-5 items-center justify-center rounded-full bg-orange-100 text-orange-600 dark:bg-orange-900/30 dark:text-orange-400">
                                        <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <!-- Tooltip -->
                                    <div class="invisible group-hover/badge:visible absolute -top-8 right-0 z-10 whitespace-nowrap rounded bg-gray-800 px-2 py-1 text-xs text-white dark:bg-gray-600">
                                        Unverified poster
                                    </div>
                                </div>

                                <!-- Time Badge -->
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300">
                                    {{ formatTimeAgo(job.created_at) }}
                                </span>
                            </div>

                            <!-- Job Title -->
                            <h3 class="text-lg font-bold leading-tight mb-2 pr-20 line-clamp-2 text-gray-900 dark:text-white">
                                {{ job.title }}
                            </h3>

                            <!-- Job Type & Location -->
                            <div class="flex items-center justify-between mb-3">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300">
                                    {{ formatJobType(job.job_type) }}
                                </span>

                                <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                    </svg>
                                    {{ job.location }}
                                </div>
                            </div>
                        </div>

                        <!-- Content Section -->
                        <div class="px-6 pb-4 flex-1">
                            <!-- Description Preview -->
                            <div class="mb-4">
                                <div class="text-sm text-gray-600 dark:text-gray-400 line-clamp-3" v-html="truncateDescription(job.description, 150)"></div>
                            </div>

                            <!-- Key Qualifications Preview -->
                            <div v-if="job.qualifications && job.qualifications.length > 0" class="mb-4">
                                <h4 class="text-sm font-semibold mb-2 text-gray-800 dark:text-gray-200">
                                    Key Requirements:
                                </h4>
                                <ul class="space-y-1">
                                    <li
                                        v-for="(qualification, index) in job.qualifications.slice(0, 2)"
                                        :key="index"
                                        class="flex items-start text-xs text-gray-600 dark:text-gray-400"
                                    >
                                        <div class="w-1.5 h-1.5 rounded-full mt-1.5 mr-2 flex-shrink-0 bg-blue-500"></div>
                                        <span class="line-clamp-1">{{ qualification }}</span>
                                    </li>
                                    <li
                                        v-if="job.qualifications.length > 2"
                                        class="text-xs text-gray-500 dark:text-gray-400 italic"
                                    >
                                        +{{ job.qualifications.length - 2 }} more requirements
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- Footer Section -->
                        <div class="p-6 pt-4 border-t border-gray-100 dark:border-gray-700 mt-auto">
                            <Link
                                :href="route('health-jobs.show', job.uuid)"
                                class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-semibold rounded-lg transition-all duration-200 focus:ring-2 focus:ring-offset-2 border border-gray-300 bg-transparent text-gray-700 hover:bg-blue-600 hover:text-white hover:border-blue-600 focus:ring-blue-500 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-blue-600 dark:hover:text-white dark:hover:border-blue-600"
                            >
                                <span>View Details</span>
                                <svg
                                    class="w-4 h-4 ml-2 transition-transform group-hover:translate-x-1"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
