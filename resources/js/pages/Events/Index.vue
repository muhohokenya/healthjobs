<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import { ref } from 'vue'
import AppLayout from '@/layouts/AppLayout.vue'
import FireworksComponent from '@/components/FireworksComponent.vue' // Adjust path as needed

const props = defineProps({
    events: Array
});

// Fireworks component reference
const fireworksRef = ref()

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

const truncateText = (text: string | null | undefined, maxLength: number = 150) => {
    if (!text) return '';
    if (text.length <= maxLength) return text;
    return text.slice(0, maxLength) + '...';
};

// Function to manually trigger fireworks (for testing or special occasions)
const celebrateEvent = () => {
    if (fireworksRef.value) {
        fireworksRef.value.triggerFireworks()
    }
}
</script>

<template>
    <AppLayout>
        <Head title="Events" />

        <!-- Fireworks Component -->
<!--        <FireworksComponent ref="fireworksRef" />-->

        <div class="min-h-screen bg-gray-50 py-8">
            <div class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="mb-12">
                    <!-- Title and Button Row -->
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
                        <div class="text-center md:text-left mb-4 md:mb-0">
                            <h1 class="text-4xl font-bold text-gray-900 mb-2">Upcoming Events</h1>
                        </div>

                        <!-- Create Event Button -->
                        <div class="flex justify-center md:justify-end space-x-4">
                            <!-- Debug: Manual Fireworks Button (remove in production) -->
<!--                            <button-->
<!--                                @click="celebrateEvent"-->
<!--                                class="inline-flex items-center px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 text-sm">-->
<!--                                🎆 Celebrate-->
<!--                            </button>-->

                            <a href="/events/create"
                               class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Create New Event
                            </a>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="">
                        <p class="text-lg text-gray-600 max-w-2xl">
                            Discover amazing events happening around you. Don't miss out on these exciting opportunities!
                        </p>
                    </div>
                </div>

                <!-- Events Grid -->
                <div v-if="props.events && props.events.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <!-- Event Card -->
                    <div v-for="event in props.events" :key="event.id" class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300 group">
                        <!-- Event Image -->
                        <div class="h-48 relative overflow-hidden">
                            <!-- Event Image or Fallback Gradient -->
                            <img v-if="event?.image_path"
                                 :src="event.image_path"
                                 :alt="event.title || 'Event image'"
                                 class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105"
                                 @error="$event.target.style.display = 'none'; $event.target.nextElementSibling.style.display = 'block'">
                            <div v-else
                                 class="w-full h-full bg-gradient-to-br from-blue-500 via-purple-500 to-pink-500">
                            </div>

                            <!-- Fallback gradient for broken images -->
                            <div class="w-full h-full bg-gradient-to-br from-blue-500 via-purple-500 to-pink-500"
                                 style="display: none;">
                            </div>

                            <!-- Dark overlay -->
                            <div class="absolute inset-0 bg-black bg-opacity-20"></div>

                            <!-- Date badge -->
                            <div class="absolute top-4 left-4">
                                <div class="bg-white bg-opacity-90 backdrop-blur-sm rounded-lg px-3 py-2 text-center">
                                    <div class="text-2xl font-bold text-gray-900">
                                        {{ event?.start_date ? new Date(event.start_date).getDate() : '?' }}
                                    </div>
                                    <div class="text-xs font-medium text-gray-600 uppercase">
                                        {{ event?.start_date ? new Date(event.start_date).toLocaleDateString('en-US', { month: 'short' }) : 'TBD' }}
                                    </div>
                                </div>
                            </div>

                            <!-- Bottom gradient overlay -->
                            <div class="absolute bottom-0 left-0 right-0 h-24 bg-gradient-to-t from-black via-transparent to-transparent"></div>
                        </div>

                        <!-- Card Content -->
                        <div class="p-6">
                            <!-- Event Title -->
                            <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-blue-600 transition-colors duration-200 line-clamp-2">
                                {{ event?.title || 'Untitled Event' }}
                            </h3>

                            <!-- Date and Time -->
                            <div class="flex items-center space-x-4 mb-4 text-sm text-gray-600">
                                <div class="flex items-center space-x-1">
                                    <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 002 2z"></path>
                                    </svg>
                                    <span>{{ formatDate(event.start_date) }}</span>
                                </div>
                            </div>

                            <div class="flex items-center space-x-1 mb-4 text-sm text-gray-600">
                                <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>{{ formatTime(event.start_date) }} - {{ formatTime(event.end_date) }}</span>
                            </div>

                            <!-- Event Description -->
                            <p class="text-gray-600 text-sm leading-relaxed mb-6">
                                {{ truncateText(event.description) || 'No description available.' }}
                            </p>

                            <!-- Action Buttons -->
                            <div class="flex items-center justify-between">
                                <a :href="event?.link || '#'"
                                   :target="event?.link ? '_blank' : '_self'"
                                   :class="event?.link ? 'cursor-pointer' : 'cursor-not-allowed opacity-50'"
                                   class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                    <span>{{ event?.link ? 'Learn More' : 'No Link Available' }}</span>
                                    <svg v-if="event?.link" class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                    </svg>
                                </a>

                                <!-- Bookmark Button -->
                                <button class="p-2 text-gray-400 hover:text-red-500 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 rounded-lg">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Card Footer -->
                        <div class="px-6 py-3 bg-gray-50 border-t border-gray-100">
                            <div class="flex items-center justify-between text-xs text-gray-500">
                                <span>Created {{ event?.created_at ? new Date(event.created_at).toLocaleDateString() : 'Unknown' }}</span>
                                <div class="flex items-center space-x-1">
                                    <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                                    <span>Active</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Empty State (if no events) -->
                <div v-if="!props.events || props.events.length === 0" class="text-center py-16">
                    <div class="w-24 h-24 mx-auto mb-6 bg-gray-100 rounded-full flex items-center justify-center">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-medium text-gray-900 mb-2">No events found</h3>
                    <p class="text-gray-600">Check back later for exciting upcoming events!</p>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
