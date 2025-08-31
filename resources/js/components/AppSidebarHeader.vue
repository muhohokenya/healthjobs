<script setup lang="ts">
import Breadcrumbs from '@/components/Breadcrumbs.vue';
import { SidebarTrigger } from '@/components/ui/sidebar';
import { Button } from '@/components/ui/button';
import { Bell } from 'lucide-vue-next';
import { useAuth } from '@/utils/auth';
import { computed } from 'vue';
import type { BreadcrumbItemType } from '@/types';

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
            <Button
                variant="ghost"
                size="icon"
                class="h-8 w-8 relative hover:bg-gray-100 dark:hover:bg-gray-800"
                @click="$inertia.visit('/notifications')"
            >
                <Bell class="h-5 w-5" />

                <!-- Notification Badge -->
                <div
                    v-if="hasNotifications"
                    class="absolute -top-1 -right-1 min-w-[18px] h-[18px] px-1 bg-red-500 text-white text-xs font-medium rounded-full border-2 border-white dark:border-gray-900 flex items-center justify-center"
                >
                    {{ displayCount }}
                </div>

                <span class="sr-only">
                    {{ hasNotifications ? `${unreadCount} unread notifications` : 'Notifications' }}
                </span>
            </Button>
        </div>
    </header>
</template>
