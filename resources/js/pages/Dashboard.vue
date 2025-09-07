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
import LineChart from '@/components/Dashboard/LineChart.vue'; // Fixed import
</script>
