<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';

const props = defineProps({
    profiles: Object,
    locations: Object,
    filters: Object,
    isProfileComplete: Boolean,
});

// Helper function to get initials for placeholder
const getInitials = (name: string) => {
    if (!name || name.trim() === '') {
        return 'MP'; // Default: Medical Professional
    }
    return name
        .trim()
        .split(' ')
        .map(word => word.charAt(0))
        .join('')
        .toUpperCase()
        .slice(0, 2);
};

// Helper function to format profession
const formatProfession = (profession: string) => {
    if (!profession || profession.trim() === '') {
        return 'Healthcare Provider';
    }
    return profession.charAt(0).toUpperCase() + profession.slice(1);
};

// Helper function to strip HTML tags from description
const stripHtml = (html: string) => {
    if (!html) return '';
    const tmp = document.createElement('div');
    tmp.innerHTML = html;
    return tmp.textContent || tmp.innerText || '';
};

// Helper function to truncate text
const truncateText = (text: string, maxLength: number) => {
    if (!text || text.length <= maxLength) return text;
    return text.slice(0, maxLength) + '...';
};

// Helper function to get license status color
const getLicenseStatusColor = (status: string) => {
    switch (status) {
        case 'active':
            return 'bg-green-100 text-green-800 border-green-200 dark:bg-green-900/30 dark:text-green-300 dark:border-green-800/50';
        case 'inactive':
            return 'bg-red-100 text-red-800 border-red-200 dark:bg-red-900/30 dark:text-red-300 dark:border-red-800/50';
        default:
            return 'bg-gray-100 text-gray-800 border-gray-200 dark:bg-gray-900/30 dark:text-gray-300 dark:border-gray-800/50';
    }
};

// Helper function to format license status
const formatLicenseStatus = (status: string) => {
    return status === 'active' ? 'Licensed' : 'Unlicensed';
};
</script>

