<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { computed, reactive,onMounted } from 'vue';

const page = usePage();

// const can = computed(() => page.props.auth.can);
// const can = computed(() => page.props.can);

const user = computed(() => page.props.auth.user)


// Log user on component mount
// onMounted(() => {
//     let permissions = page.props.auth.user.permissions;
//     let roles = page.props.auth.user.roles;
//     console.log("You have these permissions", permissions.length);
//     console.log("You have these roles", roles.length);
// });

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
    <Head title="Health Jobs" />

    <AppLayout>
        <div class="min-h-screen bg-gray-50 py-8">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="mb-8 text-center">
                    <h1 class="text-3xl font-bold text-gray-900">Health Jobs</h1>
                    <p class="mt-2 text-gray-600">Find your next healthcare opportunity</p>
                </div>

                <!-- Filters -->
                <div class="mb-6 rounded-lg bg-white p-6 shadow">
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
                        <div>
                            <input
                                v-model="searchForm.search"
                                type="text"
                                placeholder="Search jobs..."
                                class="w-full rounded-md border border-gray-300 px-3 py-2"
                            />
                        </div>
                        <div>
                            <select v-model="searchForm.job_type" class="w-full rounded-md border border-gray-300 px-3 py-2">
                                <option value="">All Job Types</option>
                                <option value="full-time">Full Time</option>
                                <option value="part-time">Part Time</option>
                                <option value="contract">Contract</option>
                            </select>
                        </div>
                        <div>
                            <select v-model="searchForm.experience_level" class="w-full rounded-md border border-gray-300 px-3 py-2">
                                <option value="">All Levels</option>
                                <option value="entry">Entry Level</option>
                                <option value="mid">Mid Level</option>
                                <option value="senior">Senior Level</option>
                            </select>
                        </div>
                        <div>
                            <button @click="search" class="w-full rounded-md bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">Search</button>
                        </div>
                    </div>
                </div>

                <!-- Jobs Grid -->
                <div class="mb-8 grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                    <div v-for="job in jobs.data" :key="job.id" class="rounded-lg bg-white p-6 shadow transition-shadow hover:shadow-lg">
                        <div class="mb-3 flex items-start justify-between">
                            <h3 class="text-lg font-semibold text-gray-900">{{ job.title }}</h3>
                            <span class="rounded-full bg-blue-100 px-2 py-1 text-xs text-blue-800">
                                {{ formatJobType(job.job_type) }}
                            </span>
                        </div>

                        <p class="mb-2 text-gray-600">{{ job.company }}</p>
                        <p class="mb-3 text-sm text-gray-500">📍 {{ job.location }}</p>

                        <p class="mb-4 line-clamp-3 text-gray-700">{{ job.description.substring(0, 150) }}...</p>

                        <div class="flex items-center justify-between">
                            <div class="text-sm">
                                <span class="font-semibold text-green-600">
                                    ${{ formatSalary(job.salary_min) }} - ${{ formatSalary(job.salary_max) }}
                                </span>
                                <br />
                                <span class="text-gray-500">{{ formatExperienceLevel(job.experience_level) }}</span>
                            </div>

                            <Link
                                :href="route('health-jobs.show', job.id)"
                                class="rounded-md bg-blue-600 px-4 py-2 text-sm text-white hover:bg-blue-700"
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
