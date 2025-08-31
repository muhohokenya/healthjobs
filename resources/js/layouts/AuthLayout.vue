<script setup lang="ts">
import AuthLayout from '@/layouts/auth/AuthSimpleLayout.vue';

defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template>
    <h1>Hapa</h1>
    <AuthLayout :title="title" :description="description">
        <slot />
    </AuthLayout>
</template>
