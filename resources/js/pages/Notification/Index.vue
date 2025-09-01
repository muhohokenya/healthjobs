<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { useAuth } from '@/utils/auth';
const auth = useAuth();

import {
    Bell, Check, CheckCheck, Clock, User, FileText, AlertCircle, Mail,
    Calendar, Settings, Trash2, Filter, X, Phone
} from 'lucide-vue-next';
import { ref, computed, onMounted } from 'vue';

// -------------------- Props --------------------
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

// -------------------- State --------------------
const selectedNotifications = ref<string[]>([]);
const filterType = ref<string>('all');
const density = ref<'compact' | 'cozy'>('compact');
const selectedId = ref<string | null>(null);

// -------------------- Derived --------------------
const formattedUser = computed(() => {
    if (!props.user) return 'User';
    return props.user
        .toLowerCase()
        .split(' ')
        .map(w => w.charAt(0).toUpperCase() + w.slice(1))
        .join(' ');
});

const getNotificationStyle = (type: string) => {
    const styles = {
        'App\\Notifications\\MessageNotification': { icon: Mail, color: 'from-blue-500 to-blue-600', bgColor: 'bg-blue-100', iconColor: 'text-blue-600' },
        'App\\Notifications\\SystemNotification': { icon: Settings, color: 'from-orange-500 to-orange-600', bgColor: 'bg-orange-100', iconColor: 'text-orange-600' },
        'App\\Notifications\\ReminderNotification': { icon: Calendar, color: 'from-green-500 to-green-600', bgColor: 'bg-green-100', iconColor: 'text-green-600' },
        'App\\Notifications\\AlertNotification': { icon: AlertCircle, color: 'from-red-500 to-red-600', bgColor: 'bg-red-100', iconColor: 'text-red-600' },
        'App\\Notifications\\DocumentNotification': { icon: FileText, color: 'from-purple-500 to-purple-600', bgColor: 'bg-purple-100', iconColor: 'text-purple-600' },
        'App\\Notifications\\UserNotification': { icon: User, color: 'from-indigo-500 to-indigo-600', bgColor: 'bg-indigo-100', iconColor: 'text-indigo-600' },
    } as const;

    return (styles as any)[type] || {
        icon: Bell,
        color: 'from-gray-500 to-gray-600',
        bgColor: 'bg-gray-100',
        iconColor: 'text-gray-600'
    };
};

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
    if (filterType.value === 'unread') return computedUnreadNotifications.value;
    if (filterType.value === 'read') return transformedNotifications.value.filter(n => n.read_at);
    return transformedNotifications.value;
});

const isAllSelected = computed(() =>
    filteredNotifications.value.length > 0 &&
    selectedNotifications.value.length === filteredNotifications.value.length
);
const hasSelected = computed(() => selectedNotifications.value.length > 0);

// default selection (first unread, else first)
onMounted(() => {
    if (transformedNotifications.value.length) {
        selectedId.value =
            transformedNotifications.value.find(n => !n.read_at)?.id ??
            transformedNotifications.value[0].id;
    }
});

// -------------------- Helpers --------------------
const getPriorityIndicator = (priority: string) => {
    const indicators = {
        high: 'bg-red-500 animate-pulse',
        medium: 'bg-yellow-500',
        low: 'bg-green-500'
    } as const;
    return (indicators as any)[priority];
};

const rowSubtitle = (n: any) => {
    if (n.raw?.data?.type === 'job_interest') {
        const u = n.raw.data.user || {};
        const job = n.raw.data.job || {};
        const licence = u?.licence_status ? ` • ${u.licence_status} license` : '';
        return `${u?.email ?? ''} • ${u?.contacts ?? ''} • ${job?.title ?? ''}${licence}`;
    }
    return n.description;
};

// -------------------- Actions --------------------
const toggleSelectAll = () => {
    if (isAllSelected.value) selectedNotifications.value = [];
    else selectedNotifications.value = filteredNotifications.value.map(n => n.id);
};

const toggleNotificationSelection = (id: string) => {
    const idx = selectedNotifications.value.indexOf(id);
    if (idx > -1) selectedNotifications.value.splice(idx, 1);
    else selectedNotifications.value.push(id);
};

