<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import {useAuth} from '@/utils/auth';
const auth = useAuth();
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
    Filter,
    X,
    Phone
} from 'lucide-vue-next';
import { ref, computed } from 'vue';

// Props from Laravel controller
const props = defineProps<{
    user?: string;
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

// Format username to Pascal case
const formattedUser = computed(() => {
    if (!props.user) return 'User';
    return props.user
        .toLowerCase()
        .split(' ')
        .map(word => word.charAt(0).toUpperCase() + word.slice(1))
        .join(' ');
});

// Helper function to get icon and color based on notification type
const getNotificationStyle = (type: string) => {
    const styles = {
        'App\\Notifications\\MessageNotification': { icon: Mail, color: 'from-blue-500 to-blue-600', bgColor: 'bg-blue-100', iconColor: 'text-blue-600' },
        'App\\Notifications\\SystemNotification': { icon: Settings, color: 'from-orange-500 to-orange-600', bgColor: 'bg-orange-100', iconColor: 'text-orange-600' },
        'App\\Notifications\\ReminderNotification': { icon: Calendar, color: 'from-green-500 to-green-600', bgColor: 'bg-green-100', iconColor: 'text-green-600' },
        'App\\Notifications\\AlertNotification': { icon: AlertCircle, color: 'from-red-500 to-red-600', bgColor: 'bg-red-100', iconColor: 'text-red-600' },
        'App\\Notifications\\DocumentNotification': { icon: FileText, color: 'from-purple-500 to-purple-600', bgColor: 'bg-purple-100', iconColor: 'text-purple-600' },
        'App\\Notifications\\UserNotification': { icon: User, color: 'from-indigo-500 to-indigo-600', bgColor: 'bg-indigo-100', iconColor: 'text-indigo-600' },
    };

    return styles[type as keyof typeof styles] || {
        icon: Bell,
        color: 'from-gray-500 to-gray-600',
        bgColor: 'bg-gray-100',
        iconColor: 'text-gray-600'
    };
};

// Helper function to format time
const formatTime = (dateString: string) => {
    const date = new Date(dateString);
    const now = new Date();
    const diffInMinutes = Math.floor((now.getTime() - date.getTime()) / (1000 * 60));

    if (diffInMinutes < 1) return 'Just now';
    if (diffInMinutes < 60) return `${diffInMinutes}m ago`;

    const diffInHours = Math.floor(diffInMinutes / 60);
    if (diffInHours < 24) return `${diffInHours}h ago`;

    const diffInDays = Math.floor(diffInHours / 24);
    if (diffInDays < 7) return `${diffInDays}d ago`;

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
            bgColor: style.bgColor,
            iconColor: style.iconColor,
            avatar: notification.data.user?.avatar || null,
            userName: notification.data.user?.name || null,
            priority: notification.read_at ? 'low' : 'medium',
            raw: notification
        };
    });
});

const computedUnreadNotifications = computed(() =>
    transformedNotifications.value.filter(n => !n.read_at)
);

