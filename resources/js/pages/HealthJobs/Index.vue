<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3'
import { reactive } from 'vue'


// const breadcrumbs: BreadcrumbItem[] = [
//     {
//         title: 'Dashboard Page',
//         href: '/dashboard',
//     },
// ];

const props = defineProps({
    jobs: Object,
    filters: Object
})

const searchForm = reactive({
    search: props.filters.search || '',
    job_type: props.filters.job_type || '',
    experience_level: props.filters.experience_level || ''
})

const search = () => {
    router.get(route('health-jobs.index'), searchForm, {
        preserveState: true,
        replace: true
    })
}

const formatJobType = (type) => {
    return type.split('-').map(word =>
        word.charAt(0).toUpperCase() + word.slice(1)
    ).join(' ')
}

const formatExperienceLevel = (level) => {
    return level.charAt(0).toUpperCase() + level.slice(1) + ' Level'
}

const formatSalary = (salary) => {
    return new Intl.NumberFormat('en-US').format(salary)
}
</script>

<template>
    <Head title="Health Jobs" />

    <AppLayout>
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Health Jobs</h1>
                <p class="mt-2 text-gray-600">Find your next healthcare opportunity</p>
            </div>

            <!-- Filters -->
            <div class="bg-white p-6 rounded-lg shadow mb-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <input
                            v-model="searchForm.search"
                            type="text"
                            placeholder="Search jobs..."
                            class="w-full border border-gray-300 rounded-md px-3 py-2"
                        />
                    </div>
                    <div>
                        <select v-model="searchForm.job_type" class="w-full border border-gray-300 rounded-md px-3 py-2">
                            <option value="">All Job Types</option>
                            <option value="full-time">Full Time</option>
                            <option value="part-time">Part Time</option>
                            <option value="contract">Contract</option>
                        </select>
                    </div>
                    <div>
                        <select v-model="searchForm.experience_level" class="w-full border border-gray-300 rounded-md px-3 py-2">
                            <option value="">All Levels</option>
                            <option value="entry">Entry Level</option>
                            <option value="mid">Mid Level</option>
                            <option value="senior">Senior Level</option>
                        </select>
                    </div>
                    <div>
                        <button
                            @click="search"
                            class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700"
                        >
                            Search
                        </button>
                    </div>
                </div>
            </div>

            <!-- Jobs Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                <div
                    v-for="job in jobs.data"
                    :key="job.id"
                    class="bg-white rounded-lg shadow hover:shadow-lg transition-shadow p-6"
                >
                    <div class="flex justify-between items-start mb-3">
                        <h3 class="text-lg font-semibold text-gray-900">{{ job.title }}</h3>
                        <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">
                            {{ formatJobType(job.job_type) }}
                        </span>
                    </div>

                    <p class="text-gray-600 mb-2">{{ job.company }}</p>
                    <p class="text-gray-500 text-sm mb-3">📍 {{ job.location }}</p>

                    <p class="text-gray-700 mb-4 line-clamp-3">
                        {{ job.description.substring(0, 150) }}...
                    </p>

                    <div class="flex justify-between items-center">
                        <div class="text-sm">
                            <span class="text-green-600 font-semibold">
                                ${{ formatSalary(job.salary_min) }} - ${{ formatSalary(job.salary_max) }}
                            </span>
                            <br>
                            <span class="text-gray-500">{{ formatExperienceLevel(job.experience_level) }}</span>
                        </div>

                        <Link
                            :href="route('health-jobs.show', job.id)"
                            class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 text-sm"
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


