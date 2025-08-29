<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Form, Head, router } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';

const props = defineProps({
    errors: Object
})
</script>

<template>
    <div class="flex min-h-screen items-center justify-center bg-gradient-to-br from-blue-50 via-white to-purple-50 p-4">
        <Head title="Register" />

        <div class="w-full max-w-2xl">
            <!-- Header Section -->
            <div class="mb-8 text-center">
                <div class="mb-6 flex items-center justify-center space-x-3">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-r from-blue-600 to-purple-600">
                        <span class="text-2xl font-bold text-white">M</span>
                    </div>
                    <span class="bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-2xl font-bold text-transparent">MediJobs</span>
                </div>
                <h1 class="mb-2 text-3xl font-bold text-gray-900">Join MediJobs</h1>
                <p class="text-gray-600">Create your medical professional profile and discover your next career opportunity</p>
            </div>

            <!-- Registration Form Card -->
            <div class="rounded-2xl border border-white/20 bg-white/80 p-8 shadow-xl backdrop-blur-lg">
                <Form
                    method="post"
                    :action="route('register')"
                    :reset-on-success="['password', 'password_confirmation']"
                    v-slot="{ errors, processing }"
                    class="space-y-6"
                >
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                            <!-- Role -->
                            <div class="space-y-2">
                                <div>
                                    <label for="role" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Interest *</label>
                                    <select
                                        name="role"
                                        id="role"
                                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:focus:border-blue-400 dark:focus:ring-blue-400"
                                        required
                                    >
                                        <option value="">I want to</option>
                                        <option value="job-seeker">Search for medical jobs</option>
                                        <option value="recruiter">Post medical jobs</option>
                                    </select>
                                </div>
                            </div>


                            <!-- Email Field -->
                            <div class="space-y-2">
                                <Label for="email" class="text-sm font-semibold text-gray-700">Email Address</Label>
                                <div class="relative">
                                    <Input
                                        id="email"
                                        type="email"
                                        required
                                        :tabindex="2"
                                        autocomplete="email"
                                        name="email"
                                        placeholder="doctor@example.com"
                                        class="h-12 w-full rounded-lg border-2
                                        border-gray-200 bg-white/50 px-4 py-3 backdrop-blur-sm
                                        outline-none
                                         focus:border-blue-500 focus:ring-2 focus:ring-blue-500"
                                    />
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"
                                            ></path>
                                        </svg>
                                    </div>
                                </div>
                                <InputError :message="errors.email" />
                            </div>


                        </div>
                    </div>
                    <div class="space-y-6">
                        <!-- First Row: Name and Email -->
                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">



                        </div>

                        <!-- Second Row: Password and Confirm Password -->
                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                            <!-- Password Field -->
                            <div class="space-y-2">
                                <Label for="password" class="text-sm font-semibold text-gray-700">Password</Label>
                                <div class="relative">
                                    <Input
                                        id="password"
                                        type="password"
                                        required
                                        :tabindex="3"
                                        autocomplete="new-password"
                                        name="password"
                                        placeholder="Create a secure password"
                                        class="h-12 w-full rounded-lg border-2 border-gray-200 bg-white/50 px-4 py-3 backdrop-blur-sm transition-all duration-200 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500"
                                    />
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"
                                            ></path>
                                        </svg>
                                    </div>
                                </div>
                                <InputError :message="errors.password" />
                            </div>

                            <!-- Confirm Password Field -->
                            <div class="space-y-2">
                                <Label for="password_confirmation" class="text-sm font-semibold text-gray-700">Confirm Password</Label>
                                <div class="relative">
                                    <Input
                                        id="password_confirmation"
                                        type="password"
                                        required
                                        :tabindex="4"
                                        autocomplete="new-password"
                                        name="password_confirmation"
                                        placeholder="Confirm your password"
                                        class="h-12 w-full rounded-lg border-2 border-gray-200 bg-white/50 px-4 py-3 backdrop-blur-sm transition-all duration-200 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500"
                                    />
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"
                                            ></path>
                                        </svg>
                                    </div>
                                </div>
                                <InputError :message="errors.password_confirmation" />
                            </div>
                        </div>

                        <!-- Terms and Privacy -->
                        <div class="rounded-lg border border-blue-200 bg-blue-50 p-4">
                            <p class="text-sm text-blue-800">
                                By creating an account, you agree to our
                                <a href="#" class="font-semibold hover:underline">Terms of Service</a>
                                and
                                <a href="#" class="font-semibold hover:underline">Privacy Policy</a>. We protect your medical professional information
                                with industry-standard security.
                            </p>
                        </div>

                        <!-- Create Account Button -->
                        <Button
                            type="submit"
                            class="mt-2 h-12 w-full rounded-lg bg-gradient-to-r from-blue-600 to-purple-600 py-4 text-lg font-semibold text-white shadow-lg transition-all duration-200 hover:from-blue-700 hover:to-purple-700 hover:shadow-xl disabled:transform-none disabled:cursor-not-allowed disabled:opacity-50"
                            tabindex="5"
                            :disabled="processing"
                        >
                            <LoaderCircle v-if="processing" class="mr-2 h-5 w-5 animate-spin" />
                            <span v-if="!processing">Create Account</span>
                            <span v-else>Creating Account...</span>
                        </Button>
                    </div>

                    <!-- Login Link -->
                    <div class="border-t border-gray-200 pt-4 text-center">
                        <p class="text-gray-600">
                            Already have an account?
                            <TextLink
                                :href="route('login')"
                                class="ml-1 font-semibold text-blue-600 transition-colors hover:text-blue-700 hover:underline"
                                :tabindex="6"
                            >
                                Sign in to your account
                            </TextLink>
                        </p>
                    </div>
                </Form>
            </div>

            <!-- Additional Links -->
            <div class="mt-8 space-y-3 text-center">
                <div class="flex items-center justify-center space-x-4 text-sm text-gray-500">
                    <a href="#" class="transition-colors hover:text-blue-600">Privacy Policy</a>
                    <span>•</span>
                    <a href="#" class="transition-colors hover:text-blue-600">Terms of Service</a>
                    <span>•</span>
                    <a href="#" class="transition-colors hover:text-blue-600">Help</a>
                </div>
                <p class="text-xs text-gray-400">© 2025 MediJobs. Empowering medical careers worldwide.</p>
            </div>
        </div>
    </div>
</template>
