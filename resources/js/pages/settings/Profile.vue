<script setup lang="ts">
import { Form, Head, Link, usePage } from '@inertiajs/vue3';

import DeleteUser from '@/components/DeleteUser.vue';
import HeadingSmall from '@/components/HeadingSmall.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import { type BreadcrumbItem, type User } from '@/types';
import { CheckCircle as CheckCircleIcon, BadgeIcon, BadgeCheckIcon } from 'lucide-vue-next';
import { computed, onMounted, watch } from 'vue';
import Swal from 'sweetalert2'; // Add this import


const page = usePage();
interface Props {
    mustVerifyEmail: boolean;
    status?: string;
    flash: object;
}

const message = computed(() => page.props.flash.flashMessage);

defineProps<Props>();
// const message = "test"

// Watch for changes in the message
watch(
    message,
    (newMessage, oldMessage) => {
        // Only show if there's a new message and it's different from the previous one
        if (newMessage && newMessage !== oldMessage) {
            Swal.fire({
                text: newMessage, // Use the actual message text
                icon: 'error',
                toast: true,
                showConfirmButton: false,
                position: 'top-right',
                timer: 3500,
            });
        }
    },
    { immediate: false },
);

const year = new Date(Date.now()).getFullYear();
const license_pattern: string = 'PT' + year + '****';

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Profile settings',
        href: '/settings/profile',
    },
];


const user = page.props.auth.user as User;
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">
        <Head title="Profile settings" />



        <SettingsLayout>
            <div class="flex flex-col space-y-6">
                <HeadingSmall title="Profile information" description="Update your name and email address">
                    <CheckCircleIcon class="h-5 w-5 text-red-800" />text-gray-800
                </HeadingSmall>

                <Form method="patch" :action="route('profile.update')" class="space-y-6" v-slot="{ errors, processing, recentlySuccessful }">
                    <div class="grid gap-2">
                        <Label for="name">Name</Label>
                        <Input
                            :disabled="user.licence_status === 'active'"
                            id="name"
                            class="mt-1 block w-full"
                            name="name"
                            :default-value="user.name"
                            required
                            autocomplete="name"
                            placeholder="Full Name"
                        />
                        <InputError class="mt-2" :message="errors.name" />
                    </div>
                    <section v-if="user.roles[0].name === 'job-seeker'">
                        <div class="">
                            <label for="speciality" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Speciality *</label>
                            <select

                                name="speciality"
                                id="speciality"
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2 text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:focus:border-blue-400 dark:focus:ring-blue-400"
                                :required="!user.licence_status"
                            >
                                <option value="">I am a</option>
                                <option value="clinician">Clinician <b>(COC)</b></option>
                                <option value="pharmacist">Pharmacist <b>(PBB)</b></option>
                                <option value="pharm_tech">Pharmtech <b>(PBB)</b></option>
                                <option value="nurse">Nurse <b>(NCK)</b></option>
<!--                                <option value="doctor">Doctor</option>-->
<!--                                <option value="dentist">Dentist</option>-->
                                <option value="lab_technician">Medical Lab technician</option>
                            </select>

<!--                            B01389-->
                        </div>

                        <div class="mt-6 grid gap-2">
                            <div class="relative">
                                <Label for="licence">Licence</Label>
                                <Input
                                    :disabled="user.licence_number != ''"
                                    id="licence"
                                    class="mt-1 block w-full pr-10"
                                    name="licence"
                                    :default-value=user.licence_number
                                    autocomplete="licence"
                                    :placeholder="license_pattern"
                                />
                                <span>{{ page.props.errors.licence }}</span>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                    <BadgeCheckIcon v-if="user.licence_number" class="h-4 w-4 text-green-500" />
                                    <BadgeIcon v-else class="h-4 w-4 text-red-500" />
                                </div>

                                <small>This will increase your trust with the employers</small>
                            </div>


                            <small class="ml-2 font-medium text-red-300">
                                {{ $page.props.flash.flashMessage }}
                            </small>
                        </div>
                    </section>
                    <div class="grid gap-2">
                        <Label for="email">Email address</Label>
                        <Input
                            id="email"
                            type="email"
                            class="mt-1 block w-full"
                            name="email"
                            :default-value="user.email"
                            required
                            autocomplete="username"
                            placeholder="Email address"
                        />
                        <InputError class="mt-2" :message="errors.email" />
                    </div>

                    <div v-if="mustVerifyEmail && !user.email_verified_at">
                        <p class="-mt-4 text-sm text-muted-foreground">
                            Your email address is unverified.
                            <Link
                                :href="route('verification.send')"
                                method="post"
                                as="button"
                                class="text-foreground underline decoration-neutral-300 underline-offset-4 transition-colors duration-300 ease-out hover:decoration-current! dark:decoration-neutral-500"
                            >
                                Click here to resend the verification email.
                            </Link>
                        </p>

                        <div v-if="status === 'verification-link-sent'" class="mt-2 text-sm font-medium text-green-600">
                            A new verification link has been sent to your email address.
                        </div>
                    </div>

                    <div class="flex items-center gap-4">
                        <Button :disabled="processing">Save</Button>

                        <Transition
                            enter-active-class="transition ease-in-out"
                            enter-from-class="opacity-0"
                            leave-active-class="transition ease-in-out"
                            leave-to-class="opacity-0"
                        >
                            <p v-show="recentlySuccessful" class="text-sm text-neutral-600">Saved.</p>
                        </Transition>
                    </div>
                </Form>
            </div>

            <DeleteUser />
        </SettingsLayout>
    </AppLayout>
</template>
