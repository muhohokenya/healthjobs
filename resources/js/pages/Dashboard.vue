<template>
    <Head title="Dashboard" />

    <AppLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-bold text-2xl text-gray-800">
                    Dashboard
                </h2>
                <span class="text-sm text-gray-500">MVP Demo</span>
            </div>
        </template>

        <div class="py-8">
            <div class="max-w-7xl mx-auto space-y-8">

                <!-- Key Stats -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div
                        v-for="card in statCards"
                        :key="card.title"
                        class="bg-gradient-to-r from-indigo-500 to-blue-500 rounded-2xl p-6 shadow-lg text-white flex flex-col justify-between hover:scale-105 transition-transform duration-200"
                    >
                        <div class="flex items-center justify-between">
                            <h3 class="text-sm font-medium">{{ card.title }}</h3>
                            <component :is="card.icon" class="w-6 h-6 opacity-80" />
                        </div>
                        <p class="text-3xl font-bold mt-4">{{ card.value }}</p>
                    </div>
                </div>

                <!-- Content Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                    <!-- Recent Jobs -->
                    <div class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition-shadow duration-200">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6h4m6 6H6a2 2 0 01-2-2V6a2 2 0 012-2h7l5 5v9a2 2 0 01-2 2z" />
                            </svg>
                            Recent Jobs
                        </h3>
                        <ul class="divide-y divide-gray-100">
                            <li v-for="job in recent_jobs" :key="job.id" class="py-3">
                                <div class="flex justify-between">
                                    <div>
                                        <p class="font-medium text-gray-900">{{ job.title }}</p>
                                        <p class="text-sm text-gray-600">
                                            {{ job.job_type }} • {{ job.facility?.name }}
                                        </p>
                                    </div>
                                    <span class="text-xs text-gray-500">
                                        {{ formatDate(job.created_at) }}
                                    </span>
                                </div>
                            </li>
                        </ul>
                    </div>

                    <!-- Top Facilities -->
                    <div class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition-shadow duration-200">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-green-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7l9-4 9 4-9 4-9-4zm0 0v6a9 9 0 009 9m9-15v6a9 9 0 01-9 9" />
                            </svg>
                            Top Facilities
                        </h3>
                        <ul class="divide-y divide-gray-100">
                            <li v-for="facility in top_facilities" :key="facility.id" class="py-3">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <p class="font-medium text-gray-900">{{ facility.name }}</p>
                                        <p class="text-xs text-gray-500">{{ facility.location }}</p>
                                    </div>
                                    <span class="text-sm font-semibold text-gray-700">
                                        {{ facility.jobs_count }} jobs
                                    </span>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { Head } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'

// Props
const props = defineProps({
    stats: Object,
    recent_jobs: Array,
    top_facilities: Array,
})

// Key stat cards config
import { Briefcase, Building } from 'lucide-vue-next'

const statCards = [
    { title: "Total Jobs", value: props.stats.total_jobs, icon: Briefcase },
    { title: "Total Facilities", value: props.stats.total_facilities, icon: Building },
]

// Format date
const formatDate = (dateString) => {
    const date = new Date(dateString)
    return date.toLocaleDateString('en-US', {
        month: 'short',
        day: 'numeric',
    })
}
</script>
