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
    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50 flex items-center justify-center p-4">
        <Head title="Register" />

        <div class="w-full max-w-lg">
            <!-- Header Section -->
            <div class="text-center mb-8">
                <div class="flex justify-center items-center space-x-3 mb-6">
                    <div class="w-12 h-12 bg-gradient-to-r from-blue-600 to-purple-600 rounded-xl flex items-center justify-center">
                        <span class="text-white font-bold text-2xl">M</span>
                    </div>
                    <span class="font-bold text-2xl bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">MediJobs</span>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Join MediJobs</h1>
                <p class="text-gray-600">Create your medical professional profile and discover your next career opportunity</p>
            </div>

            <!-- Registration Form Card -->
            <div class="bg-white/80 backdrop-blur-lg rounded-2xl shadow-xl border border-white/20 p-8">
                <Form
                    method="post"
                    :action="route('register')"
                    :reset-on-success="['password', 'password_confirmation']"
                    v-slot="{ errors, processing }"
                    class="space-y-6"
                >
                    <div class="space-y-5">
                        <!-- Name Field -->
                        <div class="space-y-2">
                            <Label for="name" class="text-sm font-semibold text-gray-700">Full Name</Label>
                            <div class="relative">
                                <Input
                                    id="name"
                                    type="text"
                                    required
                                    autofocus
                                    :tabindex="1"
                                    autocomplete="name"
                                    name="name"
                                    placeholder="Dr. John Doe"
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all duration-200 bg-white/50 backdrop-blur-sm"
                                />
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                            </div>
                            <InputError :message="errors.name" />
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
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all duration-200 bg-white/50 backdrop-blur-sm"
                                />
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                                    </svg>
                                </div>
                            </div>
                            <InputError :message="errors.email" />
                        </div>

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
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all duration-200 bg-white/50 backdrop-blur-sm"
                                />
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
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
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all duration-200 bg-white/50 backdrop-blur-sm"
                                />
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                </div>
                            </div>
                            <InputError :message="errors.password_confirmation" />
                        </div>

                        <!-- Terms and Privacy -->
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <p class="text-sm text-blue-800">
                                By creating an account, you agree to our
                                <a href="#" class="font-semibold hover:underline">Terms of Service</a>
                                and
                                <a href="#" class="font-semibold hover:underline">Privacy Policy</a>.
                                We protect your medical professional information with industry-standard security.
                            </p>
                        </div>

                        <!-- Create Account Button -->
                        <Button
                            type="submit"
                            class="w-full py-4 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none text-lg mt-2"
                            tabindex="5"
                            :disabled="processing"
                        >
                            <LoaderCircle v-if="processing" class="w-5 h-5 animate-spin mr-2" />
                            <span v-if="!processing">Create Account</span>
                            <span v-else>Creating Account...</span>
                        </Button>
                    </div>

                    <!-- Login Link -->
                    <div class="text-center pt-4 border-t border-gray-200">
                        <p class="text-gray-600">
                            Already have an account?
                            <TextLink :href="route('login')" class="text-blue-600 hover:text-blue-700 font-semibold hover:underline transition-colors ml-1" :tabindex="6">
                                Sign in to your account
                            </TextLink>
                        </p>
                    </div>
                </Form>
            </div>

            <!-- Additional Links -->
            <div class="text-center mt-8 space-y-3">
                <div class="flex items-center justify-center space-x-4 text-sm text-gray-500">
                    <a href="#" class="hover:text-blue-600 transition-colors">Privacy Policy</a>
                    <span>•</span>
                    <a href="#" class="hover:text-blue-600 transition-colors">Terms of Service</a>
                    <span>•</span>
                    <a href="#" class="hover:text-blue-600 transition-colors">Help</a>
                </div>
                <p class="text-xs text-gray-400">© 2025 MediJobs. Empowering medical careers worldwide.</p>
            </div>
        </div>
    </div>
</template>
