<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Form } from '@inertiajs/vue3';
import { useAuth } from '@/utils/auth';
import { ref, computed } from 'vue';

const user = useAuth();

// Dynamic qualifications list
const qualifications = ref([]);
const maxQualifications = 15;

// Computed property to check if we can add more qualifications
const canAddMore = computed(() => {
    return qualifications.value.length < maxQualifications;
});

// Add a new qualification input
const addQualification = () => {
    if (canAddMore.value) {
        qualifications.value.push('');
    }
};

// Remove a qualification input
const removeQualification = (index: number) => {
    qualifications.value.splice(index, 1);
};
</script>

<template>
    <Head title="Create Job" />
    <AppLayout>
        <div class="min-h-screen bg-gray-50 py-8 dark:bg-gray-900">
            <div class="max-w-4xl px-4 sm:px-6 lg:px-8">
                <!-- Job Creation Form -->
                <div ref="jobForm" class="mt-12">
                    <div  class="mb-6 border-b border-gray-200 pb-4 dark:border-gray-700">
                        <h4>{{user.facility.name}}</h4>
                        <p class="text-gray-600 mt-4 dark:text-gray-300">Fill out the form below to create a new job listing</p>
                    </div>

                    <Form
                        :action="route('health-jobs.store')"
                        method="post"
                        class="space-y-6 rounded-lg bg-white p-8 shadow-lg dark:bg-gray-800"
                    >
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <!-- Job Title -->
                            <div>
                                <label for="title" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Job Title *</label>
                                <input
                                    type="text"
                                    name="title"
                                    id="title"
                                    placeholder="e.g. Registered Nurse"
                                    required
                                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-gray-900 placeholder-gray-500 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-400 dark:focus:ring-blue-400"
                                />
                            </div>
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Description *</label>
                            <textarea
                                name="description"
                                id="description"
                                rows="4"
                                placeholder="Describe the job role and responsibilities"
                                required
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-gray-900 placeholder-gray-500 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-400 dark:focus:ring-blue-400"
                            />
                            {{prop}}
                        </div>



                        <!-- Job Type & Experience -->
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <label for="job_type" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Job Type *</label>
                                <select
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

                        <!-- Salary Range -->
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <label for="salary_min" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Min Range</label>
                                <input
                                    type="text"
                                    name="salary_min"
                                    id="salary_min"
                                    placeholder="e.g. Kes 35000"
                                    required
                                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-gray-900 placeholder-gray-500 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-400 dark:focus:ring-blue-400"
                                />
                            </div>
                            <div>
                                <label for="salary_max" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Max Range</label>
                                <input
                                    type="text"
                                    name="salary_max"
                                    id="salary_max"
                                    placeholder="e.g. Kes 85000"
                                    required
                                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-gray-900 placeholder-gray-500 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-400 dark:focus:ring-blue-400"
                                />
                            </div>
                        </div>

                        <!-- Dynamic Qualifications Section -->
                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Qualifications & Requirements
                                </label>
                                <button
                                    type="button"
                                    @click="addQualification"
                                    :disabled="!canAddMore"
                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed dark:text-blue-400 dark:bg-blue-900 dark:hover:bg-blue-800"
                                >
                                    <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                    Add Qualification
                                </button>
                            </div>

                            <div class="space-y-3" v-if="qualifications.length > 0">
                                <div
                                    v-for="(qualification, index) in qualifications"
                                    :key="index"
                                    class="flex items-center gap-3"
                                >
                                    <div class="flex-1">
                                        <input
                                            v-model="qualifications[index]"
                                            type="text"
                                            :name="`qualifications[${index}]`"
                                            :placeholder="`Qualification ${index + 1} (e.g., Bachelor's degree in Nursing)`"
                                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-gray-900 placeholder-gray-500 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-400 dark:focus:ring-blue-400"
                                        />
                                    </div>
                                    <div class="flex items-center gap-1">
                                        <!-- Small Add Button -->
                                        <button
                                            type="button"
                                            @click="addQualification"
                                            :disabled="!canAddMore"
                                            class="flex-shrink-0 p-1.5 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed dark:text-blue-400 dark:hover:text-blue-300 dark:hover:bg-blue-900"
                                            title="Add qualification"
                                        >
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                            </svg>
                                        </button>
                                        <!-- Delete Button -->
                                        <button
                                            type="button"
                                            @click="removeQualification(index)"
                                            class="flex-shrink-0 p-1.5 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg transition-colors dark:text-red-400 dark:hover:text-red-300 dark:hover:bg-red-900"
                                            title="Remove qualification"
                                        >
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                Add up to {{ maxQualifications }} qualifications. These will appear as bullet points in the job listing.
                            </p>
                        </div>

                        <!-- Active Checkbox -->
                        <div class="flex items-center space-x-3 pt-2">
                            <input
                                type="checkbox"
                                name="is_active"
                                id="is_active"
                                value="1"
                                checked
                                class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:focus:ring-blue-400"
                            />
                            <label for="is_active" class="text-sm font-medium text-gray-700 dark:text-gray-300">Job is Active</label>
                        </div>

                        <!-- Submit -->
                        <div class="pt-4">
                            <button
                                type="submit"
                                class="flex w-full items-center justify-center rounded-lg bg-blue-600 px-6 py-3.5 text-lg font-semibold text-white transition hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:outline-none disabled:opacity-50 dark:bg-blue-500 dark:hover:bg-blue-600 dark:focus:ring-blue-400 dark:focus:ring-offset-gray-800"
                            >
                                Create Job
                            </button>
                        </div>
                    </Form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped></style>
