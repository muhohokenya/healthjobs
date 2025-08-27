<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { useAuth } from '@/utils/auth';
import { Plus, MapPin, Mail, Phone, FileText, Calendar, Eye, Building2, BadgeMinus, AlertTriangle, BadgeCheck } from 'lucide-vue-next';


const user = useAuth();

// eslint-disable-next-line @typescript-eslint/no-unused-vars
const props = defineProps({
    facilities: Object,
});

// Function to get license status
const getLicenseStatus = (expiryDate: string) => {
    if (!expiryDate) return 'expired';

    const today = new Date();
    const expiry = new Date(expiryDate);
    const daysDifference = Math.ceil((expiry.getTime() - today.getTime()) / (1000 * 3600 * 24));

    if (daysDifference < 0) {
        return 'expired';
    } else if (daysDifference <= 30) {
        return 'expiring-soon';
    } else {
        return 'active';
    }
};

// Function to get badge configuration
const getBadgeConfig = (status: string) => {
    const configs = {
        active: {
            text: '',
            icon: BadgeCheck,
            classes: 'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300',
        },
        'expiring-soon': {
            text: 'Expires Soon',
            icon: AlertTriangle,
            classes: 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-300',
        },
        expired: {
            text: 'Expired',
            icon: BadgeMinus,
            classes: 'bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300',
        },
    };

    return configs[status] || configs.expired;
};
</script>

<template>
    <AppLayout>
        <Head title="Index" />
        <div class="min-h-screen bg-gray-50 py-8 dark:bg-gray-900">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="mb-8">
                    <div class="mb-2 flex items-center gap-3">
                        <Building2 class="h-8 w-8 text-blue-600" />
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Manage Facilities</h1>
                    </div>
                    <p class="mt-2 text-gray-600 dark:text-gray-300">View and manage facilities</p>
                </div>

                <!-- Create Facility Button (Conditional) -->
                <div class="mb-6 flex justify-end">
                    <Link
                        v-if="user.hasPermission('create-facility')"
                        class="flex items-center gap-2 rounded-md bg-blue-600 px-4 py-2.5 font-medium text-white transition hover:bg-blue-700 focus:ring-2 focus:ring-blue-400 focus:outline-none"
                        :href="route('facilities.create')"
                    >
                        <Plus class="h-5 w-5" />
                        Create Facility
                    </Link>
                </div>

                <!-- Facilities Grid -->
                <div class="mb-8 grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                    <div
                        v-for="facility in facilities"
                        :key="facility.id"
                        class="rounded-lg border border-transparent bg-white p-6 shadow transition-all hover:shadow-lg dark:border-gray-700 dark:bg-gray-800 dark:shadow-gray-700/25 dark:hover:shadow-gray-700/40"
                    >
                        <div class="mb-3 flex items-start justify-between">
                            <div class="flex items-center gap-2">
                                <Building2 class="h-5 w-5 text-blue-600" />
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ facility.name }}</h3>
                            </div>

                            <!-- License Status Badge -->
                            <div class="flex flex-col gap-1">
                                <span
                                    :class="[
                                        'inline-flex items-center gap-1 rounded-full px-2.5 py-1 text-xs font-medium',
                                        getBadgeConfig(getLicenseStatus(facility.licence_expiry_date)).classes,
                                    ]"
                                >
                                    <component :is="getBadgeConfig(getLicenseStatus(facility.licence_expiry_date)).icon" class="h-3 w-3" />
                                    {{ getBadgeConfig(getLicenseStatus(facility.licence_expiry_date)).text }}
                                </span>
                            </div>
                        </div>

                        <div class="mb-4 space-y-2">
                            <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-300">
                                <MapPin class="h-4 w-4 text-gray-400" />
                                {{ facility.location }}
                            </div>
                            <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-300">
                                <Mail class="h-4 w-4 text-gray-400" />
                                {{ facility.email }}
                            </div>
                            <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-300">
                                <Phone class="h-4 w-4 text-gray-400" />
                                {{ facility.contact_number }}
                            </div>
                            <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-300">
                                <FileText class="h-4 w-4 text-gray-400" />
                                License: {{ facility.licence_number }}
                            </div>
                            <div
                                class="flex items-center gap-2 text-sm"
                                :class="{
                                    'text-green-600 dark:text-green-400': getLicenseStatus(facility.licence_expiry_date) === 'active',
                                    'text-yellow-600 dark:text-yellow-400': getLicenseStatus(facility.licence_expiry_date) === 'expiring-soon',
                                    'text-red-600 dark:text-red-400': getLicenseStatus(facility.licence_expiry_date) === 'expired',
                                }"
                            >
                                <Calendar class="h-4 w-4" />
                                Expires: {{ facility.licence_expiry_date }}
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <Link
                                class="flex items-center gap-2 rounded-md bg-blue-600 px-4 py-2 text-sm text-white transition-colors hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:bg-blue-500 dark:hover:bg-blue-600 dark:focus:ring-offset-gray-800"
                            >
                                <Eye class="h-4 w-4" />
                                View Details
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