const filteredNotifications = computed(() => {
    if (filterType.value === 'unread') {
        return computedUnreadNotifications.value;
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
        router.patch(`/notifications/${id}/read`, {}, {
            preserveState: true,
            preserveScroll: true,
        });
    } else {
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

const removeNotification = (id: string) => {
    router.delete(`/notifications/${id}`, {
        preserveState: true,
        preserveScroll: true,
    });
};

const getPriorityIndicator = (priority: string) => {
    const indicators = {
        high: 'bg-red-500 animate-pulse',
        medium: 'bg-yellow-500',
        low: 'bg-green-500'
    }
    return indicators[priority as keyof typeof indicators]
}
</script>

<template>
    <AppLayout>
        <Head title="Notifications" />

        <div class="min-h-screen bg-gradient-to-br from-gray-50 via-blue-50 to-indigo-100 dark:from-gray-900 dark:via-blue-900/20 dark:to-indigo-900/20">
            <div class="w-full max-w-5xl mx-auto p-6">
                <div class="bg-gradient-to-br from-white to-gray-50 dark:from-gray-800 dark:to-gray-900 rounded-3xl shadow-2xl border border-gray-200/50 dark:border-gray-700/50 overflow-hidden backdrop-blur-sm">

                    <!-- Enhanced Header -->
                    <div class="relative bg-gradient-to-r from-indigo-600 via-purple-600 to-blue-600 p-8">
                        <div class="absolute inset-0 bg-black/10"></div>
                        <div class="relative z-10">
                            <div class="flex items-center gap-4 mb-3">
                                <div class="relative">
                                    <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-3xl flex items-center justify-center border border-white/30">
                                        <Bell class="w-8 h-8 text-white" />
                                    </div>
                                    <div v-if="props.unreadCount > 0" class="absolute -top-2 -right-2 w-8 h-8 bg-red-500 rounded-full flex items-center justify-center text-white text-sm font-bold animate-bounce">
                                        {{ props.unreadCount }}
                                    </div>
                                </div>
                                <div>
                                    <h1 class="text-3xl font-bold text-white mb-1">Notifications</h1>
                                    <p class="text-white/80">
                                        {{ props.unreadCount }} unread • {{ props.allNotifications.total }} total
                                    </p>
                                    <p v-if="formattedUser" class="text-white/70 text-sm mt-1">
                                        Welcome back, {{ auth.name }}! 👋
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Enhanced Controls -->
                    <div class="px-8 py-6 bg-white/50 dark:bg-gray-800/50 backdrop-blur-sm border-b border-gray-200/50 dark:border-gray-700/50">
                        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
                            <!-- Left: Select All & Bulk Actions -->
                            <div class="flex flex-wrap items-center gap-4">
                                <div class="flex items-center gap-3">
                                    <Checkbox
                                        :checked="isAllSelected"
                                        @update:checked="toggleSelectAll"
                                        class="rounded-md border-2"
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
                                        class="gap-2 text-green-700 border-green-200 hover:bg-green-50 dark:text-green-400 dark:border-green-700 dark:hover:bg-green-900/20 rounded-xl transition-all duration-200 hover:scale-105"
                                    >
                                        <Check class="h-4 w-4" />
                                        Mark Selected Read ({{ selectedNotifications.length }})
                                    </Button>

                                    <Button
                                        @click="deleteSelected"
                                        variant="outline"
                                        size="sm"
                                        class="gap-2 text-red-700 border-red-200 hover:bg-red-50 dark:text-red-400 dark:border-red-700 dark:hover:bg-red-900/20 rounded-xl transition-all duration-200 hover:scale-105"
                                    >
                                        <Trash2 class="h-4 w-4" />
                                        Delete Selected
                                    </Button>
                                </div>
                            </div>

                            <!-- Right: Filter & Mark All Read -->
                            <div class="flex items-center gap-3">
                                <div class="relative">
                                    <select
                                        v-model="filterType"
                                        class="appearance-none bg-white dark:bg-gray-700 border-2 border-gray-300 dark:border-gray-600 rounded-xl px-4 py-2 pr-10 text-sm font-medium text-gray-700 dark:text-gray-300 hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200"
                                    >
                                        <option value="all">All Notifications</option>
                                        <option value="unread">Unread Only</option>
                                        <option value="read">Read Only</option>
                                    </select>
                                    <Filter class="absolute right-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" />
                                </div>

                                <Button
                                    v-if="props.unreadCount > 0"
                                    @click="markAllAsRead"
                                    class="gap-2 bg-gradient-to-r from-indigo-600 to-purple-600 text-white hover:from-indigo-700 hover:to-purple-700 transition-all duration-200 transform hover:scale-105 shadow-lg rounded-xl"
                                    size="sm"
                                >
                                    <CheckCheck class="h-4 w-4" />
                                    Mark All Read
                                </Button>
                            </div>
                        </div>
                    </div>

                    <!-- Enhanced Notifications List -->
                    <div class="max-h-[70vh] overflow-y-auto px-4">
                        <transition-group
                            name="notification"
                            tag="div"
                            enter-active-class="transition-all duration-500 ease-out"
                            leave-active-class="transition-all duration-300 ease-in"
                            enter-from-class="opacity-0 -translate-y-4 scale-95"
                            leave-to-class="opacity-0 translate-x-full scale-95"
                            move-class="transition-transform duration-300 ease-out"
                            class="space-y-4 py-6"
                        >
                            <!-- Empty State -->
                            <div
                                v-if="filteredNotifications.length === 0"
                                key="empty"
                                class="px-8 py-16 text-center"
                            >
                                <div class="w-24 h-24 bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-800 rounded-3xl flex items-center justify-center mx-auto mb-6 shadow-inner">
                                    <Bell class="w-12 h-12 text-gray-400" />
                                </div>
                                <h3 class="text-xl font-semibold text-gray-600 dark:text-gray-400 mb-2">
                                    No {{ filterType }} notifications
                                </h3>
                                <p class="text-gray-500 dark:text-gray-500">
                                    {{ filterType === 'unread' ? 'All caught up! You\'re doing great! 🎉' : 'No notifications to display.' }}
                                </p>
                            </div>

                            <!-- Notification Items -->
                            <div
                                v-for="notification in filteredNotifications"
                                :key="notification.id"
                                @click="!notification.read_at && markAsRead(notification.id)"
                                class="group relative mx-4 rounded-2xl border-2 transition-all duration-300 cursor-pointer hover:shadow-xl hover:scale-[1.02] hover:-translate-y-1"
                                :class="[
                                    notification.read_at
                                        ? 'bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-700 border-gray-200 dark:border-gray-600 opacity-75 hover:opacity-100'
                                        : 'bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 border-blue-200 dark:border-blue-700 shadow-lg',
                                    selectedNotifications.includes(notification.id) && 'ring-4 ring-indigo-300 dark:ring-indigo-600 scale-105'
                                ]"
                            >
                                <!-- Priority Indicator -->
                                <div
                                    class="absolute top-4 left-4 w-3 h-3 rounded-full"
                                    :class="getPriorityIndicator(notification.priority)"
                                ></div>

                                <!-- Unread Indicator -->
                                <div
                                    v-if="!notification.read_at"
                                    class="absolute top-4 right-4 w-3 h-3 bg-indigo-500 rounded-full animate-pulse ring-2 ring-indigo-200"
                                ></div>

                                <div class="p-6 pl-10">
                                    <div class="flex items-start gap-4">
                                        <!-- Selection Checkbox -->
                                        <div class="flex items-center pt-2">
                                            <Checkbox
                                                :checked="selectedNotifications.includes(notification.id)"
                                                @update:checked="() => toggleNotificationSelection(notification.id)"
                                                @click.stop
                                                class="rounded-md border-2"
                                            />
                                        </div>

                                        <!-- Avatar/Icon -->
                                        <div class="flex-shrink-0 relative">
                                            <div v-if="notification.avatar" class="relative">
                                                <img
                                                    :src="notification.avatar"
                                                    :alt="notification.title"
                                                    class="w-14 h-14 rounded-2xl object-cover border-2 border-white dark:border-gray-700 shadow-lg"
                                                />
                                            </div>
                                            <div v-else :class="['w-14 h-14 rounded-2xl flex items-center justify-center shadow-lg border-2 border-white/50 dark:border-gray-700/50', notification.bgColor]">
                                                <component :is="notification.icon" :class="['h-7 w-7', notification.iconColor]" />
                                            </div>
                                        </div>

                                        <!-- Content -->
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-start justify-between gap-4 mb-3">
                                                <h4 class="text-lg font-bold text-gray-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors duration-200"
                                                    :class="{ 'text-gray-600 dark:text-gray-400': notification.read_at }">
                                                    {{ notification.title }}
                                                </h4>
                                                <button
                                                    @click.stop="removeNotification(notification.id)"
                                                    class="opacity-0 group-hover:opacity-100 text-gray-400 hover:text-red-500 transition-all duration-200 transform hover:scale-110 p-2 rounded-xl hover:bg-white/50 dark:hover:bg-gray-800/50"
                                                >
                                                    <X class="w-5 h-5" />
                                                </button>
                                            </div>

                                            <!-- Job Interest Specific Content - Compact Version -->
                                            <div v-if="notification.raw.data.type === 'job_interest'" class="space-y-3">
                                                <!-- Single Compact Card -->
                                                <div class="bg-white/70 dark:bg-gray-800/70 rounded-lg p-3 border border-gray-200/50 dark:border-gray-600/50">
                                                    <!-- Header with applicant and job -->
                                                    <div class="flex items-center justify-between mb-3">
                                                        <div class="flex items-center gap-3">
                                                            <!-- Compact Avatar -->
                                                            <div v-if="notification.raw.data.user.avatar" class="w-8 h-8 rounded-lg overflow-hidden border border-white dark:border-gray-600">
                                                                <img :src="notification.raw.data.user.avatar" :alt="notification.raw.data.user.name" class="w-full h-full object-cover" />
                                                            </div>
                                                            <div v-else class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center text-white font-bold text-xs">
                                                                {{ notification.raw.data.user.name.charAt(0).toUpperCase() }}
                                                            </div>

                                                            <!-- Applicant Name -->
                                                            <div>
                                                                <h5 class="font-semibold text-gray-900 dark:text-white text-sm">
                                                                    {{ notification.raw.data.user.name }}
                                                                </h5>
                                                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                                                    Applied for: <span class="font-medium">{{ notification.raw.data.job.title }}</span>
                                                                </p>
                                                            </div>
                                                        </div>

                                                        <!-- License Status -->
                                                        <span v-if="notification.raw.data.user.licence_status"
                                                              class="text-xs px-2 py-1 rounded-full"
                                                              :class="notification.raw.data.user.licence_status === 'active'
                                                                ? 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400'
                                                                : 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400'">
                                                            {{ notification.raw.data.user.licence_status }} license
                                                        </span>
                                                    </div>

                                                    <!-- Contact Info Row -->
                                                    <div class="flex items-center gap-4 text-xs text-gray-600 dark:text-gray-400 mb-3">
                                                        <div class="flex items-center gap-1">
                                                            <Mail class="w-3 h-3" />
                                                            <span>{{ notification.raw.data.user.email }}</span>
                                                        </div>
                                                        <div v-if="notification.raw.data.user.contacts" class="flex items-center gap-1">
                                                            <Phone class="w-3 h-3" />
                                                            <span>{{ notification.raw.data.user.contacts }}</span>
                                                        </div>
                                                    </div>

                                                    <!-- Quick Action Buttons -->
                                                    <div class="flex items-center gap-2">
                                                        <Button
                                                            @click.stop="window.open(`mailto:${notification.raw.data.user.email}?subject=Regarding your application for ${notification.raw.data.job.title}`, '_blank')"
                                                            variant="outline"
                                                            size="sm"
                                                            class="gap-1 text-xs px-2 py-1 h-7 text-blue-700 border-blue-200 hover:bg-blue-50 dark:text-blue-400 dark:border-blue-700 dark:hover:bg-blue-900/20 rounded-lg"
                                                        >
                                                            <Mail class="w-3 h-3" />
                                                            Email
                                                        </Button>

                                                        <Button
                                                            v-if="notification.raw.data.user.contacts"
                                                            @click.stop="window.open(`tel:${notification.raw.data.user.contacts}`, '_self')"
                                                            variant="outline"
                                                            size="sm"
                                                            class="gap-1 text-xs px-2 py-1 h-7 text-green-700 border-green-200 hover:bg-green-50 dark:text-green-400 dark:border-green-700 dark:hover:bg-green-900/20 rounded-lg"
                                                        >
                                                            <Phone class="w-3 h-3" />
                                                            Call
                                                        </Button>

                                                        <Button
                                                            @click.stop="window.open(notification.raw.data.action_url, '_blank')"
                                                            variant="outline"
                                                            size="sm"
                                                            class="gap-1 text-xs px-2 py-1 h-7 text-indigo-700 border-indigo-200 hover:bg-indigo-50 dark:text-indigo-400 dark:border-indigo-700 dark:hover:bg-indigo-900/20 rounded-lg"
                                                        >
                                                            <FileText class="w-3 h-3" />
                                                            View Job
                                                        </Button>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Default content for other notification types -->
                                            <div v-else>
                                                <p class="text-gray-700 dark:text-gray-300 leading-relaxed mb-4 text-sm"
                                                   :class="{ 'text-gray-500 dark:text-gray-500': notification.read_at }">
                                                    {{ notification.description }}
                                                </p>
                                            </div>

                                            <!-- Time and Status Footer -->
                                            <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-200/50 dark:border-gray-600/50">
                                                <div class="flex items-center gap-3">
                                                    <div class="flex items-center gap-2 text-xs bg-white/60 dark:bg-gray-800/60 px-3 py-1.5 rounded-full">
                                                        <Clock class="w-3 h-3 text-gray-500" />
                                                        <span class="font-medium text-gray-600 dark:text-gray-400">{{ notification.time }}</span>
                                                    </div>

                                                    <span v-if="!notification.read_at" class="bg-indigo-100 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide">
                                                        New
                                                    </span>

                                                    <div v-if="notification.read_at" class="flex items-center gap-1">
                                                        <Check class="h-3 w-3 text-green-500" />
                                                        <span class="text-xs text-green-600 dark:text-green-400 font-medium">Read</span>
                                                    </div>
                                                </div>

                                                <Button
                                                    v-if="!notification.read_at"
                                                    @click.stop="markAsRead(notification.id)"
                                                    variant="ghost"
                                                    size="sm"
                                                    class="opacity-0 group-hover:opacity-100 h-8 px-3 text-green-600 dark:text-green-400 hover:bg-green-100 dark:hover:bg-green-900/20 rounded-xl transition-all duration-200"
                                                >
                                                    <Check class="h-4 w-4 mr-1" />
                                                    Mark Read
                                                </Button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </transition-group>
                    </div>

                    <!-- Enhanced Footer -->
                    <div class="px-8 py-6 bg-gradient-to-r from-gray-50 to-white dark:from-gray-800 dark:to-gray-700 border-t border-gray-200/50 dark:border-gray-600/50">
                        <div class="flex items-center justify-between">
                            <p class="text-sm text-gray-500 dark:text-gray-400 flex items-center gap-2">
                                <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                                Showing {{ filteredNotifications.length }} of {{ props.allNotifications.total }} notifications
                            </p>
                            <div class="flex items-center gap-2 text-xs text-gray-400">
                                <span>Page {{ props.allNotifications.current_page }} of {{ props.allNotifications.last_page }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
