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
            <div class="max-w-7xl grid mx-auto grid-cols-2 gap-4">
                <div>
                    <BarChart />
                </div>

                <div>
                    <LineChart />
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

const props = defineProps({
    stats: Object,
    recent_jobs: Array,
    top_facilities: Array,
});

const formatDate = (dateString: string): string => {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', {
        month: 'short',
        day: 'numeric',
    });
};
</script>
