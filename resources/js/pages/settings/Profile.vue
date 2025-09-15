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
import { computed, ref, watch } from 'vue';
import Swal from 'sweetalert2';
import { QuillEditor } from '@vueup/vue-quill'; // Add this import
import '@vueup/vue-quill/dist/vue-quill.snow.css';




const page = usePage();
// interface Props {
//     mustVerifyEmail: boolean;
//     status?: string;
//     flash: object;
//     isProfileComplete: boolean;
// }

const message = computed(() => page.props.flash.flashMessage);


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

// Create a reactive reference for the description
const description = ref(user.description || '');

import SlimCropper from './../../lib/slim/SlimCropper.vue'; // Adjust the path to where SlimCropper.vue is stored

// Define the options for the cropper
const cropperOptions = ref({
    initialImage: '',  // Example image
    ratio: '1:1', // Correct format for ratio
    size: '640,640',
    edit: false,

});

</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">
        <Head title="Profile settings" />

        <SettingsLayout>
            <div v-if="!page.props.isProfileComplete" class="mb-6 rounded-md bg-red-100 p-4 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                ⚠️ Please finish setting up your profile build more trust with other practiotioners
            </div>
            <div class="flex flex-col space-y-6">
                <HeadingSmall title="Profile information" description="Update your profile">
                    <CheckCircleIcon class="h-5 w-5 text-red-800" />text-gray-800
                </HeadingSmall>




                <Form method="patch" :action="route('profile.update')" class="space-y-6" v-slot="{ errors, processing, recentlySuccessful }">


                    <!-- Replace your existing slim-cropper section with this styled version -->

                    <!-- Profile Picture Upload Section -->
                    <div class="mb-6">
                        <Label class="mb-3 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Profile Picture
                        </Label>

                        <!-- Container for the cropper with responsive sizing -->
                        <div class="flex justify-start">
                            <div class="relative w-48 h-48 sm:w-56 sm:h-56 md:w-64 md:h-64 lg:w-72 lg:h-72">
                                <slim-cropper
                                    :ratio="cropperOptions.ratio"
                                    :size="cropperOptions.size"
                                    :edit="cropperOptions.edit"
                                    :initial-image="user.avatar"
                                    max-file-size="5"
                                    class="w-full h-full"
                                />
                                <!-- Helper text -->
                                <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                    Recommended: Square image, at least 200x200px. Max file size: 5MB.
                                </p>
                            </div>
                        </div>

                    </div>


                    <div class="grid gap-2">
                        <Label for="name">Name</Label>
                        <Input
                            :disabled="user.licence_status === 'active'"
                            id="name"
                            class="mt-1 block w-full py-2"
                            name="name"
                            :default-value="user.name"
                            required
                            autocomplete="name"
                            :placeholder="user.roles[0].name === 'job-seeker' ? 'Full Name' : 'Full Facility Name'"
                        />
                        <InputError class="mt-2" :message="errors.name" />
                    </div>

                    <section v-if="user.roles[0].name === 'job-seeker'">
                        <div class="">
                            <label for="profession" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Speciality *</label>
                            <select
                                name="profession"
                                id="profession"
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2 text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:focus:border-blue-400 dark:focus:ring-blue-400"
                                :required="!user.licence_status"
                            >
                                <option value="">I am a</option>
                                <option value="clinician" :selected="user.profession === 'clinician'">
                                    Clinician (COC)
                                </option>
                                <option value="pharmacist" :selected="user.profession === 'pharmacist'">
                                    Pharmacist (PBB)
                                </option>
                                <option value="pharm_tech" :selected="user.profession === 'pharm_tech'">
                                    Pharmtech (PBB)
                                </option>
                                <option value="nurse" :selected="user.profession === 'nurse'">
                                    Nurse (NCK)
                                </option>
                                <option value="lab_technician" :selected="user.profession === 'lab_technician'">
                                    Medical Lab technician
                                </option>
                            </select>
                            <InputError class="mt-2" :message="errors.profession" />

                            <!--                            B01389-->
                        </div>

                        <div class="mt-6 grid gap-2">
                            <div class="relative">
                                <Label for="licence">Licence</Label>
                                <Input
                                    :disabled="user.licence_number != null"
                                    id="licence"
                                    class="mt-1 block w-full"
                                    name="licence"
                                    :default-value="user.licence_number"
                                    autocomplete="licence"
                                    :placeholder="license_pattern"
                                />
                                <InputError class="mt-2" :message="errors.licence_number" />
                                <span>{{ page.props.errors.licence }}</span>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-4">
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
                    <section v-if="user.roles[0].name === 'recruiter'">
                        <!-- Licence -->
                        <div>
                            <Label for="name" class="text-sm font-semibold text-gray-700">Facility License</Label>

                            <Input
                                :disabled="user.licence_status === 'active'"
                                id="licence_number"
                                class="mt-1 block w-full py-2"
                                name="licence"
                                :default-value="user.licence_number"
                                required
                                autocomplete="name"
                                placeholder="PBXXYYY000"
                            />
                            <InputError class="mt-2" :message="errors.licence_number" />
                        </div>
                    </section>

                    <!-- Description -->
                    <div>
                        <label for="about" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            About me <small class="text-red-700">*</small>
                        </label>
                        <QuillEditor
                            content-type="html"
                            v-model:content="description"
                            placeholder="Brief professional summary that highlights your strengths, experience, and unique value."
                            theme="snow"
                            toolbar="full"
                            class="rounded-lg bg-white dark:bg-gray-700"
                        />
                        <!-- Hidden input to submit the description with the form -->
                        <input type="hidden" name="description" :value="description" />
                    </div>

                    <!-- contact number -->
                    <div class="mt-6 grid gap-2">
                        <Label for="name" class="text-sm font-semibold text-gray-700">Contact *</Label>

                        <Input
                            :default-value="user.contacts"
                            name="contacts"
                            id="contacts"
                            placeholder="07xxx"
                            autoComplete="off"
                            class="mt-1 block w-full py-2"
                        />
                        <InputError class="mt-2" :message="errors.contacts" />
                    </div>
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

<!--                    {{$props}}-->

<!--                    <div v-if="mustVerifyEmail && !user.email_verified_at">-->
<!--                        <p class="-mt-4 text-sm text-muted-foreground">-->
<!--                            Your email address is unverified.-->
<!--                            <Link-->
<!--                                :href="route('verification.send')"-->
<!--                                method="post"-->
<!--                                as="button"-->
<!--                                class="text-foreground underline decoration-neutral-300 underline-offset-4 transition-colors duration-300 ease-out hover:decoration-current! dark:decoration-neutral-500"-->
<!--                            >-->
<!--                                Click here to resend the verification email.-->
<!--                            </Link>-->
<!--                        </p>-->

<!--                        <div v-if="status === 'verification-link-sent'" class="mt-2 text-sm font-medium text-green-600">-->
<!--                            A new verification link has been sent to your email address.-->
<!--                        </div>-->
<!--                    </div>-->

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
<style scoped>
:deep(.ql-editor) {
    min-height: 120px;
    font-size: 14px;
}

:deep(.ql-toolbar) {
    border-top-left-radius: 0.5rem;
    border-top-right-radius: 0.5rem;
}

:deep(.ql-container) {
    border-bottom-left-radius: 0.5rem;
    border-bottom-right-radius: 0.5rem;
}

/* Style for QuillEditor with errors */
:deep(.ql-container.ql-snow) {
    border-color: inherit;
}
</style>
