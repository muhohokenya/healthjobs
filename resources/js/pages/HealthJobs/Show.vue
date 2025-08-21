<template>
    <Head :title="job.title" />

    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <!-- Header -->
                <div class="bg-blue-600 text-white p-6">
                    <div class="flex justify-between items-start">
                        <div>
                            <h1 class="text-2xl font-bold mb-2">{{ job.title }}</h1>
                            <p class="text-blue-100 mb-1">{{ job.company }}</p>
                            <p class="text-blue-200">📍 {{ job.location }}</p>
                        </div>
                        <div class="text-right">
                            <span class="bg-white text-blue-600 px-3 py-1 rounded-full text-sm font-medium">
                                {{ formatJobType(job.job_type) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Content -->
                <div class="p-6">
                    <!-- Salary and Details -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div class="text-center p-4 bg-green-50 rounded-lg">
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Salary Range</h3>
                            <p class="text-lg font-semibold text-green-600">
                                ${{ formatSalary(job.salary_min) }} - ${{ formatSalary(job.salary_max) }}
                            </p>
                        </div>

                        <div class="text-center p-4 bg-purple-50 rounded-lg">
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Experience Level</h3>
                            <p class="text-lg font-semibold text-purple-600">
                                {{ formatExperienceLevel(job.experience_level) }}
                            </p>
                        </div>

                        <div class="text-center p-4 bg-blue-50 rounded-lg">
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Job Type</h3>
                            <p class="text-lg font-semibold text-blue-600">
                                {{ formatJobType(job.job_type) }}
                            </p>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="mb-8">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Job Description</h2>
                        <div class="prose max-w-none text-gray-700">
                            <p>{{ job.description }}</p>
                        </div>
                    </div>

                    <!-- Requirements -->
                    <div class="mb-8" v-if="job.requirements && job.requirements.length">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Requirements</h2>
                        <ul class="list-disc pl-6 space-y-2">
                            <li v-for="requirement in job.requirements" :key="requirement" class="text-gray-700">
                                {{ requirement }}
                            </li>
                        </ul>
                    </div>

                    <!-- Actions -->
                    <div class="flex flex-col sm:flex-row gap-4">
                        <button class="bg-blue-600 text-white px-6 py-3 rounded-md hover:bg-blue-700 font-medium">
                            Apply Now
                        </button>
                        <button class="border border-gray-300 text-gray-700 px-6 py-3 rounded-md hover:bg-gray-50 font-medium">
                            Save Job
                        </button>
                        <Link
                            :href="route('health-jobs.index')"
                            class="border border-gray-300 text-gray-700 px-6 py-3 rounded-md hover:bg-gray-50 font-medium text-center"
                        >
                            ← Back to Jobs
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3'

const props = defineProps({
    job: Object
})

const formatJobType = (type) => {
    if (!type) return 'Not specified'
    return type.split('-').map(word =>
        word.charAt(0).toUpperCase() + word.slice(1)
    ).join(' ')
}

const formatExperienceLevel = (level) => {
    if (!level) return 'Not specified'
    return level.charAt(0).toUpperCase() + level.slice(1) + ' Level'
}

const formatSalary = (salary) => {
    if (!salary || salary === 0) return 'Not specified'
    return new Intl.NumberFormat('en-US').format(salary)
}
</script>
