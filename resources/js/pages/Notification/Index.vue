<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import {
    Bell,
    Check,
    CheckCheck,
    Clock,
    User,
    FileText,
    AlertCircle,
    Mail,
    Calendar,
    Settings,
    Trash2,
    Filter
} from 'lucide-vue-next';
import { ref, computed } from 'vue';

// Props from Laravel controller
const props = defineProps<{
    unreadNotifications: Array<{
        id: string;
        type: string;
        data: {
            title?: string;
            message?: string;
            user?: { name: string; avatar?: string };
            [key: string]: any;
        };
        created_at: string;
        read_at: string | null;
    }>;
    allNotifications: {
        data: Array<{
            id: string;
            type: string;
            data: {
                title?: string;
                message?: string;
                user?: { name: string; avatar?: string };
                [key: string]: any;
            };
            created_at: string;
            read_at: string | null;
        }>;
        current_page: number;
        last_page: number;
        total: number;
    };
    unreadCount: number;
}>();

const selectedNotifications = ref<string[]>([]);
const filterType = ref<string>('all');

// Helper function to get icon and color based on notification type
const getNotificationStyle = (type: string) => {
    const styles = {
        'App\\Notifications\\MessageNotification': { icon: Mail, color: 'bg-blue-500' },
        'App\\Notifications\\SystemNotification': { icon: Settings, color: 'bg-orange-500' },
        'App\\Notifications\\ReminderNotification': { icon: Calendar, color: 'bg-green-500' },
        'App\\Notifications\\AlertNotification': { icon: AlertCircle, color: 'bg-red-500' },
        'App\\Notifications\\DocumentNotification': { icon: FileText, color: 'bg-purple-500' },
        'App\\Notifications\\UserNotification': { icon: User, color: 'bg-indigo-500' },
        // Add more notification types as needed
    };

    return styles[type] || { icon: Bell, color: 'bg-gray-500' };
};

// Helper function to format time
const formatTime = (dateString: string) => {
    const date = new Date(dateString);
    const now = new Date();
    const diffInMinutes = Math.floor((now.getTime() - date.getTime()) / (1000 * 60));

    if (diffInMinutes < 1) return 'Just now';
    if (diffInMinutes < 60) return `${diffInMinutes} minute${diffInMinutes > 1 ? 's' : ''} ago`;

    const diffInHours = Math.floor(diffInMinutes / 60);
    if (diffInHours < 24) return `${diffInHours} hour${diffInHours > 1 ? 's' : ''} ago`;

    const diffInDays = Math.floor(diffInHours / 24);
    if (diffInDays < 7) return `${diffInDays} day${diffInDays > 1 ? 's' : ''} ago`;

    return date.toLocaleDateString();
};

// Transform Laravel notifications to display format
const transformedNotifications = computed(() => {
    return props.allNotifications.data.map(notification => {
        const style = getNotificationStyle(notification.type);

        return {
            id: notification.id,
            type: notification.type,
            title: notification.data.title || 'Notification',
            description: notification.data.message || notification.data.body || 'No description available',
            time: formatTime(notification.created_at),
            read_at: notification.read_at,
            icon: style.icon,
            color: style.color,
            avatar: notification.data.user?.avatar || null,
            raw: notification // Keep original data for backend operations
        };
    });
});

// Computed properties
const unreadNotifications = computed(() =>
    transformedNotifications.value.filter(n => !n.read_at)
);

const filteredNotifications = computed(() => {
    if (filterType.value === 'unread') {
        return unreadNotifications.value;
    }
    if (filterType.value === 'read') {
        return transformedNotifications.value.filter(n => n.read_at);
    }
    return transformedNotifications.value;
});

const isAllSelected = computed(() =>
    filteredNotifications.value.length > 0 &&
    selectedNotifications.value.length === filteredNotifications.value.length
);

const hasSelected = computed(() => selectedNotifications.value.length > 0);