const markAsRead = (id?: string) => {
    if (id) {
        router.patch(`/notifications/${id}/read`, {}, { preserveState: true, preserveScroll: true });
    } else {
        router.patch('/notifications/mark-selected-read',
            { notification_ids: selectedNotifications.value },
            {
                preserveState: true,
                preserveScroll: true,
                onSuccess: () => { selectedNotifications.value = [] }
            });
    }
};

const markAllAsRead = () => {
    router.patch('/notifications/mark-all-read', {}, {
        preserveState: true,
        preserveScroll: true,
        onSuccess: () => { selectedNotifications.value = [] }
    });
};

const deleteSelected = () => {
    router.delete('/notifications/delete-selected', {
        data: { notification_ids: selectedNotifications.value },
        preserveState: true,
        preserveScroll: true,
        onSuccess: () => { selectedNotifications.value = [] }
    });
};

const removeNotification = (id: string) => {
    router.delete(`/notifications/${id}`, { preserveState: true, preserveScroll: true });
};

const selectRow = (nId: string) => {
    selectedId.value = nId;
    const n = transformedNotifications.value.find(n => n.id === nId);
    if (n && !n.read_at) markAsRead(nId);
};
</script>

<template>
    <AppLayout>
        <Head title="Notifications" />

        <div class="min-h-screen bg-gradient-to-br from-gray-50 via-blue-50 to-indigo-100 dark:from-gray-900 dark:via-blue-900/20 dark:to-indigo-900/20">
            <div class="w-full max-w-6xl mx-auto p-4 lg:p-6">
                <div class="bg-white/80 dark:bg-gray-900/60 rounded-2xl shadow-xl border border-gray-200/60 dark:border-gray-700/60 overflow-hidden">

                    <!-- HEADER (compact) -->
                    <div class="relative bg-gradient-to-r from-indigo-600 via-purple-600 to-blue-600 p-5">
                        <div class="flex items-center gap-3">
                            <div class="relative">
                                <div class="w-12 h-12 bg-white/20 rounded-2xl grid place-items-center border border-white/30">
                                    <Bell class="w-6 h-6 text-white" />
                                </div>
                                <div v-if="props.unreadCount>0"
                                     class="absolute -top-2 -right-2 w-7 h-7 bg-red-500 rounded-full grid place-items-center text-white text-xs font-bold">
                                    {{ props.unreadCount }}
                                </div>
                            </div>

                            <div class="min-w-0">
                                <h1 class="text-2xl font-semibold text-white">Notifications</h1>
                                <p class="text-white/80 text-sm truncate">{{ props.unreadCount }} unread • {{ props.allNotifications.total }} total</p>
                                <p class="text-white/70 text-xs">Welcome back, {{ auth.name }}! 👋</p>
                            </div>

                            <div class="ml-auto flex items-center gap-2">
                                <select v-model="filterType"
                                        class="bg-white/90 dark:bg-gray-800 border border-white/40 dark:border-gray-700 rounded-lg px-2.5 py-1.5 text-xs font-medium">
                                    <option value="all">All</option>
                                    <option value="unread">Unread</option>
                                    <option value="read">Read</option>
                                </select>
                                <Button v-if="props.unreadCount>0" @click="markAllAsRead" size="sm"
                                        class="h-8 px-3 text-xs bg-white text-indigo-700 hover:bg-white/90 rounded-lg">
                                    <CheckCheck class="w-4 h-4 mr-1" /> Mark all read
                                </Button>
                            </div>
                        </div>
                    </div>

                    <!-- TOOLBAR -->
                    <div class="px-4 py-2 border-b border-gray-200/60 dark:border-gray-700/60 flex items-center gap-3">
                        <Checkbox :checked="isAllSelected" @update:checked="toggleSelectAll" class="rounded-md" />
                        <span class="text-xs text-gray-600 dark:text-gray-300">Select all</span>

                        <div v-if="hasSelected" class="flex items-center gap-2">
                            <Button @click="markAsRead()" variant="outline" size="sm" class="h-8 px-2 text-xs">
                                <Check class="w-4 h-4 mr-1" /> Mark read ({{ selectedNotifications.length }})
                            </Button>
                            <Button @click="deleteSelected" variant="outline" size="sm" class="h-8 px-2 text-xs">
                                <Trash2 class="w-4 h-4 mr-1" /> Delete
                            </Button>
                        </div>

                        <div class="ml-auto flex items-center gap-2 text-xs text-gray-500">
                            <Filter class="w-4 h-4" />
                            <Button variant="ghost" size="sm" class="h-8 px-2 text-xs"
                                    @click="density = density==='compact' ? 'cozy' : 'compact'">
                                {{ density==='compact' ? 'Cozy view' : 'Compact view' }}
                            </Button>
                        </div>
                    </div>

                    <!-- SPLIT VIEW -->
                    <div class="grid grid-cols-1 lg:grid-cols-[380px_minmax(0,1fr)]">
                        <!-- LEFT: LIST -->
                        <div class="min-h-[60vh] max-h-[72vh] overflow-y-auto divide-y divide-gray-200/60 dark:divide-gray-700/60">
                            <div
                                v-for="n in filteredNotifications"
                                :key="n.id"
                                class="relative cursor-pointer hover:bg-indigo-50/60 dark:hover:bg-indigo-900/10"
                                :class="[
                  'px-3',
                  density==='compact' ? 'py-2' : 'py-3',
                  selectedId===n.id ? 'bg-indigo-50/80 dark:bg-indigo-900/20' : ''
                ]"
                                @click="selectRow(n.id)"
                            >
                                <div class="flex items-center gap-3">
                                    <Checkbox
                                        :checked="selectedNotifications.includes(n.id)"
                                        @update:checked="() => toggleNotificationSelection(n.id)"
                                        @click.stop
                                        class="rounded-md"
                                    />

                                    <div class="flex-shrink-0">
                                        <img v-if="n.avatar" :src="n.avatar" class="w-8 h-8 rounded-lg object-cover" />
                                        <div v-else :class="['w-8 h-8 rounded-lg grid place-items-center', n.bgColor]">
                                            <component :is="n.icon" class="w-4 h-4" :class="n.iconColor" />
                                        </div>
                                    </div>

                                    <div class="min-w-0 flex-1">
                                        <div class="flex items-center gap-2">
                                            <h4 class="text-sm font-semibold truncate"
                                                :class="n.read_at ? 'text-gray-500' : 'text-gray-900 dark:text-white'">
                                                {{ n.title }}
                                            </h4>
                                            <span v-if="!n.read_at"
                                                  class="text-[10px] px-1.5 py-0.5 rounded bg-indigo-100 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300">
                        NEW
                      </span>
                                        </div>
                                        <p class="text-xs text-gray-600 dark:text-gray-300 truncate">
                                            {{ rowSubtitle(n) }}
                                        </p>
                                    </div>

                                    <div class="flex flex-col items-end gap-1">
                                        <span class="text-[11px] text-gray-500">{{ n.time }}</span>
                                        <button class="opacity-60 hover:opacity-100" @click.stop="removeNotification(n.id)" title="Remove">
                                            <X class="w-4 h-4 text-gray-400 hover:text-red-500" />
                                        </button>
                                    </div>
                                </div>

                                <!-- tiny priority dot -->
                                <div class="absolute left-1 top-1 w-2 h-2 rounded-full" :class="getPriorityIndicator(n.priority)"></div>
                            </div>

                            <div v-if="filteredNotifications.length===0" class="p-8 text-center text-sm text-gray-500">
                                No {{ filterType }} notifications.
                            </div>
                        </div>

                        <!-- RIGHT: DETAILS -->
                        <div class="min-h-[60vh] max-h-[72vh] overflow-y-auto border-t lg:border-t-0 lg:border-l border-gray-200/60 dark:border-gray-700/60">
                            <div v-if="selectedId" class="p-4 lg:p-6" v-for="n in transformedNotifications" :key="n.id" v-show="n.id===selectedId">
                                <div class="flex items-start justify-between mb-3">
                                    <h3 class="text-base lg:text-lg font-semibold">{{ n.title }}</h3>
                                    <Button v-if="!n.read_at" @click="markAsRead(n.id)" variant="ghost" size="sm" class="h-8 px-3 text-xs">
                                        <Check class="w-4 h-4 mr-1" /> Mark read
                                    </Button>
                                </div>

                                <!-- Job interest details -->
                                <template v-if="n.raw.data.type === 'job_interest'">
                                    <div class="rounded-xl border border-gray-200/60 dark:border-gray-700/60 p-4 bg-white/70 dark:bg-gray-800/50 space-y-3">
                                        <div class="flex items-center gap-3">
                                            <img v-if="n.raw.data.user.avatar" :src="n.raw.data.user.avatar" class="w-10 h-10 rounded-lg object-cover" />
                                            <div v-else class="w-10 h-10 rounded-lg bg-gradient-to-br from-indigo-500 to-purple-600 text-white grid place-items-center font-bold">
                                                {{ n.raw.data.user.name.charAt(0).toUpperCase() }}
                                            </div>
                                            <div class="min-w-0">
                                                <div class="flex items-center gap-2">
                                                    <p class="font-semibold text-sm truncate">{{ n.raw.data.user.name }}</p>
                                                    <span v-if="n.raw.data.user.licence_status"
                                                          class="text-[10px] px-2 py-0.5 rounded-full"
                                                          :class="n.raw.data.user.licence_status==='active'
                                  ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300'
                                  : 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300'">
                            {{ n.raw.data.user.licence_status }} license
                          </span>
                                                </div>
                                                <p class="text-xs text-gray-500">Applied for: <span class="font-medium">{{ n.raw.data.job.title }}</span></p>
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs text-gray-700 dark:text-gray-300">
                                            <div class="flex items-center gap-2"><Mail class="w-3 h-3"/> {{ n.raw.data.user.email }}</div>
                                            <div v-if="n.raw.data.user.contacts" class="flex items-center gap-2"><Phone class="w-3 h-3"/> {{ n.raw.data.user.contacts }}</div>
                                        </div>

                                        <div class="flex flex-wrap items-center gap-2 pt-2">
                                            <Button
                                                @click.stop="window.open(`mailto:${n.raw.data.user.email}?subject=Regarding your application for ${n.raw.data.job.title}`, '_blank')"
                                                variant="outline" size="sm" class="h-8 px-3 text-xs">
                                                <Mail class="w-4 h-4 mr-1" /> Email
                                            </Button>
                                            <Button
                                                v-if="n.raw.data.user.contacts"
                                                @click.stop="window.open(`tel:${n.raw.data.user.contacts}`, '_self')"
                                                variant="outline" size="sm" class="h-8 px-3 text-xs">
                                                <Phone class="w-4 h-4 mr-1" /> Call
                                            </Button>
                                            <Button
                                                @click.stop="window.open(n.raw.data.action_url, '_blank')"
                                                variant="outline" size="sm" class="h-8 px-3 text-xs">
                                                <FileText class="w-4 h-4 mr-1" /> View Job
                                            </Button>
                                        </div>
                                    </div>
                                </template>

                                <!-- Default details -->
                                <template v-else>
                                    <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed">{{ n.description }}</p>
                                </template>

                                <div class="mt-4 flex items-center gap-2 text-xs text-gray-500">
                                    <Clock class="w-3 h-3" />
                                    <span>{{ n.time }}</span>
                                </div>
                            </div>

                            <div v-if="!selectedId" class="h-full grid place-items-center p-8 text-sm text-gray-500">
                                Select a notification to preview details.
                            </div>
                        </div>
                    </div>

                    <!-- FOOTER -->
                    <div class="px-4 py-3 border-t border-gray-200/60 dark:border-gray-700/60 text-xs text-gray-500 flex items-center justify-between">
                        <span>Showing {{ filteredNotifications.length }} of {{ props.allNotifications.total }}</span>
                        <span>Page {{ props.allNotifications.current_page }} of {{ props.allNotifications.last_page }}</span>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
