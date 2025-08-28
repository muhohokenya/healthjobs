<script setup lang="ts">
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { useAuth } from '@/utils/auth';
const user = useAuth();

import AppLayout from '@/layouts/AppLayout.vue';

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'roles',
        href: '/iam/roles',
    },
];

const formatRole = (type) => {
    if (!type) return 'Not specified';
    return type
        .split('-')
        .map((word) => word.charAt(0).toUpperCase() + word.slice(1))
        .join(' ');
};

// eslint-disable-next-line @typescript-eslint/no-unused-vars
const props = defineProps<{
    roles: Array<{
        id: number;
        name: string;
        permissions: Array<{
            id: number;
            name: string;
        }>;
    }>;
}>();
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">
        <Head title="Roles" />
        <div class="min-h-screen bg-gray-50 py-8 dark:bg-gray-900">
            <div class="mx-auto max-w-full px-2 sm:px-5">
                <!-- Header -->
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Manage Users</h1>
                    <p class="mt-2 text-gray-600 dark:text-gray-300">View and manage user accounts, roles, and permissions</p>
                </div>

                <!-- Create Job Button (Conditional) -->
                <div v-if="user.hasRole('super-admin')" class="mb-6 flex justify-end">
                    <Link
                        class="flex items-center rounded-md bg-blue-600 px-4 py-2.5 font-medium text-white transition hover:bg-blue-700 focus:ring-2 focus:ring-blue-400 focus:outline-none"
                        :href="route('iam.roles.map')"
                        >Manage roles and permissions</Link
                    >
                </div>


                <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-900">
                    <!-- Table Header -->
                    <div class="border-b border-gray-200 bg-gray-50 px-6 py-4 dark:border-gray-700 dark:bg-gray-800">
                        <div class="grid grid-cols-12 gap-6">
                            <div class="col-span-3">
                                <h3 class="text-sm font-semibold tracking-wider text-gray-900 uppercase dark:text-gray-100">Role</h3>
                            </div>
                            <div class="col-span-9">
                                <h3 class="text-sm font-semibold tracking-wider text-gray-900 uppercase dark:text-gray-100">Permissions</h3>
                            </div>
                        </div>
                    </div>

                    <!-- Table Body -->
                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                        <div
                            v-for="role in roles"
                            :key="role.id"
                            class="px-6 py-5 transition-colors duration-200 hover:bg-gray-50 dark:hover:bg-gray-800/50"
                        >
                            <div class="grid grid-cols-12 items-start gap-6">
                                <!-- Role Name -->
                                <div class="col-span-3">
                                    <div class="flex items-center space-x-3">
                                        <div class="flex-shrink-0">
                                            <div
                                                class="flex h-8 w-8 items-center justify-center rounded-lg bg-gradient-to-br from-blue-500 to-blue-600"
                                            >
                                                <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"
                                                    />
                                                </svg>
                                            </div>
                                        </div>
                                        <div>
                                            <p class="text-base font-medium text-gray-900 dark:text-gray-100">
                                                {{ formatRole(role.name) }}
                                            </p>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ role.permissions.length }} permission{{ role.permissions.length !== 1 ? 's' : '' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Permissions -->
                                <div class="col-span-9">
                                    <div class="flex flex-wrap gap-2">
                                        <span
                                            v-for="perm in role.permissions"
                                            :key="perm.id"
                                            class="inline-flex items-center rounded-lg border
                                            border-blue-200
                                            bg-blue-50
                                            px-3 py-1.5 text-sm font-medium
                                            text-green-700 transition-colors duration-200
                                            hover:bg-green-100
                                            dark:border-green-800
                                            dark:bg-green-900/20
                                            dark:text-green-300
                                            dark:hover:bg-blue-900/30"
                                        >

                                            <input type="checkbox" :name="perm.name" id="" value="Check" checked  class="mr-5" disabled/>
                                            <label>{{perm.name}}</label>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Empty State (if no roles) -->
                    <div v-if="!roles || roles.length === 0" class="px-6 py-12 text-center">
                        <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800">
                            <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"
                                />
                            </svg>
                        </div>
                        <h3 class="mb-2 text-lg font-medium text-gray-900 dark:text-gray-100">No roles found</h3>
                        <p class="text-gray-500 dark:text-gray-400">Get started by creating your first role.</p>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