// Methods
const toggleSelectAll = () => {
    if (isAllSelected.value) {
        selectedNotifications.value = [];
    } else {
        selectedNotifications.value = filteredNotifications.value.map(n => n.id);
    }
};

const toggleNotificationSelection = (id: string) => {
    const index = selectedNotifications.value.indexOf(id);
    if (index > -1) {
        selectedNotifications.value.splice(index, 1);
    } else {
        selectedNotifications.value.push(id);
    }
};

const markAsRead = (id?: string) => {
    if (id) {
        // Mark single notification as read
        router.patch(`/notifications/${id}/read`, {}, {
            preserveState: true,
            preserveScroll: true,
        });
    } else {
        // Mark selected notifications as read
        router.patch('/notifications/mark-selected-read', {
            notification_ids: selectedNotifications.value
        }, {
            preserveState: true,
            preserveScroll: true,
            onSuccess: () => {
                selectedNotifications.value = [];
            }
        });
    }
};

const markAllAsRead = () => {
    router.patch('/notifications/mark-all-read', {}, {
        preserveState: true,
        preserveScroll: true,
        onSuccess: () => {
            selectedNotifications.value = [];
        }
    });
};

const deleteSelected = () => {
    router.delete('/notifications/delete-selected', {
        data: { notification_ids: selectedNotifications.value },
        preserveState: true,
        preserveScroll: true,
        onSuccess: () => {
            selectedNotifications.value = [];
        }
    });
};
</script>

