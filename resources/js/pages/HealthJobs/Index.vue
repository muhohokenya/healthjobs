<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';
import { useAuth } from '@/utils/auth';
import { Head, Link, router } from '@inertiajs/vue3';
import { reactive } from 'vue';
const user = useAuth();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Jobs Pages',
        href: '/dashboard',
    },
];

const props = defineProps({
    jobs: Object,
    filters: Object,
    isProfileComplete: Boolean,
});

const searchForm = reactive({
    search: props.filters.search || '',
    job_type: props.filters.job_type || '',
    experience_level: props.filters.experience_level || '',
});

const search = () => {
    router.get(route('health-jobs.index'), searchForm, {
        preserveState: true,
        replace: true,
    });
};

const formatJobType = (type) => {
    return type
        .split('-')
        .map((word) => word.charAt(0).toUpperCase() + word.slice(1))
        .join(' ');
};

const formatExperienceLevel = (level) => {
    return level.charAt(0).toUpperCase() + level.slice(1) + ' Level';
};

const formatSalary = (salary) => {
    return new Intl.NumberFormat('en-US').format(salary);
};
</script>

<template>
    <Head title="Health Job" />

    <AppLayout>
        <div class="min-h-screen bg-gray-50 py-8 dark:bg-gray-900">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Health Jobs</h1>
                    <p class="mt-2 text-gray-600 dark:text-gray-300">Find your next healthcare opportunity</p>
                </div>

                <div v-if="!props.isProfileComplete" class="mb-6 rounded-md bg-red-100 p-4 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                    ⚠️ Please complete your profile to unlock full job details and apply.
                    <Link :href="route('profile.update')" class="ml-3 text-blue-600 underline">Complete Profile</Link>
                </div>

                <!-- Create Job Button (Conditional) -->
                <div v-if="user.hasPermission('create-job-postings')" class="mb-6 flex justify-end">
                    <Link
                        class="flex items-center rounded-md bg-blue-600 px-4 py-2.5 font-medium text-white transition hover:bg-blue-700 focus:ring-2 focus:ring-blue-400 focus:outline-none"
                        :href="route('health-jobs.create')"
                        >Create New Job</Link
                    >
                </div>

                <!-- Filters -->
                <div class="mb-6 rounded-lg bg-white p-6 shadow dark:bg-gray-800 dark:shadow-gray-700/25">
                    <h2 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Filter Jobs</h2>
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
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
                            <label for="job_type_filter" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Job Type</label>
                            <select
                                v-model="searchForm.job_type"
                                id="job_type_filter"
                                class="w-full rounded-md border border-gray-300 bg-white px-4 py-2.5 text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:focus:border-blue-400 dark:focus:ring-blue-400"
                            >
                                <option value="">All Job Types</option>
                                <option value="full-time">Full Time</option>
                                <option value="part-time">Part Time</option>
                                <option value="contract">Contract</option>
                            </select>
                        </div>
                        <div>
                            <label for="experience_level_filter" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300"
                                >Experience Level</label
                            >
                            <select
                                v-model="searchForm.experience_level"
                                id="experience_level_filter"
                                class="w-full rounded-md border border-gray-300 bg-white px-4 py-2.5 text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:focus:border-blue-400 dark:focus:ring-blue-400"
                            >
                                <option value="">All Levels</option>
                                <option value="entry">Entry Level</option>
                                <option value="mid">Mid Level</option>
                                <option value="senior">Senior Level</option>
                            </select>
                        </div>
                        <div class="flex items-end">
                            <button
                                @click="search"
                                class="mr-3 w-full rounded-md bg-blue-600 px-4 py-2.5 text-white transition-colors hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:bg-blue-500 dark:hover:bg-blue-600 dark:focus:ring-offset-gray-800"
                            >
                                Apply Filters
                            </button>

                            <Link
                                class="w-full rounded-md bg-red-400 px-4 py-2.5 text-white transition-colors hover:bg-red-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:bg-red-500 dark:hover:bg-red-600 dark:focus:ring-offset-gray-800"
                                :href="route('health-jobs.index')"
                                >Clear Filters</Link
                            >
                        </div>
                    </div>
                </div>

                <!-- Jobs Grid -->
                <div class="mb-8 grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                    <div
                        v-for="job in props.jobs.data"
                        :key="job.id"
                        class="group relative overflow-hidden rounded-2xl border border-gray-100 bg-white p-8 shadow-sm transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl hover:shadow-blue-500/10 dark:border-gray-700/50 dark:bg-gray-800/50 dark:backdrop-blur-sm dark:hover:shadow-gray-700/25"
                    >
                        <!-- Decorative gradient background -->
                        <div
                            class="absolute -top-8 -right-8 h-24 w-24 rounded-full bg-gradient-to-br from-blue-400/10 to-purple-400/10 transition-transform duration-300 group-hover:scale-150 dark:from-blue-500/20 dark:to-purple-500/20"
                        ></div>

                        <!-- Header section with enhanced typography hierarchy -->
                        <div class="relative mb-6 flex items-start justify-between">
                            <div class="flex-1 pr-4">
                                <h3
                                    class="mb-2 text-xl leading-tight font-bold text-gray-900 transition-colors duration-200 group-hover:text-blue-600 dark:text-white dark:group-hover:text-blue-400"
                                >
                                    {{ job.title }}
                                </h3>

                                <!-- Company info with enhanced styling -->
                                <div class="mb-3 flex items-center space-x-2">
                                    <div class="flex h-8 w-8 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-700">
                                        <svg class="h-4 w-4 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                fill-rule="evenodd"
                                                d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-6a1 1 0 00-1-1H9a1 1 0 00-1 1v6a1 1 0 01-1 1H4a1 1 0 110-2V4z"
                                                clip-rule="evenodd"
                                            />
                                        </svg>
                                    </div>
                                    <span v-if="props.isProfileComplete" class="text-base font-medium text-gray-700 dark:text-gray-300">
                                        {{ job.company }}
                                    </span>
                                    <span v-else class="text-base text-gray-400/80 italic dark:text-gray-500"> [Hidden – complete profile] </span>
                                </div>
                            </div>

                            <!-- Enhanced job type badge -->
                            <div class="relative">
                                <span
                                    class="inline-flex items-center rounded-full bg-gradient-to-r from-blue-50 to-indigo-50 px-4 py-2 text-sm font-semibold text-blue-700 ring-1 ring-blue-200/50 transition-all duration-200 group-hover:from-blue-100 group-hover:to-indigo-100 group-hover:ring-blue-300/50 dark:from-blue-900/30 dark:to-indigo-900/30 dark:text-blue-300 dark:ring-blue-800/50"
                                >
                                    {{ formatJobType(job.job_type) }}
                                </span>
                            </div>
                        </div>

                        <!-- Location with improved visual design -->
                        <div class="mb-6 flex items-center space-x-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-green-50 dark:bg-green-900/30">
                                <svg class="h-5 w-5 text-green-600 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        fill-rule="evenodd"
                                        d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                            </div>
                            <div>
                                <span v-if="props.isProfileComplete" class="text-base font-medium text-gray-700 dark:text-gray-300">
                                    {{ job.location }}
                                </span>
                                <span v-else class="text-base text-gray-400/80 italic dark:text-gray-500"> [Hidden] </span>
                            </div>
                        </div>

                        <!-- Description with improved readability -->
                        <div class="mb-6">
                            <p class="line-clamp-3 leading-relaxed text-gray-600 dark:text-gray-300">{{ job.description.substring(0, 150) }}...</p>
                        </div>

                        <!-- Enhanced bottom section with better visual hierarchy -->
                        <div class="flex items-end justify-between border-t border-gray-50 pt-6 dark:border-gray-700/50">
                            <div class="space-y-3">
                                <!-- Salary with prominent styling -->
                                <div class="flex items-center space-x-2">
                                    <small class="text-sm font-bold text-green-600 dark:text-green-400">
                                        Ksh {{ formatSalary(job.salary_min) }} - {{ formatSalary(job.salary_max) }}
                                    </small>
                                </div>

                                <!-- Experience level with subtle styling -->
                                <div class="flex items-center space-x-2">
                                    <div class="flex h-6 w-6 items-center justify-center rounded-full bg-purple-50 dark:bg-purple-900/30">
                                        <svg class="h-3 w-3 text-purple-600 dark:text-purple-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                fill-rule="evenodd"
                                                d="M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v6.5A1.5 1.5 0 0116.5 16h-13A1.5 1.5 0 012 14.5V8a2 2 0 012-2h2zm4-1a1 1 0 00-1 1v1h2V6a1 1 0 00-1-1z"
                                                clip-rule="evenodd"
                                            />
                                        </svg>
                                    </div>
                                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                        {{ formatExperienceLevel(job.experience_level) }}
                                    </span>
                                </div>
                            </div>

                            <!-- Enhanced action button -->
                            <div class="ml-6">
                                <Link
                                    v-if="props.isProfileComplete"
                                    :href="route('health-jobs.show', job.id)"
                                    class="group/btn inline-flex items-center space-x-2 rounded-xl bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-blue-500/25 transition-all duration-200 hover:from-blue-700 hover:to-blue-800 hover:shadow-xl hover:shadow-blue-500/30 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:from-blue-500 dark:to-blue-600 dark:shadow-blue-500/20 dark:hover:from-blue-600 dark:hover:to-blue-700 dark:focus:ring-offset-gray-800"
                                >
                                    <span>View Details</span>
                                    <svg
                                        class="h-4 w-4 transition-transform duration-200 group-hover/btn:translate-x-0.5"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </Link>

                                <button
                                    v-else
                                    disabled
                                    class="inline-flex cursor-not-allowed items-center space-x-2 rounded-xl bg-gray-100 px-6 py-3 text-sm font-medium text-gray-400 dark:bg-gray-700 dark:text-gray-500"
                                >
                                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            fill-rule="evenodd"
                                            d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                    <small>Complete Profile to View</small>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pagination -->
                <div class="flex justify-center" v-if="jobs.links.length > 3">
                    <div class="flex space-x-1">
                        <!--                    <Link-->
                        <!--                        v-for="link in jobs.links"-->
                        <!--                        :key="link.label"-->
                        <!--                        :href="link.url"-->
                        <!--                        v-html="link.label"-->
                        <!--                        class="px-3 py-2 text-sm border rounded"-->
                        <!--                        :class="{-->
                        <!--                            'bg-blue-600 text-white border-blue-600': link.active,-->
                        <!--                            'bg-white text-gray-700 border-gray-300 hover:bg-gray-50': !link.active && link.url,-->
                        <!--                            'bg-gray-100 text-gray-400 border-gray-300 cursor-not-allowed': !link.url-->
                        <!--                        }"-->
                        <!--                    />-->
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
