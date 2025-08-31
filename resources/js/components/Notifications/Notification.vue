<!--<script setup lang="ts">-->
<!--import { ref, computed } from "vue"-->

<!--const props = defineProps<{-->
<!--    user: string-->
<!--}>()-->

<!--// Format username to Pascal case (First Name Last Name)-->
<!--const formattedUser = computed(() => {-->
<!--    return props.user-->
<!--        .toLowerCase()-->
<!--        .split(' ')-->
<!--        .map(word => word.charAt(0).toUpperCase() + word.slice(1))-->
<!--        .join(' ')-->
<!--})-->

<!--interface Notification {-->
<!--    id: number-->
<!--    title: string-->
<!--    message: string-->
<!--    type: "info" | "success" | "warning" | "error" | "job" | "message" | "system"-->
<!--    time: string-->
<!--    read: boolean-->
<!--    avatar?: string-->
<!--    priority: "low" | "medium" | "high"-->
<!--}-->

<!--// Example: notifications for the user-->
<!--const notifications = ref<Notification[]>([-->
<!--])-->

<!--const unreadCount = computed(() => notifications.value.filter(n => !n.read).length)-->
<!--const totalCount = computed(() => notifications.value.length)-->

<!--// Function to mark notification as read-->
<!--const markAsRead = (id: number) => {-->
<!--    const notification = notifsications.value.find(n => n.id === id)-->
<!--    if (notification && !notification.read) {-->
<!--        notification.read = true-->
<!--    }-->
<!--}-->

<!--// Function to mark all as read-->
<!--const markAllAsRead = () => {-->
<!--    notifications.value.forEach(n => n.read = true)-->
<!--}-->

<!--// Function to remove notification-->
<!--const removeNotification = (id: number) => {-->
<!--    const index = notifications.value.findIndex(note => note.id === id)-->
<!--    if (index > -1) {-->
<!--        notifications.value.splice(index, 1)-->
<!--    }-->
<!--}-->

<!--const selectedFilter = ref("All")-->
<!--const filterOptions = ["All", "Unread", "Read"]-->

<!--const filteredNotifications = computed(() => {-->
<!--    switch (selectedFilter.value) {-->
<!--        case "Unread":-->
<!--            return notifications.value.filter(n => !n.read)-->
<!--        case "Read":-->
<!--            return notifications.value.filter(n => n.read)-->
<!--        default:-->
<!--            return notifications.value-->
<!--    }-->
<!--})-->

<!--const getNotificationIcon = (type: string) => {-->
<!--    const icons = {-->
<!--        info: "💡",-->
<!--        success: "✅",-->
<!--        warning: "⚠️",-->
<!--        error: "❌",-->
<!--        job: "👤",-->
<!--        message: "💬",-->
<!--        system: "⚙️"-->
<!--    }-->
<!--    return icons[type as keyof typeof icons] || "📢"-->
<!--}-->

<!--const getTypeColors = (type: string, read: boolean) => {-->
<!--    const colors = {-->
<!--        info: read ? 'from-blue-50 to-blue-100 border-blue-200' : 'from-blue-100 to-blue-200 border-blue-300',-->
<!--        success: read ? 'from-emerald-50 to-emerald-100 border-emerald-200' : 'from-emerald-100 to-emerald-200 border-emerald-300',-->
<!--        warning: read ? 'from-amber-50 to-amber-100 border-amber-200' : 'from-amber-100 to-amber-200 border-amber-300',-->
<!--        error: read ? 'from-red-50 to-red-100 border-red-200' : 'from-red-100 to-red-200 border-red-300',-->
<!--        job: read ? 'from-purple-50 to-purple-100 border-purple-200' : 'from-purple-100 to-purple-200 border-purple-300',-->
<!--        message: read ? 'from-indigo-50 to-indigo-100 border-indigo-200' : 'from-indigo-100 to-indigo-200 border-indigo-300',-->
<!--        system: read ? 'from-gray-50 to-gray-100 border-gray-200' : 'from-gray-100 to-gray-200 border-gray-300'-->
<!--    }-->
<!--    return colors[type as keyof typeof colors] || colors.info-->
<!--}-->

<!--const getPriorityIndicator = (priority: string) => {-->
<!--    const indicators = {-->
<!--        high: 'bg-red-500 animate-pulse',-->
<!--        medium: 'bg-yellow-500',-->
<!--        low: 'bg-green-500'-->
<!--    }-->
<!--    return indicators[priority as keyof typeof indicators]-->
<!--}-->
<!--</script>-->

