<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';

const props = defineProps({
    profiles: Object,
    locations: Object,
    filters: Object,
    isProfileComplete: Boolean,
});

const maskedLicenseNumber = (licenseNumber: string) => {
    if (!licenseNumber) return '';
    return licenseNumber.slice(0, 3) + '****';
};

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
            return 'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-900/20 dark:text-emerald-300 dark:border-emerald-700/30';
        case 'inactive':
            return 'bg-rose-50 text-rose-700 border-rose-200 dark:bg-rose-900/20 dark:text-rose-300 dark:border-rose-700/30';
        default:
            return 'bg-slate-50 text-slate-700 border-slate-200 dark:bg-slate-900/20 dark:text-slate-300 dark:border-slate-700/30';
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

        <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50/30 to-indigo-50/20 py-8 dark:from-slate-900 dark:via-slate-900 dark:to-indigo-950/20">
            <div class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Header Section -->
                <div class="mb-8">
                    <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-blue-900 bg-clip-text text-transparent dark:from-slate-200 dark:to-blue-300">Medical Professionals</h1>
                    <p class="mt-2 text-slate-600 dark:text-slate-400">Connect with qualified healthcare professionals</p>
                </div>

                <!-- Profiles Grid -->
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                    <div
                        v-for="profile in props.profiles"
                        :key="profile.id"
                        class="group relative flex flex-col overflow-hidden rounded-2xl border border-white/60 bg-white/70 backdrop-blur-sm shadow-lg transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl hover:shadow-blue-500/10 dark:border-slate-700/50 dark:bg-slate-800/80"
                    >
                        <!-- Header with gradient background -->
                        <div class="relative bg-gradient-to-br from-blue-600 via-indigo-600 to-purple-700 px-6 py-8">
                            <!-- License Status Badge -->
                            <div class="absolute top-4 right-4">
                        <span
                            :class="[
                                'inline-flex items-center rounded-full border px-2.5 py-1 text-xs font-medium backdrop-blur-sm',
                                getLicenseStatusColor(profile.licence_status)
                            ]"
                        >
                            <div
                                :class="[
                                    'mr-1.5 h-2 w-2 rounded-full',
                                    profile.licence_status === 'active' ? 'bg-emerald-500' : 'bg-rose-500'
                                ]"
                            ></div>
                            {{ formatLicenseStatus(profile.licence_status) }}
                        </span>
                            </div>

                            <!-- Profile Picture -->
                            <div class="flex justify-center">
                                <div class="relative">
                                    <!-- Profile Image or Placeholder -->
                                    <div v-if="profile.avatar" class="h-24 w-24 overflow-hidden rounded-full border-4 border-white/90 shadow-xl ring-4 ring-white/20">
                                        <img
                                            :src="profile.avatar"
                                            :alt="profile.name || 'Medical Professional'"
                                            class="h-full w-full object-cover"
                                            @error="$event.target.style.display = 'none'; $event.target.nextElementSibling.style.display = 'flex'"
                                        />
                                        <!-- Fallback placeholder -->
                                        <div class="hidden h-full w-full items-center justify-center bg-gradient-to-br from-blue-400 via-indigo-500 to-purple-600 text-white">
                                            <span class="text-lg font-bold">{{ getInitials(profile.name) }}</span>
                                        </div>
                                    </div>

                                    <!-- Default Placeholder -->
                                    <div v-else class="flex h-24 w-24 items-center justify-center rounded-full border-4 border-white/90 bg-gradient-to-br from-blue-400 via-indigo-500 to-purple-600 text-white shadow-xl ring-4 ring-white/20">
                                        <span class="text-lg font-bold">{{ getInitials(profile.name) }}</span>
                                    </div>

                                    <!-- Online indicator -->
                                    <div class="absolute bottom-1 right-1 h-6 w-6 rounded-full border-2 border-white bg-emerald-500 shadow-lg"></div>
                                </div>
                            </div>

                            <!-- Name and Profession -->
                            <div class="mt-4 text-center text-white">
                                <h3 class="text-xl font-bold drop-shadow-sm">{{ profile.name || 'Medical Professional' }}</h3>
                                <p class="text-blue-100/90 drop-shadow-sm">{{ formatProfession(profile.profession || 'Healthcare Provider') }}</p>
                            </div>
                        </div>

                        <!-- Content Section -->
                        <div class="flex-1 p-6">
                            <!-- Email -->
                            <div class="mb-4 flex items-center text-sm text-slate-600 dark:text-slate-400">
                                <svg class="mr-2 h-4 w-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                                </svg>
                                <span class="truncate">{{ profile.email }}</span>
                            </div>

                            <!-- Description -->
                            <div v-if="profile.description" class="mb-4">
                                <h4 class="mb-2 text-sm font-semibold text-slate-800 dark:text-slate-200">About:</h4>
                                <p class="text-sm leading-relaxed text-slate-600 dark:text-slate-400">
                                    {{ truncateText(stripHtml(profile.description), 150) }}
                                </p>
                            </div>

                            <!-- No description placeholder -->
                            <div v-else class="mb-4">
                                <p class="text-sm italic text-slate-500 dark:text-slate-500">
                                    Professional profile description not available.
                                </p>
                            </div>

                            <!-- License Information -->
                            <div v-if="profile.licence_number" class="mb-4 rounded-xl bg-gradient-to-br from-slate-50 to-blue-50/50 p-3 border border-slate-100 dark:from-slate-700/50 dark:to-slate-800/50 dark:border-slate-600/50">
                                <div class="flex items-center text-xs text-slate-600 dark:text-slate-400">
                                    <svg class="mr-1 h-3 w-3 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V4a2 2 0 00-2-2H6zm1 2a1 1 0 000 2h6a1 1 0 100-2H7zm6 7a1 1 0 011 1v3a1 1 0 11-2 0v-3a1 1 0 011-1zm-3 3a1 1 0 100 2h.01a1 1 0 100-2H10z" clip-rule="evenodd" />
                                    </svg>
                                    <span>License: {{ maskedLicenseNumber(profile.licence_number) }}</span>
                                </div>
                                <div v-if="profile.licence_number_expiry" class="mt-1 text-xs text-slate-500 dark:text-slate-500">
                                    Expires: {{ new Date(profile.licence_number_expiry).toLocaleDateString() }}
                                </div>
                            </div>
                        </div>

                        <!-- Footer Section -->
                        <div class="border-t border-slate-100/80 bg-gradient-to-r from-slate-50/50 to-blue-50/30 p-6 pt-4 dark:border-slate-700/50 dark:from-slate-800/50 dark:to-slate-700/50">
                            <div class="flex items-center justify-between">
                                <!-- View Profile Button -->
                                <Link
                                    :href="`/profiles/${profile.id}`"
                                    class="inline-flex items-center justify-center rounded-xl bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 px-4 py-2.5 text-sm font-semibold text-white transition-all duration-200 hover:from-blue-700 hover:via-indigo-700 hover:to-purple-700 hover:shadow-lg hover:scale-105 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
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
                                    class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white/80 backdrop-blur-sm px-4 py-2.5 text-sm font-semibold text-slate-700 transition-all duration-200 hover:border-blue-300 hover:bg-blue-50/80 hover:text-blue-600 hover:scale-105 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:border-slate-600 dark:bg-slate-800/80 dark:text-slate-300 dark:hover:border-blue-500 dark:hover:bg-slate-700/80 dark:hover:text-blue-400"
                                >
                                    <svg class="mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                    </svg>
                                    Contact
                                </button>
                            </div>

                            <!-- Member since -->
                            <div class="mt-3 text-center">
                        <span class="text-xs text-slate-500 dark:text-slate-500">
                            Member since {{ new Date(profile.created_at).toLocaleDateString('en-US', { month: 'short', year: 'numeric' }) }}
                        </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Empty State -->
                <div v-if="!props.profiles || props.profiles.length === 0" class="py-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-slate-900 dark:text-white">No profiles found</h3>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                        No medical professionals have registered yet.
                    </p>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
