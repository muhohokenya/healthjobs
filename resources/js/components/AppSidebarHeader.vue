<script setup lang="ts">
import Breadcrumbs from '@/components/Breadcrumbs.vue';
import { SidebarTrigger } from '@/components/ui/sidebar';
import { Button } from '@/components/ui/button';
import { Bell } from 'lucide-vue-next';
import { useAuth } from '@/utils/auth';
import { computed } from 'vue';
import type { BreadcrumbItemType } from '@/types';
import { Link } from '@inertiajs/vue3';

withDefaults(
    defineProps<{
        breadcrumbs?: BreadcrumbItemType[];
    }>(),
    {
        breadcrumbs: () => [],
    },
);

const user = useAuth();

// Get unread notifications count from user data
const unreadCount = computed(() => {
    if (!user.notifications) return 0;
    return user.notifications.filter(notification => !notification.read_at).length;
});

const hasNotifications = computed(() => unreadCount.value > 0);

// Format notification count for display
const displayCount = computed(() => {
    if (unreadCount.value > 99) return '99+';
    return unreadCount.value.toString();
});
</script>

<template>
    <header
        class="flex h-16 shrink-0 items-center gap-2 border-b border-sidebar-border/70 px-6 transition-[width,height] ease-linear group-has-data-[collapsible=icon]/sidebar-wrapper:h-12 md:px-4"
    >
        <div class="flex items-center gap-2">
            <SidebarTrigger class="-ml-1" />
            <template v-if="breadcrumbs && breadcrumbs.length > 0">
                <Breadcrumbs :breadcrumbs="breadcrumbs" />
            </template>
        </div>

        <!-- Notification Bell - Far Right -->
        <div class="ml-auto">
            <Link
                :href="route('notifications.index')"
                class="relative inline-flex items-center justify-center h-10 w-10 rounded-full transition-all duration-300 hover:scale-110 hover:bg-gray-100 dark:hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            >
                <Bell
                    class="h-6 w-6 text-gray-700 dark:text-gray-200 transition-transform duration-300 group-hover:rotate-12"
                />

                <!-- Notification Badge -->
                <div
                    v-if="hasNotifications"
                    class="absolute -top-1 -right-1 min-w-[20px] h-[20px] px-1 bg-gradient-to-r from-red-500 to-pink-500 text-white text-[11px] font-bold rounded-full border-2 border-white dark:border-gray-900 shadow-md animate-bounce flex items-center justify-center"
                >
                    {{ displayCount }}
                </div>

                <span class="sr-only">
            {{ hasNotifications ? `${unreadCount} unread notifications` : 'Notifications' }}
        </span>
            </Link>
        </div>
    </header>
</template>