<!--<template>-->
<!--    <div class="w-full max-w-3xl mx-auto bg-gradient-to-br from-white to-gray-50 rounded-2xl shadow-xl border border-gray-200/50 overflow-hidden backdrop-blur-sm">-->
<!--        &lt;!&ndash; Animated Header &ndash;&gt;-->
<!--        <div class="relative bg-gradient-to-r from-indigo-600 via-purple-600 to-blue-600 p-8">-->
<!--            <div class="absolute inset-0 bg-black/10"></div>-->
<!--            <div class="relative z-10">-->
<!--                <div class="flex items-center gap-4 mb-3">-->
<!--                    <div class="relative">-->
<!--                        <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center border border-white/30">-->
<!--                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">-->
<!--                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM9 12l2 2 4-4M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>-->
<!--                            </svg>-->
<!--                        </div>-->
<!--                        <div v-if="unreadCount > 0" class="absolute -top-2 -right-2 w-6 h-6 bg-red-500 rounded-full flex items-center justify-center text-white text-xs font-bold animate-bounce">-->
<!--                            {{ unreadCount }}-->
<!--                        </div>-->
<!--                    </div>-->
<!--                    <div>-->
<!--                        <h2 class="text-2xl font-bold text-white">Notifications</h2>-->
<!--                        <p class="text-white/80 text-sm">{{ unreadCount }} unread • {{ totalCount }} total</p>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->

<!--        &lt;!&ndash; Enhanced Controls &ndash;&gt;-->
<!--        <div class="px-8 py-6 bg-white/50 backdrop-blur-sm border-b border-gray-200/50">-->
<!--            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">-->
<!--                <div class="flex items-center gap-3">-->
<!--                    <div class="flex items-center gap-2 text-sm text-gray-600">-->
<!--                        <input type="checkbox" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">-->
<!--                        <span>Select All</span>-->
<!--                    </div>-->
<!--                </div>-->

<!--                <div class="flex items-center gap-3">-->
<!--                    <div class="relative">-->
<!--                        <select-->
<!--                            v-model="selectedFilter"-->
<!--                            class="appearance-none bg-white border border-gray-300 rounded-xl px-4 py-2 pr-8 text-sm font-medium text-gray-700 hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200"-->
<!--                        >-->
<!--                            <option v-for="option in filterOptions" :key="option" :value="option">-->
<!--                                {{ option }}-->
<!--                            </option>-->
<!--                        </select>-->
<!--                        <svg class="absolute right-2 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">-->
<!--                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>-->
<!--                        </svg>-->
<!--                    </div>-->

<!--                    <button-->
<!--                        @click="markAllAsRead"-->
<!--                        :disabled="unreadCount === 0"-->
<!--                        class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-6 py-2 rounded-xl font-medium hover:from-indigo-700 hover:to-purple-700 transition-all duration-200 transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100 flex items-center gap-2 shadow-lg"-->
<!--                    >-->
<!--                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">-->
<!--                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>-->
<!--                        </svg>-->
<!--                        Mark All Read-->
<!--                    </button>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->

<!--        &lt;!&ndash; Enhanced Notifications List &ndash;&gt;-->
<!--        <div class="max-h-96 overflow-y-auto">-->
<!--            <transition-group-->
<!--                name="notification"-->
<!--                tag="div"-->
<!--                enter-active-class="transition-all duration-500 ease-out"-->
<!--                leave-active-class="transition-all duration-300 ease-in"-->
<!--                enter-from-class="opacity-0 -translate-y-4 scale-95"-->
<!--                leave-to-class="opacity-0 translate-x-full scale-95"-->
<!--                move-class="transition-transform duration-300 ease-out"-->
<!--            >-->
<!--                <div-->
<!--                    v-for="notification in filteredNotifications"-->
<!--                    :key="notification.id"-->
<!--                    @click="markAsRead(notification.id)"-->
<!--                    class="group relative mx-4 my-3-->
<!--                    rounded-2xl border-2 transition-all-->
<!--                    duration-300 cursor-pointer hover:shadow-lg-->
<!--                    hover:scale-[1.02] hover:-translate-y-1 bg-gradient-to-r"-->
<!--                    :class="[-->
<!--                        getTypeColors(notification.type, notification.read),-->
<!--                        notification.read ? 'opacity-75 hover:opacity-100' : 'shadow-md'-->
<!--                    ]"-->
<!--                >-->
<!--                    &lt;!&ndash; Priority Indicator &ndash;&gt;-->
<!--                    <div-->
<!--                        class="absolute top-3 left-3 w-3 h-3 rounded-full"-->
<!--                        :class="getPriorityIndicator(notification.priority)"-->
<!--                    ></div>-->

