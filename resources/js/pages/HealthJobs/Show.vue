<script setup lang="ts">
import { Head, Link, Form } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { useAuth } from '@/utils/auth';
import { User } from '@/types';


const user = useAuth();
const { job } = defineProps<{ job: any }>();

const formatJobType = (type: string): string => {
    if (!type) return 'Not specified';
    return type.split('-').map(w => w.charAt(0).toUpperCase() + w.slice(1)).join(' ');
};


function canShowInterest(user: User, job: any): boolean {
    // User cannot show interest in their own job
    if (user.id === job.user_id) {
        return false;
    }

    // User cannot show interest if they already have
    const alreadyInterested = user.jobInterests.some(
        (interest: any) => interest.health_job_id === job.id
    );

    return !alreadyInterested;
}


const formatSalary = (salary: number): string => {
    if (!salary || salary === 0) return 'Not specified';
    return new Intl.NumberFormat('en-US').format(salary);
};
</script>

<template>
    <Head :title="job.title" />
    <AppLayout>
        <div class="min-h-screen bg-gray-50 py-8">
            <div class="max-w-4xl px-4 sm:px-6 lg:px-8">
                <div class="bg-white rounded-lg shadow-lg overflow-hidden">

                    <!-- Error Banner -->
                    <div v-if="$page.props.flash.flashMessage" class="mb-6">
                        <div
                            class="rounded-md border border-red-300 bg-red-50 p-4 text-red-800 shadow-sm dark:border-red-700 dark:bg-red-900 dark:text-red-200"
                        >
                            <div class="flex items-center">
                                <!-- Error Icon -->
                                <svg
                                    class="h-5 w-5 text-red-500 dark:text-red-300"
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.662 1.732-3L13.732 4c-.77-1.338-2.694-1.338-3.464 0L3.34 16c-.77 1.338.192 3 1.732 3z"
                                    />
                                </svg>

                                <!-- Message -->
                                <span class="ml-2 font-medium">
                {{ $page.props.flash.flashMessage }}
            </span>
                            </div>
                        </div>
                    </div>

                    <!-- Header -->
                    <div class="bg-blue-600 text-white p-6">
                        <div class="flex justify-between items-start">
                            <div>
                                <h1 class="text-2xl font-bold mb-2">{{ job.title }}</h1>
                                <p class="text-blue-100 mb-1">{{ job.company }}</p>
                                <p class="text-blue-200">📍 {{ job.location }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="p-6">
                        <!-- Salary and Details -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                            <div class="text-center p-4 bg-green-50 rounded-lg">
                                <h3 class="text-sm font-medium text-gray-500 mb-1">Salary Range</h3>
                                <p class="text-lg font-semibold text-green-600">
                                    Ksh: {{ formatSalary(job.salary_min) }} - {{ formatSalary(job.salary_max) }}
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
                                <span v-html="job.description"></span>
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
                        <div  class="flex flex-col sm:flex-row gap-4">



                            <!-- Interested Form -->
                            <Form v-if="canShowInterest(user,job)"  :action="route('health-jobs.interested')" method="post">
                                <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-md hover:bg-blue-700 font-medium cursor-pointer">
                                    Interested
                                </button>
                                <input type="hidden" name="job"  :value="job.uuid"/>
                            </Form>





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
    </AppLayout>
</template>
