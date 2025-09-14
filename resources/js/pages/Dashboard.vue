<template>
    <Head title="Dashboard" />

    <AppLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-bold text-xl sm:text-2xl text-gray-800">
                    Dashboard
                </h2>
                <span class="text-xs sm:text-sm text-gray-500">MVP Demo</span>
            </div>
        </template>

        <div class="py-4 sm:py-8">

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-10">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <TotalCard :count="stats.total_jobs" label="Available Positions" class="text-sm text-red-500 bg-green-100" link="/health-jobs" />
                    </div>
                    <div>
                        <TotalCard :count="stats.total_facilities" label="Total Facilities" class="text-sm text-red-500 bg-orange-100" link="#" />
                    </div>
                    <div>
                        <TotalCard  :count="stats.total_locums" label="Total Locums" class="text-sm text-red-500 bg-purple-100" link="#" />
                    </div>
                    <div v-if="user.roles[0].name ==='super-admin'">
                        <TotalCard  :count="stats.total_users" label="Total Users" class="text-sm text-red-500 bg-red-100" link="#" />
                    </div>
                </div>
            </div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 lg:gap-6">
                    <div class="w-full">
                        <BarChart />
                    </div>

                    <div class="w-full">
                        <LineChart />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import BarChart from '@/components/Dashboard/BarChart.vue';
import LineChart from '@/components/Dashboard/LineChart.vue';
import TotalCard from '@/components/Dashboard/TotalCard.vue';
import {useAuth} from '@/utils/auth';
const user = useAuth();
// Define props to match what your controller sends
// eslint-disable-next-line @typescript-eslint/no-unused-vars
const props = defineProps<{
    stats: {
        total_jobs: number;
        total_facilities: number;
        total_users: number;
        total_locums: number;
    };
    recent_jobs: Array<any>;
    top_facilities: Array<any>;
}>();
</script>