<!--                    &lt;!&ndash; Unread Indicator &ndash;&gt;-->
<!--                    <div-->
<!--                        v-if="!notification.read"-->
<!--                        class="absolute top-3 right-3 w-3 h-3 bg-indigo-500 rounded-full animate-pulse ring-2 ring-indigo-200"-->
<!--                    ></div>-->

<!--                    <div class="p-6 pl-8">-->
<!--                        <div class="flex items-start gap-4">-->
<!--                            &lt;!&ndash; Enhanced Avatar/Icon &ndash;&gt;-->
<!--                            <div class="flex-shrink-0">-->
<!--                                <div v-if="notification.avatar" class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl flex items-center justify-center text-white font-bold text-sm shadow-lg">-->
<!--                                    {{ notification.avatar }}-->
<!--                                </div>-->
<!--                                <div v-else class="w-12 h-12 bg-white/80 backdrop-blur-sm rounded-2xl flex items-center justify-center text-2xl shadow-lg border border-white/50">-->
<!--                                    {{ getNotificationIcon(notification.type) }}-->
<!--                                </div>-->
<!--                            </div>-->

<!--                            &lt;!&ndash; Content &ndash;&gt;-->
<!--                            <div class="flex-1 min-w-0">-->
<!--                                <div class="flex items-start justify-between gap-4 mb-2">-->
<!--                                    <h4 class="text-lg font-bold text-gray-900 group-hover:text-indigo-600 transition-colors duration-200"-->
<!--                                        :class="{ 'text-gray-600': notification.read }">-->
<!--                                        {{ notification.title }}-->
<!--                                    </h4>-->
<!--                                    <button-->
<!--                                        @click.stop="removeNotification(notification.id)"-->
<!--                                        class="opacity-0 group-hover:opacity-100 text-gray-400 hover:text-red-500 transition-all duration-200 transform hover:scale-110 p-1 rounded-lg hover:bg-white/50"-->
<!--                                    >-->
<!--                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">-->
<!--                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />-->
<!--                                        </svg>-->
<!--                                    </button>-->
<!--                                </div>-->

<!--                                <p class="text-gray-700 leading-relaxed mb-3 text-sm"-->
<!--                                   :class="{ 'text-gray-500': notification.read }">-->
<!--                                    {{ notification.message }}-->
<!--                                </p>-->

<!--                                <div class="flex items-center gap-3 text-xs">-->
<!--                                    <div class="flex items-center gap-1 text-gray-500 bg-white/60 px-3 py-1 rounded-full">-->
<!--                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">-->
<!--                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>-->
<!--                                        </svg>-->
<!--                                        <span class="font-medium">{{ notification.time }}</span>-->
<!--                                    </div>-->

<!--                                    <span v-if="!notification.read" class="bg-indigo-100 text-indigo-700 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide">-->
<!--                                        New-->
<!--                                    </span>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </transition-group>-->

<!--            &lt;!&ndash; Enhanced Empty State &ndash;&gt;-->
<!--            <div v-if="filteredNotifications.length === 0" class="px-8 py-16 text-center">-->
<!--                <div class="w-20 h-20 bg-gradient-to-br from-gray-100 to-gray-200 rounded-3xl flex items-center justify-center mx-auto mb-6 shadow-inner">-->
<!--                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">-->
<!--                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM9 12l2 2 4-4M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>-->
<!--                    </svg>-->
<!--                </div>-->
<!--                <h3 class="text-lg font-semibold text-gray-600 mb-2">No {{ selectedFilter.toLowerCase() }} notifications</h3>-->
<!--                <p class="text-sm text-gray-500">You're all caught up! 🎉</p>-->
<!--            </div>-->
<!--        </div>-->

<!--        &lt;!&ndash; Enhanced Footer &ndash;&gt;-->
<!--        <div class="px-8 py-4 bg-gradient-to-r from-gray-50 to-white border-t border-gray-200/50">-->
<!--            <div class="flex items-center justify-between">-->
<!--                <p class="text-xs text-gray-500 flex items-center gap-2">-->
<!--                    <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>-->
<!--                    Showing {{ filteredNotifications.length }} of {{ totalCount }} notifications-->
<!--                </p>-->
<!--                <button class="text-xs text-indigo-600 hover:text-indigo-800 font-medium transition-colors duration-200">-->
<!--                    View All →-->
<!--                </button>-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
<!--</template>-->
