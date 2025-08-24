<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';
import { Form } from '@inertiajs/vue3';
import { usePage } from '@inertiajs/vue3';
import { Label } from '@/components/ui/label';

const page = usePage();

// eslint-disable-next-line @typescript-eslint/no-unused-vars
const props = defineProps({
    flash: Object,
});

// Counties data
const counties = [
    { "name": "Baringo", "capital": "Kabarnet", "code": 30 },
    { "name": "Bomet", "capital": "Bomet", "code": 36 },
    { "name": "Bungoma", "capital": "Bungoma", "code": 39 },
    { "name": "Busia", "capital": "Busia", "code": 40 },
    { "name": "Elgeyo-Marakwet", "capital": "Iten", "code": 28 },
    { "name": "Embu", "capital": "Embu", "code": 14 },
    { "name": "Garissa", "capital": "Garissa", "code": 7 },
    { "name": "Homa Bay", "capital": "Homa Bay", "code": 43 },
    { "name": "Isiolo", "capital": "Isiolo", "code": 11 },
    { "name": "Kajiado", "code": 34 },
    { "name": "Kakamega", "capital": "Kakamega", "code": 37 },
    { "name": "Kericho", "capital": "Kericho", "code": 35 },
    { "name": "Kiambu", "capital": "Kiambu", "code": 22 },
    { "name": "Kilifi", "capital": "Kilifi", "code": 3 },
    { "name": "Kirinyaga", "capital": "Kerugoya/Kutus", "code": 20 },
    { "name": "Kisii", "capital": "Kisii", "code": 45 },
    { "name": "Kisumu", "capital": "Kisumu", "code": 42 },
    { "name": "Kitui", "capital": "Kitui", "code": 15 },
    { "name": "Kwale", "capital": "Kwale", "code": 2 },
    { "name": "Laikipia", "capital": "Rumuruti", "code": 31 },
    { "name": "Lamu", "capital": "Lamu", "code": 5 },
    { "name": "Machakos", "capital": "Machakos", "code": 16 },
    { "name": "Makueni", "capital": "Wote", "code": 17 },
    { "name": "Mandera", "capital": "Mandera", "code": 9 },
    { "name": "Marsabit", "capital": "Marsabit", "code": 10 },
    { "name": "Meru", "capital": "Meru", "code": 12 },
    { "name": "Migori", "capital": "Migori", "code": 44 },
    { "name": "Mombasa", "capital": "Mombasa City", "code": 1 },
    { "name": "Murang'a", "capital": "Murang'a", "code": 21 },
    { "name": "Nairobi", "capital": "Nairobi City", "code": 47 },
    { "name": "Nakuru", "capital": "Nakuru", "code": 32 },
    { "name": "Nandi", "capital": "Kapsabet", "code": 29 },
    { "name": "Narok", "capital": "Narok", "code": 33 },
    { "name": "Nyamira", "capital": "Nyamira", "code": 46 },
    { "name": "Nyandarua", "capital": "Ol Kalou", "code": 18 },
    { "name": "Nyeri", "capital": "Nyeri", "code": 19 },
    { "name": "Samburu", "capital": "Maralal", "code": 25 },
    { "name": "Siaya", "capital": "Siaya", "code": 41 },
    { "name": "Taita-Taveta", "capital": "Voi", "code": 6 },
    { "name": "Tana River", "capital": "Hola", "code": 4 },
    { "name": "Tharaka-Nithi", "capital": "Chuka", "code": 13 },
    { "name": "Trans-Nzoia", "capital": "Kitale", "code": 26 },
    { "name": "Turkana", "capital": "Lodwar", "code": 23 },
    { "name": "Uasin Gishu", "capital": "Eldoret", "code": 27 },
    { "name": "Vihiga", "capital": "Vihiga", "code": 38 },
    { "name": "Wajir", "capital": "Wajir", "code": 8 },
    { "name": "West Pokot", "capital": "Kapenguria", "code": 24 }
];

// const verification = computed(() => page.props.verification);
const verificationSuccess = ref(null);
const verificationMessage = ref(null);
onMounted(() => {
    const url = new URL(page.url, window.location.origin);
    console.log(page.props);
    const params = new URLSearchParams(url.search);

    verificationSuccess.value = params.get('verification[success]');
    verificationMessage.value = params.get('verification[message]');

    console.log('Verification success:', verificationSuccess.value);
    console.log('Verification message:', verificationMessage.value);
});

const form = useForm({
    // name: '',
    // email: '',
    // licence_number: '',
    // contact_number: '',
    // location: '',

    name: 'TOSHA PHARMACY',
    email: 'tosha@gmail.com',
    licence_number: 'BU202502358',
    contact_number: '0711898122',
    location: 'TRANS-NZOIA',
});
</script>

