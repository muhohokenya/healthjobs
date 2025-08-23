<script setup lang="ts">
import type { HTMLAttributes } from 'vue';

defineOptions({
    inheritAttrs: false,
});

interface Props {
    className?: HTMLAttributes['class'];
}

defineProps<Props>();
</script>

<template>
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 40 42" class="className" v-bind="$attrs">
        <!-- Background briefcase/folder -->
        <path
            fill="currentColor"
            fill-rule="evenodd"
            clip-rule="evenodd"
            d="M8 12h24c1.1 0 2 .9 2 2v20c0 1.1-.9 2-2 2H8c-1.1 0-2-.9-2-2V14c0-1.1.9-2 2-2zm2 4v16h20V16H10z"
        />

        <!-- Handle -->
        <path
            fill="currentColor"
            d="M14 8h12c1.1 0 2 .9 2 2v2H12V10c0-1.1.9-2 2-2z"
        />

        <!-- Medical cross -->
        <path
            fill="currentColor"
            d="M18 20h4v3h3v4h-3v3h-4v-3h-3v-4h3v-3z"
        />

        <!-- Stethoscope accent -->
        <circle
            fill="currentColor"
            cx="32"
            cy="8"
            r="3"
            opacity="0.7"
        />
        <path
            fill="currentColor"
            d="M32 11c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm0-6c-2.2 0-4 1.8-4 4 0 1.5.8 2.8 2 3.5v2.5c0 1.1-.9 2-2 2s-2-.9-2-2v-1"
            stroke="currentColor"
            stroke-width="1"
            opacity="0.7"
        />

        <!-- Small medical plus indicators -->
        <circle fill="currentColor" cx="4" cy="6" r="1" opacity="0.5"/>
        <path fill="currentColor" d="M3.5 6h1M4 5.5v1" stroke="currentColor" stroke-width="0.5" opacity="0.5"/>

        <circle fill="currentColor" cx="36" cy="36" r="1" opacity="0.5"/>
        <path fill="currentColor" d="M35.5 36h1M36 35.5v1" stroke="currentColor" stroke-width="0.5" opacity="0.5"/>
    </svg>
</template>