<template>
    <AppLayout>
        <Head title="Medical Professionals" />

        <div class="min-h-screen bg-gray-50 py-8 dark:bg-gray-900">
            <div class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Header Section -->
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Medical Professionals</h1>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">Connect with qualified healthcare professionals</p>
                </div>

                <!-- Profiles Grid -->
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                    <div
                        v-for="profile in props.profiles"
                        :key="profile.id"
                        class="group relative flex flex-col overflow-hidden rounded-xl border border-gray-200 bg-white shadow-md transition-all duration-300 hover:-translate-y-2 hover:shadow-xl hover:shadow-blue-500/20 dark:border-gray-700 dark:bg-gray-800"
                    >
                        <!-- Header with gradient background -->
                        <div class="relative bg-gradient-to-br from-blue-500 to-purple-600 px-6 py-8">
                            <!-- License Status Badge -->
                            <div class="absolute top-4 right-4">
                        <span
                            :class="[
                                'inline-flex items-center rounded-full border px-2.5 py-1 text-xs font-medium',
                                getLicenseStatusColor(profile.licence_status)
                            ]"
                        >
                            <div
                                :class="[
                                    'mr-1.5 h-2 w-2 rounded-full',
                                    profile.licence_status === 'active' ? 'bg-green-500' : 'bg-red-500'
                                ]"
                            ></div>
                            {{ formatLicenseStatus(profile.licence_status) }}
                        </span>
                            </div>

                            <!-- Profile Picture -->
                            <div class="flex justify-center">
                                <div class="relative">
                                    <!-- Profile Image or Placeholder -->
                                    <div v-if="profile.avatar" class="h-24 w-24 overflow-hidden rounded-full border-4 border-white shadow-lg">
                                        <img
                                            :src="profile.avatar"
                                            :alt="profile.name || 'Medical Professional'"
                                            class="h-full w-full object-cover"
                                            @error="$event.target.style.display = 'none'; $event.target.nextElementSibling.style.display = 'flex'"
                                        />
                                        <!-- Fallback placeholder -->
                                        <div class="hidden h-full w-full items-center justify-center bg-gradient-to-br from-blue-400 to-purple-500 text-white">
                                            <span class="text-lg font-bold">{{ getInitials(profile.name) }}</span>
                                        </div>
                                    </div>

                                    <!-- Default Placeholder -->
                                    <div v-else class="flex h-24 w-24 items-center justify-center rounded-full border-4 border-white bg-gradient-to-br from-blue-400 to-purple-500 text-white shadow-lg">
                                        <span class="text-lg font-bold">{{ getInitials(profile.name) }}</span>
                                    </div>

                                    <!-- Online indicator (optional - you can remove if not needed) -->
                                    <div class="absolute bottom-1 right-1 h-6 w-6 rounded-full border-2 border-white bg-green-500"></div>
                                </div>
                            </div>

                            <!-- Name and Profession -->
                            <div class="mt-4 text-center text-white">
                                <h3 class="text-xl font-bold">{{ profile.name || 'Medical Professional' }}</h3>
                                <p class="text-blue-100">{{ formatProfession(profile.profession || 'Healthcare Provider') }}</p>
                            </div>
                        </div>

                        <!-- Content Section -->
                        <div class="flex-1 p-6">
                            <!-- Email -->
                            <div class="mb-4 flex items-center text-sm text-gray-600 dark:text-gray-400">
                                <svg class="mr-2 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                                </svg>
                                <span class="truncate">{{ profile.email }}</span>
                            </div>

                            <!-- Description -->
                            <div v-if="profile.description" class="mb-4">
                                <h4 class="mb-2 text-sm font-semibold text-gray-800 dark:text-gray-200">About:</h4>
                                <p class="text-sm leading-relaxed text-gray-600 dark:text-gray-400">
                                    {{ truncateText(stripHtml(profile.description), 150) }}
                                </p>
                            </div>

                            <!-- No description placeholder -->
                            <div v-else class="mb-4">
                                <p class="text-sm italic text-gray-500 dark:text-gray-500">
                                    Professional profile description not available.
                                </p>
                            </div>

                            <!-- License Information -->
                            <div v-if="profile.licence_number" class="mb-4 rounded-lg bg-gray-50 p-3 dark:bg-gray-700">
                                <div class="flex items-center text-xs text-gray-600 dark:text-gray-400">
                                    <svg class="mr-1 h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V4a2 2 0 00-2-2H6zm1 2a1 1 0 000 2h6a1 1 0 100-2H7zm6 7a1 1 0 011 1v3a1 1 0 11-2 0v-3a1 1 0 011-1zm-3 3a1 1 0 100 2h.01a1 1 0 100-2H10z" clip-rule="evenodd" />
                                    </svg>
                                    <span>License: {{ profile.licence_number }}</span>
                                </div>
                                <div v-if="profile.licence_number_expiry" class="mt-1 text-xs text-gray-500 dark:text-gray-500">
                                    Expires: {{ new Date(profile.licence_number_expiry).toLocaleDateString() }}
                                </div>
                            </div>
                        </div>

                        <!-- Footer Section -->
                        <div class="border-t border-gray-100 p-6 pt-4 dark:border-gray-700">
                            <div class="flex items-center justify-between">
                                <!-- View Profile Button -->
                                <Link
                                    :href="`/profiles/${profile.id}`"
                                    class="inline-flex items-center justify-center rounded-lg bg-gradient-to-r from-blue-600 to-purple-600 px-4 py-2.5 text-sm font-semibold text-white transition-all duration-200 hover:from-blue-700 hover:to-purple-700 hover:shadow-lg focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                                >
                                    <span>View Profile</span>
                                    <svg
                                        class="ml-2 h-4 w-4 transition-transform group-hover:translate-x-1"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </Link>

                                <!-- Contact Button -->
                                <button
                                    class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 transition-all duration-200 hover:border-blue-600 hover:bg-blue-50 hover:text-blue-600 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:border-blue-600 dark:hover:bg-gray-700 dark:hover:text-blue-400"
                                >
                                    <svg class="mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                    </svg>
                                    Contact
                                </button>
                            </div>

                            <!-- Member since -->
                            <div class="mt-3 text-center">
                        <span class="text-xs text-gray-500 dark:text-gray-500">
                            Member since {{ new Date(profile.created_at).toLocaleDateString('en-US', { month: 'short', year: 'numeric' }) }}
                        </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Empty State -->
                <div v-if="!props.profiles || props.profiles.length === 0" class="py-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No profiles found</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        No medical professionals have registered yet.
                    </p>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
