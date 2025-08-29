<template>
    <div class="rounded-lg bg-white p-6 shadow">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div :class="iconClasses">
                    <component :is="iconComponent" class="h-6 w-6" />
                </div>
            </div>
            <div class="ml-4 flex-1">
                <div class="text-sm font-medium text-gray-500">{{ title }}</div>
                <div class="text-2xl font-bold text-gray-900">
                    {{ formatNumber(value) }}
                </div>
                <div v-if="trend !== undefined" class="mt-1 text-xs text-gray-600">+{{ trend }} {{ trendLabel }}</div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue';
import { BriefcaseIcon, BuildingIcon, CheckCircleIcon, ClockIcon, PlayIcon, ShieldCheckIcon, UserCheckIcon } from 'lucide-vue-next';

const props = defineProps({
    title: String,
    value: Number,
    icon: String,
    color: {
        type: String,
        default: 'blue',
    },
    trend: Number,
    trendLabel: String,
});

const iconComponent = computed(() => {
    const icons = {
        briefcase: BriefcaseIcon,
        'check-circle': CheckCircleIcon,
        building: BuildingIcon,
        'shield-check': ShieldCheckIcon,
        'user-check': UserCheckIcon,
        play: PlayIcon,
        clock: ClockIcon,
    };
    return icons[props.icon] || BriefcaseIcon;
});

const iconClasses = computed(() => {
    const colorClasses = {
        blue: 'bg-blue-100 text-blue-600',
        green: 'bg-green-100 text-green-600',
        purple: 'bg-purple-100 text-purple-600',
        emerald: 'bg-emerald-100 text-emerald-600',
        indigo: 'bg-indigo-100 text-indigo-600',
        yellow: 'bg-yellow-100 text-yellow-600',
    };

    return ['flex items-center justify-center h-12 w-12 rounded-md', colorClasses[props.color] || colorClasses['blue']];
});

const formatNumber = (num) => {
    if (num === null || num === undefined) return '0';
    return new Intl.NumberFormat().format(num);
};
</script>
