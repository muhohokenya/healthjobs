<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Form, Head } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';
</script>

<template>
    <!-- Kenyan ribbon (subtle flag accent) -->
    <div class="fixed top-0 left-0 right-0 z-[60] h-1">
        <div class="h-full grid grid-cols-12">
            <div class="col-span-3 bg-black"></div>
            <div class="col-span-3 bg-white"></div>
            <div class="col-span-3 bg-red-600"></div>
            <div class="col-span-3 bg-green-600"></div>
        </div>
    </div>

    <div class="flex min-h-screen items-center justify-center bg-gradient-to-br from-emerald-50 via-white to-green-50 p-4 pt-8">
        <Head title="Register" />

        <div class="w-full max-w-2xl">
            <!-- Header Section -->
            <div class="mb-8 text-center">
                <div class="mb-6 flex items-center justify-center space-x-3">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center shadow-lg bg-gradient-to-br from-emerald-600 via-red-600 to-black">
                        <!-- Simple Kenya map mark -->
                        <svg class="w-7 h-7 text-white" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path
                                d="M9 2l4 2 3-1 1 3 3 2-2 3 2 3-3 2-1 3-3-1-4 2-1-3-4-2 2-3-2-3 4-2z"
                            />
                        </svg>
                    </div>

                    <TextLink
                    :href="route('home')"
                    class="ml-1 font-semibold text-red-600 transition-colors hover:text-emerald-700 hover:underline"
                    :tabindex="6"
                    >
                        <span class="font-extrabold text-2xl bg-gradient-to-r from-emerald-700 via-red-600 to-black bg-clip-text text-transparent">
                        MediCareers Kenya
                    </span>
                    </TextLink>


                </div>
                <h1 class="mb-2 text-3xl font-bold text-gray-900">Karibu MediCareers</h1>
                <p class="text-gray-600">Create your medical professional profile and discover opportunities across Kenya's 47 counties</p>
            </div>

            <!-- Registration Form Card -->
            <div class="rounded-2xl border border-gray-100 bg-white/90 p-8 shadow-xl backdrop-blur-lg relative overflow-hidden">
                <!-- Decorative circles in Kenyan palette -->
                <div class="absolute top-4 right-4 w-8 h-8 bg-emerald-200/30 rounded-full"></div>
                <div class="absolute top-12 left-6 w-6 h-6 bg-red-200/30 rounded-full"></div>
                <div class="absolute bottom-8 right-8 w-10 h-10 bg-black/10 rounded-full"></div>

                <Form
                    method="post"
                    :action="route('register')"
                    :reset-on-success="['password', 'password_confirmation']"
                    v-slot="{ errors, processing }"
                    class="space-y-6 relative z-10"
                >
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                            <!-- Role -->
                            <div class="space-y-2">
                                <label for="role" class="text-sm font-semibold text-gray-700">Interest in Kenya's Health Sector *</label>
                                <div class="relative">
                                    <select
                                        name="role"
                                        id="role"
                                        class="w-full h-12 rounded-lg border-2 border-gray-200 bg-white/80 px-4 py-3 text-gray-900 outline-none transition-all duration-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500 hover:border-emerald-300 backdrop-blur-sm"
                                        required
                                        :tabindex="1"
                                    >
                                        <option value="">I want to</option>
                                        <option value="job-seeker">Find or post medical jobs (Individual)</option>
                                        <option value="recruiter">Register as a faclity (Organisation)</option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </div>
                                </div>
                                <InputError :message="errors.role" />
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
                                        placeholder="doctor@example.co.ke"
                                        class="h-12 w-full rounded-lg border-2 border-gray-200 bg-white/80 px-4 py-3 backdrop-blur-sm outline-none transition-all duration-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500 hover:border-emerald-300"
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
                                        class="h-12 w-full rounded-lg border-2 border-gray-200 bg-white/80 px-4 py-3 backdrop-blur-sm transition-all duration-200 outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500 hover:border-emerald-300"
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
                                        class="h-12 w-full rounded-lg border-2 border-gray-200 bg-white/80 px-4 py-3 backdrop-blur-sm transition-all duration-200 outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500 hover:border-emerald-300"
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
                        <div class="rounded-lg border border-emerald-200 bg-emerald-50 p-4">
                            <p class="text-sm text-emerald-800">
                                By creating an account, you agree to our
                                <a href="#" class="font-semibold hover:underline">Terms of Service</a>
                                and
                                <a href="#" class="font-semibold hover:underline">Privacy Policy</a>. We protect your medical professional information
                                with industry-standard security and verify license status with Kenyan regulatory councils.
                            </p>
                        </div>

                        <!-- Create Account Button -->
                        <Button
                            type="submit"
                            class="mt-2 h-12 w-full rounded-lg bg-gradient-to-r from-emerald-600 to-green-700 py-4 text-lg font-semibold text-white shadow-lg transition-all duration-300 hover:from-emerald-700 hover:to-green-800 hover:shadow-xl transform hover:scale-105 disabled:transform-none disabled:cursor-not-allowed disabled:opacity-50"
                            tabindex="5"
                            :disabled="processing"
                        >
                            <LoaderCircle v-if="processing" class="mr-2 h-5 w-5 animate-spin" />
                            <span v-if="!processing">Join MediCareers Kenya</span>
                            <span v-else>Creating Account...</span>
                        </Button>
                    </div>

                    <!-- Login Link -->
                    <div class="border-t border-gray-200 pt-4 text-center">
                        <p class="text-gray-600">
                            Already have an account?
                            <TextLink
                                :href="route('login')"
                                class="ml-1 font-semibold text-red-600 transition-colors hover:text-emerald-700 hover:underline"
                                :tabindex="6"
                            >
                                Sign in to your dashboard
                            </TextLink>
                        </p>
                    </div>
                </Form>
            </div>

            <!-- Trust Indicators -->
            <div class="text-center mt-6">
                <div class="inline-flex items-center px-4 py-2 bg-white/80 backdrop-blur-sm rounded-full border border-gray-100 shadow-sm">
                    <div class="flex items-center space-x-4 text-xs text-gray-600">
                        <div class="flex items-center space-x-1">
                            <div class="w-2 h-2 bg-emerald-500 rounded-full"></div>
                            <span>License Verification</span>
                        </div>
                        <span class="text-gray-300">•</span>
                        <div class="flex items-center space-x-1">
                            <div class="w-2 h-2 bg-red-500 rounded-full"></div>
                            <span>47 Counties Coverage</span>
                        </div>
                        <span class="text-gray-300">•</span>
                        <div class="flex items-center space-x-1">
                            <div class="w-2 h-2 bg-black rounded-full"></div>
                            <span>KES Salary Transparency</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Links -->
            <div class="mt-8 space-y-3 text-center">
                <div class="flex items-center justify-center space-x-4 text-sm text-gray-500">
                    <a href="#" class="transition-colors hover:text-emerald-600">Privacy Policy</a>
                    <span>•</span>
                    <a href="#" class="transition-colors hover:text-emerald-600">Terms of Service</a>
                    <span>•</span>
                    <a href="#" class="transition-colors hover:text-emerald-600">Help</a>
                </div>
                <p class="text-xs text-gray-400">© 2025 MediCareers Kenya. Empowering medical careers across Kenya.</p>
            </div>
        </div>
    </div>

</template>