<template>
    <Head title="Index" />
    <AppLayout>

        <div class="min-h-screen bg-gray-50 py-8 dark:bg-gray-900">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Create New Facility</h1>
                    <p class="mt-2 text-gray-600 dark:text-gray-300">View and manage facilities</p>
                </div>

                <!-- Error Banner -->
                <div v-if="$page.props.flash.myVariable" class="mb-6">
                    <div
                        class="rounded-md border border-red-300 bg-red-50 p-4 text-red-800 shadow-sm dark:border-red-700 dark:bg-red-900 dark:text-red-200"
                    >
                        <div class="flex items-center">
                            <!-- Error Icon -->
                            <svg
                                class="h-5 w-5 text-red-500 dark:text-red-300"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.662 1.732-3L13.732 4c-.77-1.338-2.694-1.338-3.464 0L3.34 16c-.77 1.338.192 3 1.732 3z"
                                />
                            </svg>

                            <!-- Message -->
                            <span class="ml-2 font-medium">
                {{ $page.props.flash.myVariable }}
            </span>
                        </div>
                    </div>
                </div>


                <!-- Create Job Button (Conditional) -->
                <div class="mb-6 flex justify-end">
                    <Link
                        class="flex items-center rounded-md bg-blue-600 px-4 py-2.5 font-medium text-white transition hover:bg-blue-700 focus:ring-2 focus:ring-blue-400 focus:outline-none"
                        :href="route('facilities.index')"
                    >
                        Back
                    </Link>
                </div>

                <Form
                    action="/facilities/store"
                    method="post"
                    #default="{
                        errors,
                        hasErrors,
                        processing,
                        progress,
                        wasSuccessful,
                        recentlySuccessful,
                        setError,
                        clearErrors,
                        resetAndClearErrors,
                        defaults,
                        isDirty,
                        reset,
                        submit,
                    }"
                    resetOnSuccess
                    class="space-y-6 rounded-lg bg-white p-8 shadow-lg dark:bg-gray-800"
                >
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">

                        <!-- Licence -->
                        <div>
                            <Label for="name" class="text-sm font-semibold text-gray-700">PBB License</Label>
                            <!-- Medium - 32x32px -->
                            <div class="flex items-center gap-3">
                                <img
                                    src="https://practice.pharmacyboardkenya.org/assets/img/gok_logo.png"
                                    class="h-8 w-8 rounded-full object-cover"
                                />
                                <span class="text-sm">Enter the facilitie's Licence exactly as registered under <b>PHARMACY AND POISONS BOARD</b> </span>
                            </div>
                            <input

                                type="text"
                                name="licence_number"
                                id="licence_number"
                                placeholder="e.g. Name"
                                class="w-full mt-3 rounded-lg border border-gray-300 bg-white px-4 py-3 text-gray-900 placeholder-gray-500 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-400 dark:focus:ring-blue-400"
                            />
                            <div v-if="errors.licence_number" class="mt-1 text-sm text-red-500">{{ errors.licence_number }}</div>
                        </div>



                        <!-- email -->
                        <div class="mt-10">
                            <label for="email" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Contact Email *</label>
                            <input

                                type="text"
                                name="email"
                                id="email"
                                placeholder="e.g. john@gmail.com."
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-gray-900 placeholder-gray-500 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-400 dark:focus:ring-blue-400"
                            />
                            <div v-if="errors.email" class="mt-1 text-sm text-red-500">{{ errors.email }}</div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <!-- Name -->
                        <div>
                            <label for="name" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Facility Name *</label>
                            <input

                                type="text"
                                name="name"
                                id="name"
                                placeholder="e.g. Name"
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-gray-900 placeholder-gray-500 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-400 dark:focus:ring-blue-400"
                            />
                            <div v-if="errors.name" class="mt-1 text-sm text-red-500">{{ errors.name }}</div>
                        </div>

                        <!-- location -->
                        <div>
                            <label for="location" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Location *</label>
                            <select
                                name="location"
                                id="location"
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:focus:border-blue-400 dark:focus:ring-blue-400"
                            >
                                <option value="">Select County</option>
                                <option
                                    v-for="county in counties"
                                    :key="county.code"
                                    :value="county.name.toUpperCase()"
                                >
                                    {{ county.name.toUpperCase() }}
                                </option>
                            </select>
                            <div v-if="errors.location" class="mt-1 text-sm text-red-500">{{ errors.location }}</div>
                        </div>

                        <!-- contact number -->
                        <div>
                            <label for="contact_number" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300"
                            >Contact Number *</label
                            >
                            <input

                                type="text"
                                name="contact_number"
                                id="contact_number"
                                placeholder="e.g. 0711898122"
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-gray-900 placeholder-gray-500 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-400 dark:focus:ring-blue-400"
                            />
                            <div v-if="errors.contact_number" class="mt-1 text-sm text-red-500">{{ errors.contact_number }}</div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <button
                            type="submit"
                            :disabled="processing"
                            class="flex w-full items-center justify-center rounded-lg bg-blue-600 px-6 py-2 text-lg font-semibold text-white transition hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:outline-none disabled:opacity-50 dark:bg-blue-500 dark:hover:bg-blue-600 dark:focus:ring-blue-400 dark:focus:ring-offset-gray-800"
                        >
                            <svg
                                v-if="form.processing"
                                class="mr-2 h-5 w-5 animate-spin text-white"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                            >
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path
                                    class="opacity-75"
                                    fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                                ></path>
                            </svg>
                            {{ form.processing ? 'Creating Job...' : 'Create Facility' }}
                        </button>
                    </div>
                </Form>
            </div>
        </div>
    </AppLayout>
</template>
