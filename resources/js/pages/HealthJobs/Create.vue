<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { useAuth } from '@/utils/auth';
import { Head, useForm } from '@inertiajs/vue3';
const user = useAuth();

const form = useForm({
    avatar:'',
    title: 'Pharmaceutical Technologist',
    company: 'Futuremed Hospital',
    description: 'Qualification in Pharmaceutical studies,Valid license from the Pharmacy and Poisons Board,At least 3 years’ experience in a hospital or retail pharmacy,Strong communication and customer service skills',
    location: 'Baraka Towers, Pangani – Nairobi',
    job_type: '',
    salary_min: '',
    salary_max: '',
    experience_level: '',
    requirements: '', // This should be a JSON string
    is_active: true,
});
</script>

<template>
    <Head title="Create Job" />
    <AppLayout>
        <div class="min-h-screen bg-gray-50 py-8 dark:bg-gray-900">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <!-- Job Creation Form -->
                <div  ref="jobForm" class="mt-12">
                    <div class="mb-6 border-b border-gray-200 pb-4 dark:border-gray-700">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Create New Job Posting</h2>
                        <p class="text-gray-600 dark:text-gray-300">Fill out the form below to create a new job listing</p>
                    </div>

                    <form @submit.prevent="form.post(route('health-jobs.store'))"
                          class="space-y-6 rounded-lg bg-white p-8 shadow-lg dark:bg-gray-800">
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <!-- Job Title -->
                            <div>
                                <label for="title" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Job Title *</label>
                                <input
                                    v-model="form.title"
                                    type="text"
                                    name="title"
                                    id="title"
                                    placeholder="e.g. Registered Nurse"
                                    required
                                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-gray-900 placeholder-gray-500 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-400 dark:focus:ring-blue-400"
                                />
                            </div>

                            <!-- Company -->
                            <div>
                                <label for="company" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Facility *</label>
                                <input
                                    v-model="form.company"
                                    type="text"
                                    name="company"
                                    id="company"
                                    placeholder="e.g. HealthCare Inc."
                                    required
                                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-gray-900 placeholder-gray-500 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-400 dark:focus:ring-blue-400"
                                />
                            </div>
                        </div>
                        <!-- Description -->
                        <div>
                            <label for="description" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Description *</label>
                            <textarea
                                v-model="form.description"
                                name="description"
                                id="description"
                                rows="4"
                                placeholder="Describe the job role and responsibilities"
                                required
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-gray-900 placeholder-gray-500 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-400 dark:focus:ring-blue-400"
                            />
                        </div>

                        <!-- Location -->
                        <div>
                            <label for="location" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Location *</label>
                            <input
                                v-model="form.location"
                                type="text"
                                name="location"
                                id="location"
                                placeholder="e.g. Nairobi, Kilimani"
                                required
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-gray-900 placeholder-gray-500 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-400 dark:focus:ring-blue-400"
                            />
                        </div>

                        <!-- Job Type & Experience -->
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <label for="job_type" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Job Type *</label>
                                <select
                                    v-model="form.job_type"
                                    name="job_type"
                                    id="job_type"
                                    required
                                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:focus:border-blue-400 dark:focus:ring-blue-400"
                                >
                                    <option value="" disabled selected>Select Job Type</option>
                                    <option value="full-time">Full-Time</option>
                                    <option value="part-time">Part-Time</option>
                                    <option value="contract">Contract</option>
                                </select>
                            </div>

                            <div>
                                <label for="experience_level" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300"
                                    >Experience Level *</label
                                >
                                <select
                                    v-model="form.experience_level"
                                    name="experience_level"
                                    id="experience_level"
                                    required
                                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:focus:border-blue-400 dark:focus:ring-blue-400"
                                >
                                    <option value="" disabled selected>Select Experience Level</option>
                                    <option value="entry">Entry</option>
                                    <option value="mid">Mid</option>
                                    <option value="senior">Senior</option>
                                </select>
                            </div>
                        </div>


                        <input type="file" @input="form.avatar = $event.target.files[0]" />
                        <progress v-if="form.progress" :value="form.progress.percentage" max="100">
                            {{ form.progress.percentage }}%
                        </progress>



                        <!-- Active Checkbox -->
                        <div class="flex items-center space-x-3 pt-2">
                            <input
                                v-model="form.is_active"
                                type="checkbox"
                                name="is_active"
                                id="is_active"
                                class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:focus:ring-blue-400"
                            />
                            <label for="is_active" class="text-sm font-medium text-gray-700 dark:text-gray-300">Job is Active</label>
                        </div>

                        <!-- Submit -->
                        <div class="pt-4">
                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="flex w-full items-center justify-center rounded-lg bg-blue-600 px-6 py-3.5 text-lg font-semibold text-white transition hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:outline-none disabled:opacity-50 dark:bg-blue-500 dark:hover:bg-blue-600 dark:focus:ring-blue-400 dark:focus:ring-offset-gray-800"
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
                                {{ form.processing ? 'Creating Job...' : 'Create Job' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped></style>
