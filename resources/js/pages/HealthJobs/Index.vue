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


const formatSalary = (salary: number): string => {
    return new Intl.NumberFormat('en-US').format(salary);
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
                    ⚠️ Please complete your profile to unlock full job details and apply.
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

                <div class="mb-8 grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                    <div
                        v-for="job in props.jobs.data"
                        :key="job.id"
                        :class="[
                            'group relative flex flex-col overflow-hidden rounded-2xl border transition-all duration-300',
                            // Licensed user styling (trusted)
                            job.user.license_status === 'active'
                                ? 'border-gray-100 bg-white shadow-sm hover:-translate-y-1 hover:shadow-xl hover:shadow-blue-500/10 dark:border-gray-700/50 dark:bg-gray-800/50 dark:backdrop-blur-sm dark:hover:shadow-gray-700/25'
                                // Unlicensed user styling (untrustworthy)
                                : 'border-red-200/60 bg-gradient-to-br from-red-50/30 to-orange-50/30 shadow-sm opacity-75 hover:opacity-85 dark:border-red-900/40 dark:from-red-950/20 dark:to-orange-950/20 dark:bg-gray-800/30'
                        ]"
                    >
                        <!-- Warning overlay for unlicensed users -->
                        <div
                            v-if="job.user.license_status !== 'active'"
                            class="absolute inset-0 bg-gradient-to-br from-red-500/5 to-orange-500/5 dark:from-red-500/10 dark:to-orange-500/10 pointer-events-none"
                        ></div>

                        <div
                            :class="[
                                'absolute -top-8 -right-8 h-24 w-24 rounded-full transition-transform duration-300 group-hover:scale-150',
                                job.user.license_status === 'active'
                                    ? 'bg-gradient-to-br from-blue-400/10 to-purple-400/10 dark:from-blue-500/20 dark:to-purple-500/20'
                                    : 'bg-gradient-to-br from-red-400/10 to-orange-400/10 dark:from-red-500/20 dark:to-orange-500/20'
                            ]"
                        ></div>

                        <!-- Header Section -->
                        <div class="relative p-6 pb-0">
                            <!-- Time Posted Badge -->
                            <div
                                :class="[
                                    'absolute top-4 right-4 flex items-center space-x-1 rounded-full px-3 py-1 backdrop-blur-sm',
                                    job.user.license_status === 'active'
                                        ? 'bg-gray-100/80 dark:bg-gray-700/80'
                                        : 'bg-red-100/80 dark:bg-red-900/60'
                                ]"
                            >
                                <svg class="h-3 w-3 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                </svg>
                                <span
                                    :class="[
                                        'text-xs font-medium',
                                        job.user.license_status === 'active'
                                            ? 'text-gray-600 dark:text-gray-300'
                                            : 'text-red-600 dark:text-red-300'
                                    ]"
                                >
                                    {{ formatTimeAgo(job.created_at) }}
                                </span>
                            </div>

                            <div class="mb-4 flex items-start justify-between pr-20">
                                <div class="flex-1 pr-4">
                                    <h3
                                        :class="[
                                            'mb-2 text-xl font-bold leading-tight transition-colors duration-200',
                                            job.user.license_status === 'active'
                                                ? 'text-gray-900 group-hover:text-blue-600 dark:text-white dark:group-hover:text-blue-400'
                                                : 'text-gray-700 group-hover:text-red-600 dark:text-gray-300 dark:group-hover:text-red-400'
                                        ]"
                                    >
                                        {{ job.title }}
                                    </h3>

                                    <div class="mb-3 flex items-center space-x-2">
                                        <div
                                            :class="[
                                                'flex h-8 w-8 items-center justify-center rounded-full',
                                                job.user.license_status === 'active'
                                                    ? 'bg-gray-100 dark:bg-gray-700'
                                                    : 'bg-red-100 dark:bg-red-900/50'
                                            ]"
                                        >
                                            <svg
                                                :class="[
                                                    'h-4 w-4',
                                                    job.user.license_status === 'active'
                                                        ? 'text-gray-500 dark:text-gray-400'
                                                        : 'text-red-500 dark:text-red-400'
                                                ]"
                                                fill="currentColor"
                                                viewBox="0 0 20 20"
                                            >
                                                <path
                                                    fill-rule="evenodd"
                                                    d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-6a1 1 0 00-1-1H9a1 1 0 00-1 1v6a1 1 0 01-1 1H4a1 1 0 110-2V4z"
                                                    clip-rule="evenodd"
                                                />
                                            </svg>
                                        </div>
                                    </div>
                                </div>

                                <div class="relative">
                                    <span
                                        :class="[
                                            'inline-flex items-center rounded-full px-3 py-1.5 text-xs font-semibold ring-1 transition-all duration-200',
                                            job.user.license_status === 'active'
                                                ? 'bg-gradient-to-r from-blue-50 to-indigo-50 text-blue-700 ring-blue-200/50 group-hover:from-blue-100 group-hover:to-indigo-100 group-hover:ring-blue-300/50 dark:from-blue-900/30 dark:to-indigo-900/30 dark:text-blue-300 dark:ring-blue-800/50'
                                                : 'bg-gradient-to-r from-red-50 to-orange-50 text-red-700 ring-red-200/50 group-hover:from-red-100 group-hover:to-orange-100 group-hover:ring-red-300/50 dark:from-red-900/30 dark:to-orange-900/30 dark:text-red-300 dark:ring-red-800/50'
                                        ]"
                                    >
                                        {{ formatJobType(job.job_type) }}
                                    </span>
                                </div>
                            </div>

                            <div class="mb-4 flex items-center space-x-3">
                                <div
                                    :class="[
                                        'flex h-8 w-8 items-center justify-center rounded-full',
                                        job.user.license_status === 'active'
                                            ? 'bg-green-50 dark:bg-green-900/30'
                                            : 'bg-red-50 dark:bg-red-900/30'
                                    ]"
                                >
                                    <svg
                                        :class="[
                                            'h-4 w-4',
                                            job.user.license_status === 'active'
                                                ? 'text-green-600 dark:text-green-400'
                                                : 'text-red-600 dark:text-red-400'
                                        ]"
                                        fill="currentColor"
                                        viewBox="0 0 20 20"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                </div>
                                {{job.location}}
                                <div>
                                    <span
                                        :class="[
                                            'text-sm font-medium',
                                            job.user.license_status === 'active'
                                                ? 'text-gray-700 dark:text-gray-300'
                                                : 'text-red-600 dark:text-red-300'
                                        ]"
                                    >
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Content Section -->
                        <div class="flex-1 px-6">
                            <div class="mb-4">
                                <div
                                    :class="[
                                        job.user.license_status !== 'active' ? 'opacity-70' : ''
                                    ]"
                                    v-html="job.description"
                                ></div>
                            </div>

                            <!-- Qualifications Section -->
                            <div v-if="job.qualifications && job.qualifications.length > 0" class="mb-6">
                                <div
                                    :class="[
                                        'rounded-lg border p-4',
                                        job.user.license_status === 'active'
                                            ? 'border-blue-100 bg-blue-50/50 dark:border-blue-900/30 dark:bg-blue-900/10'
                                            : 'border-red-100 bg-red-50/50 dark:border-red-900/30 dark:bg-red-900/10'
                                    ]"
                                >
                                    <h4
                                        :class="[
                                            'mb-3 flex items-center text-sm font-semibold',
                                            job.user.license_status === 'active'
                                                ? 'text-blue-800 dark:text-blue-300'
                                                : 'text-red-800 dark:text-red-300'
                                        ]"
                                    >
                                        <svg
                                            :class="[
                                                'mr-2 h-4 w-4',
                                                job.user.license_status === 'active'
                                                    ? 'text-blue-600 dark:text-blue-400'
                                                    : 'text-red-600 dark:text-red-400'
                                            ]"
                                            fill="currentColor"
                                            viewBox="0 0 20 20"
                                        >
                                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                        </svg>
                                        Key Qualifications
                                    </h4>
                                    <ul class="space-y-2.5">
                                        <li
                                            v-for="(qualification, index) in job.qualifications.slice(0, 3)"
                                            :key="index"
                                            :class="[
                                                'flex items-start text-xs',
                                                job.user.license_status === 'active'
                                                    ? 'text-blue-700 dark:text-blue-300'
                                                    : 'text-red-700 dark:text-red-300'
                                            ]"
                                        >
                                            <div
                                                :class="[
                                                    'mr-3 mt-1.5 h-1.5 w-1.5 flex-shrink-0 rounded-full',
                                                    job.user.license_status === 'active'
                                                        ? 'bg-blue-500 dark:bg-blue-400'
                                                        : 'bg-red-500 dark:bg-red-400'
                                                ]"
                                            ></div>
                                            <span class="line-clamp-2 leading-relaxed">{{ qualification }}</span>
                                        </li>
                                        <li
                                            v-if="job.qualifications.length > 3"
                                            :class="[
                                                'flex items-center text-xs font-medium',
                                                job.user.license_status === 'active'
                                                    ? 'text-blue-600 dark:text-blue-400'
                                                    : 'text-red-600 dark:text-red-400'
                                            ]"
                                        >
                                            <div
                                                :class="[
                                                    'mr-3 h-1.5 w-1.5 flex-shrink-0 rounded-full',
                                                    job.user.license_status === 'active'
                                                        ? 'bg-blue-400'
                                                        : 'bg-red-400'
                                                ]"
                                            ></div>
                                            +{{ job.qualifications.length - 3 }} more requirements
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Footer Section -->
                        <div
                            :class="[
                                'mt-auto border-t p-6 pt-4',
                                job.user.license_status === 'active'
                                    ? 'border-gray-50 dark:border-gray-700/50'
                                    : 'border-red-100 dark:border-red-900/50'
                            ]"
                        >
                            <div class="mb-4 flex items-center justify-between">
                                <div class="space-y-2">
                                    <div class="flex items-center space-x-2">
                                        <span
                                            :class="[
                                                'text-sm font-bold',
                                                job.user.license_status === 'active'
                                                    ? 'text-green-600 dark:text-green-400'
                                                    : 'text-orange-600 dark:text-orange-400'
                                            ]"
                                        >
                                            Ksh {{ formatSalary(job.salary_min) }} - {{ formatSalary(job.salary_max) }}
                                        </span>
                                    </div>

                                    <div class="flex items-center space-x-2">
                                        <div
                                            :class="[
                                                'flex h-5 w-5 items-center justify-center rounded-full',
                                                job.user.license_status === 'active'
                                                    ? 'bg-purple-50 dark:bg-purple-900/30'
                                                    : 'bg-red-50 dark:bg-red-900/30'
                                            ]"
                                        >
                                            <svg
                                                :class="[
                                                    'h-3 w-3',
                                                    job.user.license_status === 'active'
                                                        ? 'text-purple-600 dark:text-purple-400'
                                                        : 'text-red-600 dark:text-red-400'
                                                ]"
                                                fill="currentColor"
                                                viewBox="0 0 20 20"
                                            >
                                                <path
                                                    fill-rule="evenodd"
                                                    d="M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v6.5A1.5 1.5 0 0116.5 16h-13A1.5 1.5 0 012 14.5V8a2 2 0 012-2h2zm4-1a1 1 0 00-1 1v1h2V6a1 1 0 00-1-1z"
                                                    clip-rule="evenodd"
                                                />
                                            </svg>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <Link
                                        :href="route('health-jobs.show', job.uuid)"
                                        :class="[
                                            'group/btn inline-flex items-center space-x-2 rounded-xl px-5 py-2.5 text-sm font-semibold text-white shadow-lg transition-all duration-200 focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-gray-800 cursor-pointer',
                                            job.user.license_status === 'active'
                                                ? 'bg-gradient-to-r from-blue-600 to-blue-700 shadow-blue-500/25 hover:from-blue-700 hover:to-blue-800 hover:shadow-xl hover:shadow-blue-500/30 focus:ring-blue-500 dark:from-blue-500 dark:to-blue-600 dark:shadow-blue-500/20 dark:hover:from-blue-600 dark:hover:to-blue-700'
                                                : 'bg-gradient-to-r from-red-600 to-red-700 shadow-red-500/25 hover:from-red-700 hover:to-red-800 hover:shadow-xl hover:shadow-red-500/30 focus:ring-red-500 dark:from-red-500 dark:to-red-600 dark:shadow-red-500/20 dark:hover:from-red-600 dark:hover:to-red-700'
                                        ]"
                                    >
                                        <span>{{ job.user.license_status === 'active' ? 'View Details' : 'Proceed with Caution' }}</span>
                                        <svg
                                            class="h-4 w-4 transition-transform duration-200 group-hover/btn:translate-x-0.5"
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
            </div>
        </div>
    </AppLayout>
</template>
