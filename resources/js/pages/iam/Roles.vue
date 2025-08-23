<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import { type BreadcrumbItem } from '@/types'

import AppLayout from '@/layouts/AppLayout.vue'

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Roles',
        href: '/iam/roles',
    },
]

// eslint-disable-next-line @typescript-eslint/no-unused-vars
const props = defineProps<{
    roles: Array<{
        id: number
        name: string
        permissions: Array<{
            id: number
            name: string
        }>
    }>
}>()
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">
        <Head title="Roles" />

        <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <!-- Table Header -->
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                <div class="grid grid-cols-12 gap-6">
                    <div class="col-span-3">
                        <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100 uppercase tracking-wider">
                            Role
                        </h3>
                    </div>
                    <div class="col-span-9">
                        <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100 uppercase tracking-wider">
                            Permissions
                        </h3>
                    </div>
                </div>
            </div>

            <!-- Table Body -->
            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                <div
                    v-for="role in roles"
                    :key="role.id"
                    class="px-6 py-5 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors duration-200"
                >
                    <div class="grid grid-cols-12 gap-6 items-start">
                        <!-- Role Name -->
                        <div class="col-span-3">
                            <div class="flex items-center space-x-3">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                                        </svg>
                                    </div>
                                </div>
                                <div>
                                    <p class="text-base font-medium text-gray-900 dark:text-gray-100">
                                        {{ role.name }}
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
                                    class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-medium bg-blue-50 text-blue-700 border border-blue-200 dark:bg-blue-900/20 dark:text-blue-300 dark:border-blue-800 hover:bg-blue-100 dark:hover:bg-blue-900/30 transition-colors duration-200"
                                >
                                    {{ perm.name }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Empty State (if no roles) -->
            <div v-if="!roles || roles.length === 0" class="px-6 py-12 text-center">
                <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">No roles found</h3>
                <p class="text-gray-500 dark:text-gray-400">Get started by creating your first role.</p>
            </div>
        </div>
    </AppLayout>
</template>