<template>
    <AppLayout>
        <Head title="Notifications" />

        <div class="p-6 max-w-4xl mx-auto">
            <!-- Header Section -->
            <div class="mb-8">
                <div class="flex items-center gap-3 mb-2">
                    <div class="p-2 bg-blue-100 dark:bg-blue-900/20 rounded-lg">
                        <Bell class="h-6 w-6 text-blue-600 dark:text-blue-400" />
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Notifications</h1>
                        <p class="text-gray-600 dark:text-gray-400">
                            {{ props.unreadCount }} unread of {{ props.allNotifications.total }} total
                        </p>
                    </div>
                </div>
            </div>

            <!-- Action Bar -->
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4 mb-6 shadow-sm">
                <div class="flex items-center justify-between gap-4">
                    <!-- Left: Select All & Bulk Actions -->
                    <div class="flex items-center gap-4">
                        <div class="flex items-center gap-2">
                            <Checkbox
                                :checked="isAllSelected"
                                @update:checked="toggleSelectAll"
                                class="rounded"
                            />
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                Select All
              </span>
                        </div>

                        <div v-if="hasSelected" class="flex items-center gap-2">
                            <Button
                                @click="markAsRead()"
                                variant="outline"
                                size="sm"
                                class="gap-2 text-green-700 border-green-200 hover:bg-green-50 dark:text-green-400 dark:border-green-800 dark:hover:bg-green-900/20"
                            >
                                <Check class="h-4 w-4" />
                                Mark Selected Read
                            </Button>

                            <Button
                                @click="deleteSelected"
                                variant="outline"
                                size="sm"
                                class="gap-2 text-red-700 border-red-200 hover:bg-red-50 dark:text-red-400 dark:border-red-800 dark:hover:bg-red-900/20"
                            >
                                <Trash2 class="h-4 w-4" />
                                Delete Selected
                            </Button>
                        </div>
                    </div>

                    <!-- Right: Filter & Mark All Read -->
                    <div class="flex items-center gap-2">
                        <select
                            v-model="filterType"
                            class="px-3 py-1.5 text-sm border border-gray-200 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300"
                        >
                            <option value="all">All</option>
                            <option value="unread">Unread</option>
                            <option value="read">Read</option>
                        </select>

                        <Button
                            v-if="props.unreadCount > 0"
                            @click="markAllAsRead"
                            class="gap-2 bg-blue-600 hover:bg-blue-700 text-white"
                            size="sm"
                        >
                            <CheckCheck class="h-4 w-4" />
                            Mark All Read
                        </Button>
                    </div>
                </div>
            </div>

            <!-- Notifications List -->
            <div class="space-y-3">
                <div
                    v-if="filteredNotifications.length === 0"
                    class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-8 text-center shadow-sm"
                >
                    <Bell class="h-12 w-12 text-gray-400 mx-auto mb-3" />
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-1">No notifications</h3>
                    <p class="text-gray-600 dark:text-gray-400">
                        {{ filterType === 'unread' ? 'All caught up! No unread notifications.' : 'You have no notifications yet.' }}
                    </p>
                </div>

                <div
                    v-for="notification in filteredNotifications"
                    :key="notification.id"
                    :class="[
            'bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4 shadow-sm transition-all duration-200 hover:shadow-md cursor-pointer group',
            !notification.read_at && 'ring-2 ring-blue-100 dark:ring-blue-900/50 bg-blue-50/50 dark:bg-blue-950/20 border-blue-200 dark:border-blue-800',
            selectedNotifications.includes(notification.id) && 'ring-2 ring-blue-500 dark:ring-blue-400 bg-blue-50 dark:bg-blue-950/30'
          ]"
                >
                    <div class="flex gap-4">
                        <!-- Selection Checkbox -->
                        <div class="flex items-start pt-1">
                            <Checkbox
                                :checked="selectedNotifications.includes(notification.id)"
                                @update:checked="() => toggleNotificationSelection(notification.id)"
                                class="rounded"
                            />
                        </div>

                        <!-- Icon/Avatar -->
                        <div class="flex-shrink-0 relative">
                            <div v-if="notification.avatar" class="relative">
                                <img
                                    :src="notification.avatar"
                                    :alt="notification.title"
                                    class="w-10 h-10 rounded-full object-cover border-2 border-white dark:border-gray-700 shadow-sm"
                                />
                                <div v-if="!notification.read_at" class="absolute -top-1 -right-1 w-3 h-3 bg-blue-500 rounded-full border-2 border-white dark:border-gray-800"></div>
                            </div>
                            <div v-else :class="['p-2 rounded-lg', notification.color]">
                                <component :is="notification.icon" class="h-5 w-5 text-white" />
                                <div v-if="!notification.read_at" class="absolute -top-1 -right-1 w-3 h-3 bg-blue-500 rounded-full border-2 border-white dark:border-gray-800"></div>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex-1">
                                    <h3 :class="[
                    'font-semibold text-gray-900 dark:text-white mb-1 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors',
                    !notification.read_at && 'text-gray-900 dark:text-white'
                  ]">
                                        {{ notification.title }}
                                    </h3>
                                    <p :class="[
                    'text-sm text-gray-600 dark:text-gray-400 leading-relaxed mb-2',
                    !notification.read_at && 'text-gray-700 dark:text-gray-300'
                  ]">
                                        {{ notification.description }}
                                    </p>
                                    <div class="flex items-center gap-2">
                                        <Clock class="h-3 w-3 text-gray-400" />
                                        <span class="text-xs text-gray-500 dark:text-gray-400">{{ notification.time }}</span>
                                        <div v-if="notification.read_at" class="flex items-center gap-1 ml-2">
                                            <Check class="h-3 w-3 text-green-500" />
                                            <span class="text-xs text-green-600 dark:text-green-400">Read</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Individual Actions -->
                                <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <Button
                                        v-if="!notification.read_at"
                                        @click.stop="markAsRead(notification.id)"
                                        variant="ghost"
                                        size="sm"
                                        class="h-8 w-8 p-0 text-green-600 hover:bg-green-100 dark:text-green-400 dark:hover:bg-green-900/20"
                                    >
                                        <Check class="h-4 w-4" />
                                    </Button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Stats -->
            <div class="mt-8 text-center text-sm text-gray-500 dark:text-gray-400">
                Showing {{ filteredNotifications.length }} of {{ props.allNotifications.total }} notifications
            </div>
        </div>
    </AppLayout>
</template>
