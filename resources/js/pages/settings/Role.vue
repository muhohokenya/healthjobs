<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { useAuth } from '@/utils/auth';
const user = useAuth();
import HeadingSmall from '@/components/HeadingSmall.vue';
import { type BreadcrumbItem } from '@/types';

import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Appearance settings',
        href: '/settings/appearance',
    },
];

function toPascalCase(str) {
    return str
        .toLowerCase()
        .replace(/(^\w|[-_ ]+\w)/g, match =>
            match.replace(/[-_ ]+/, "").toUpperCase()
        );
}
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">
        <Head title="Appearance settings" />

        <SettingsLayout>
            <div class="space-y-6">
                <HeadingSmall title="Role" :description="toPascalCase(user.roles[0].name)" />
            </div>
        </SettingsLayout>
    </AppLayout>
</template>
