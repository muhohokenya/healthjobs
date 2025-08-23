<script setup lang="ts">
import { useAuth } from '@/utils/auth';
const user = useAuth();
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router, useForm, usePage } from '@inertiajs/vue3';
import { computed, reactive, onMounted } from 'vue';
import type { BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Jobs Pages',
        href: '/dashboard',
    },
];



const props = defineProps({
    jobs: Object,
    filters: Object,
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

                <!-- Create Job Button (Conditional) -->
                <div v-if="user.hasRole('super-admin')" class="mb-6 flex justify-end">

                    <Link class="flex items-center rounded-md bg-blue-600 px-4 py-2.5
                         font-medium text-white transition hover:bg-blue-700 focus:ring-2
                         focus:ring-blue-400 focus:outline-none" :href="route('health-jobs.create')">Create New Job</Link>
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
                            <label for="experience_level_filter" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Experience Level</label>
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
                                class="w-full rounded-md bg-blue-600 px-4 py-2.5 text-white transition-colors hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:bg-blue-500 dark:hover:bg-blue-600 dark:focus:ring-offset-gray-800"
                            >
                                Apply Filters
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Jobs Grid -->
                <div class="mb-8 grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                    <div
                        v-for="job in jobs.data"
                        :key="job.id"
                        class="rounded-lg border border-transparent bg-white p-6 shadow transition-all hover:shadow-lg dark:border-gray-700 dark:bg-gray-800 dark:shadow-gray-700/25 dark:hover:shadow-gray-700/40"
                    >
                        <div class="mb-3 flex items-start justify-between">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ job.title }}</h3>
                            <span class="rounded-full bg-blue-100 px-2.5 py-1 text-xs font-medium text-blue-800 dark:bg-blue-900/50 dark:text-blue-300">
                                {{ formatJobType(job.job_type) }}
                            </span>
                        </div>

                        <p class="mb-2 text-gray-600 dark:text-gray-300">{{ job.company }}</p>
                        <p class="mb-3 text-sm text-gray-500 dark:text-gray-400">📍 {{ job.location }}</p>

                        <p class="mb-4 line-clamp-3 text-gray-700 dark:text-gray-300">{{ job.description.substring(0, 150) }}...</p>

                        <div class="flex items-center justify-between">
                            <div class="text-sm">
                                <span class="font-semibold text-green-600 dark:text-green-400">
                                    ${{ formatSalary(job.salary_min) }} - ${{ formatSalary(job.salary_max) }}
                                </span>
                                <br />
                                <span class="text-gray-500 dark:text-gray-400">{{ formatExperienceLevel(job.experience_level) }}</span>
                            </div>

                            <Link
                                :href="route('health-jobs.show', job.id)"
                                class="rounded-md bg-blue-600 px-4 py-2 text-sm text-white transition-colors hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:bg-blue-500 dark:hover:bg-blue-600 dark:focus:ring-offset-gray-800"
                            >
                                View Details
                            </Link>
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
