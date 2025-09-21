<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'

const props = defineProps({
    events: Array
});

// Format date function
const formatDate = (dateString: string) => {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
};

const formatTime = (dateString: string) => {
    const date = new Date(dateString);
    return date.toLocaleTimeString('en-US', {
        hour: '2-digit',
        minute: '2-digit',
        hour12: true
    });
};

const truncateText = (text: string | null | undefined, maxLength: number = 120) => {
    if (!text) return '';
    if (text.length <= maxLength) return text;
    return text.slice(0, maxLength) + '...';
};

// Helper function to get full image URL
const getFullImageUrl = (imagePath: string) => {
    if (!imagePath) return '';
    // Handle both relative and absolute paths
    if (imagePath.startsWith('http')) return imagePath;
    // Remove leading slash if present to avoid double slashes
    const cleanPath = imagePath.startsWith('/') ? imagePath.slice(1) : imagePath;
    return `${window.location.origin}/${cleanPath}`;
};

</script>

<template>
    <AppLayout>
        <Head title="Events" />

        <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="mb-12">
                    <!-- Title and Button Row -->
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
                        <div class="text-center md:text-left mb-4 md:mb-0">
                            <h1 class="text-5xl font-bold bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-600 bg-clip-text text-transparent mb-2">
                                Upcoming Events
                            </h1>
                            <div class="w-24 h-1 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full mx-auto md:mx-0"></div>
                        </div>

                        <!-- Create Event Button -->
                        <div class="flex justify-center md:justify-end space-x-4">
                            <a href="/events/create"
                               class="group relative inline-flex items-center px-8 py-4 bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-600 text-white font-semibold rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:scale-105 hover:-translate-y-1 focus:outline-none focus:ring-4 focus:ring-purple-300">
                                <div class="absolute inset-0 bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-600 rounded-2xl blur opacity-75 group-hover:opacity-100 transition-opacity duration-300"></div>
                                <div class="relative flex items-center">
                                    <svg class="w-5 h-5 mr-2 group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Create New Event
                                </div>
                            </a>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="text-center md:text-left">
                        <p class="text-xl text-slate-600 max-w-3xl leading-relaxed">
                            Discover amazing events happening around you. Don't miss out on these exciting opportunities! ✨
                        </p>
                    </div>
                </div>

                <!-- Events Grid -->
                <div v-if="props.events && props.events.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <!-- Event Card -->
                    <div v-for="event in props.events" :key="event.id"
                         class="group bg-white rounded-3xl shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 overflow-hidden border border-slate-200 backdrop-blur-sm">

                        <!-- Event Image Container -->
                        <div class="relative h-56 overflow-hidden">
                            <!-- Event Image -->
                            <img v-if="event?.image_path"
                                 :src="getFullImageUrl(event.image_path)"
                                 :alt="event.title || 'Event image'"
                                 class="w-full h-full object-cover transition-all duration-700 group-hover:scale-110 group-hover:rotate-1"
                                 @error="$event.target.style.display = 'none'; $event.target.nextElementSibling.style.display = 'block'">

                            <!-- Fallback gradient when no image -->
                            <div v-if="!event?.image_path" class="w-full h-full bg-gradient-to-br from-violet-400 via-purple-500 to-indigo-600 relative">
                                <div class="absolute inset-0 bg-gradient-to-br from-pink-400/20 via-purple-500/30 to-indigo-600/40"></div>
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <div class="text-center text-white">
                                        <svg class="w-16 h-16 mx-auto mb-4 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                        </svg>
                                        <p class="text-lg font-medium opacity-90">Event Image</p>
                                    </div>
                                </div>
                                <!-- Animated background pattern -->
                                <div class="absolute inset-0 opacity-10">
                                    <div class="absolute top-0 -left-4 w-72 h-72 bg-purple-300 rounded-full mix-blend-multiply filter blur-xl animate-pulse"></div>
                                    <div class="absolute top-0 -right-4 w-72 h-72 bg-yellow-300 rounded-full mix-blend-multiply filter blur-xl animate-pulse animation-delay-2000"></div>
                                    <div class="absolute -bottom-8 left-20 w-72 h-72 bg-pink-300 rounded-full mix-blend-multiply filter blur-xl animate-pulse animation-delay-4000"></div>
                                </div>
                            </div>

                            <!-- Fallback gradient for broken images (hidden by default) -->
                            <div class="w-full h-full bg-gradient-to-br from-emerald-400 via-cyan-500 to-blue-600 relative" style="display: none;">
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <div class="text-center text-white">
                                        <svg class="w-12 h-12 mx-auto mb-2 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        <p class="text-sm opacity-90">Image unavailable</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Gradient overlay -->
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>

                            <!-- Date badge -->
                            <div class="absolute top-4 left-4 transform group-hover:scale-110 transition-transform duration-300">
                                <div class="bg-white/95 backdrop-blur-md rounded-2xl px-4 py-3 text-center shadow-lg border border-white/20">
                                    <div class="text-2xl font-bold text-slate-800">
                                        {{ event?.start_date ? new Date(event.start_date).getDate() : '?' }}
                                    </div>
                                    <div class="text-xs font-semibold text-slate-600 uppercase tracking-wide">
                                        {{ event?.start_date ? new Date(event.start_date).toLocaleDateString('en-US', { month: 'short' }) : 'TBD' }}
                                    </div>
                                </div>
                            </div>

                            <!-- Status badge -->
                            <div class="absolute top-4 right-4">
                                <div class="bg-green-500 text-white text-xs font-bold px-3 py-1 rounded-full flex items-center space-x-1 shadow-lg">
                                    <div class="w-2 h-2 bg-white rounded-full animate-pulse"></div>
                                    <span>LIVE</span>
                                </div>
                            </div>
                        </div>

                        <!-- Card Content -->
                        <div class="p-6 space-y-4">
                            <!-- Event Title -->
                            <div class="space-y-2">
                                <h3 class="text-xl font-bold text-slate-900 group-hover:text-purple-600 transition-colors duration-300 leading-tight line-clamp-2">
                                    {{ event?.title || 'Untitled Event' }}
                                </h3>
                                <div class="w-12 h-0.5 bg-gradient-to-r from-purple-500 to-blue-500 rounded-full transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300"></div>
                            </div>

                            <!-- Date and Time Info -->
                            <div class="space-y-3">
                                <div class="flex items-center space-x-3 text-slate-600">
                                    <div class="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center group-hover:bg-blue-200 transition-colors duration-300">
                                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-sm font-medium text-slate-900">{{ formatDate(event.start_date) }}</p>
                                    </div>
                                </div>

                                <div class="flex items-center space-x-3 text-slate-600">
                                    <div class="flex-shrink-0 w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center group-hover:bg-green-200 transition-colors duration-300">
                                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-sm font-medium text-slate-900">
                                            {{ formatTime(event.start_date) }} - {{ formatTime(event.end_date) }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Event Description -->
                            <div class="space-y-2">
                                <div v-if="event.description"
                                     v-html="truncateText(event.description)"
                                     class="text-slate-600 text-sm leading-relaxed prose prose-sm max-w-none">
                                </div>
                                <p v-else class="text-slate-400 text-sm italic">
                                    No description available.
                                </p>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="px-6 pb-6">
                            <div class="flex items-center justify-between space-x-3">
                                <a :href="event?.link || '#'"
                                   :target="event?.link ? '_blank' : '_self'"
                                   :class="event?.link
                                     ? 'bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white shadow-lg hover:shadow-xl transform hover:scale-105'
                                     : 'bg-gray-200 text-gray-400 cursor-not-allowed'"
                                   class="flex-1 inline-flex items-center justify-center px-4 py-3 text-sm font-semibold rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
                                    <span>{{ event?.link ? 'Learn More' : 'No Link Available' }}</span>
                                    <svg v-if="event?.link" class="ml-2 w-4 h-4 group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                    </svg>
                                </a>

                                <!-- Bookmark Button -->
                                <button class="flex-shrink-0 p-3 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transform hover:scale-110">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Card Footer -->
                        <div class="px-6 py-4 bg-slate-50 border-t border-slate-100">
                            <div class="flex items-center justify-between text-xs text-slate-500">
                                <div class="flex items-center space-x-2">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>Created {{ event?.created_at ? new Date(event.created_at).toLocaleDateString() : 'Unknown' }}</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <div class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></div>
                                    <span class="font-medium text-emerald-600">Active</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Empty State -->
                <div v-if="!props.events || props.events.length === 0" class="text-center py-20">
                    <div class="w-32 h-32 mx-auto mb-8 bg-gradient-to-br from-slate-100 to-slate-200 rounded-full flex items-center justify-center shadow-inner">
                        <svg class="w-16 h-16 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-slate-900 mb-3">No events found</h3>
                    <p class="text-slate-600 text-lg max-w-md mx-auto leading-relaxed">
                        Check back later for exciting upcoming events! In the meantime, why not create your own?
                    </p>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
