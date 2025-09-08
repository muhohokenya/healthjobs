<script setup lang="ts">
// Define props with TypeScript
import { Link } from '@inertiajs/vue3';

interface Props {
    count: number;
    label?: string;
}

// Accept props with default values
const props = withDefaults(defineProps<Props>(), {
    label: 'Jobs'
});
</script>

<template>
    <div class="bg-white rounded-lg shadow border p-6 hover:shadow-lg hover:border-blue-300 transition-all duration-200 cursor-pointer">
        <!-- Main Content -->
        <div class="text-center mb-4">
            <div class="text-3xl font-bold text-gray-900 mb-1">
                {{ count }}
            </div>
            <div class="text-gray-600">
                {{ label }}
            </div>
        </div>

        <!-- Footer Link -->
        <div class="text-center">
            <Link
            href="{{ route('health-jobs.index') }}"
            class="text-blue-600 hover:text-blue-800 text-sm font-medium"
            >
                View Jobs
            </Link>
        </div>
    </div>
</template>

<style scoped></style>
