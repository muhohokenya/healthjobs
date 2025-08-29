<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Form, Head } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';

defineProps<{
    status?: string;
    canResetPassword: boolean;
}>();
</script>

<template>
    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50 flex items-center justify-center p-4">
        <Head title="Log in" />

        <div class="w-full max-w-md">
            <!-- Header Section -->
            <div class="text-center mb-8">
                <div class="flex justify-center items-center space-x-3 mb-6">
                    <div class="w-12 h-12 bg-gradient-to-r from-blue-600 to-purple-600 rounded-xl flex items-center justify-center">
                        <span class="text-white font-bold text-2xl">M</span>
                    </div>
                    <span class="font-bold text-2xl bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">MediJobs</span>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Welcome Back</h1>
                <p class="text-gray-600">Enter your credentials to access your medical career dashboard</p>
            </div>

            <!-- Login Form Card -->
            <div class="bg-white/80 backdrop-blur-lg rounded-2xl shadow-xl border border-white/20 p-8">
                <div v-if="status" class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg text-sm font-medium text-center text-green-700">
                    {{ status }}
                </div>

                <Form method="post" :action="route('login')" :reset-on-success="['password']" v-slot="{ errors, processing }" class="space-y-6">
                    <div class="space-y-6">
                        <!-- Email Field -->
                        <div class="space-y-2">
                            <Label for="email" class="text-sm font-semibold text-gray-700">Email Address</Label>
                            <div class="relative">
                                <Input
                                    id="email"
                                    type="email"
                                    name="email"
                                    required
                                    autofocus
                                    :tabindex="1"
                                    autocomplete="email"
                                    placeholder="doctor@example.com"
                                    class="w-full h-13 px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all duration-200 bg-white/50 backdrop-blur-sm"
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
                            <div class="flex items-center justify-between">
                                <Label for="password" class="text-sm font-semibold text-gray-700">Password</Label>
                                <TextLink v-if="canResetPassword" :href="route('password.request')" class="text-sm text-blue-600 hover:text-blue-700 font-medium hover:underline transition-colors" :tabindex="5">
                                    Forgot password?
                                </TextLink>
                            </div>
                            <div class="relative">
                                <Input
                                    id="password"
                                    type="password"
                                    name="password"
                                    required
                                    :tabindex="2"
                                    autocomplete="current-password"
                                    placeholder="Enter your password"
                                    class="w-full h-13 px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all duration-200 bg-white/50 backdrop-blur-sm"
                                />
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                </div>
                            </div>
                            <InputError :message="errors.password" />
                        </div>

                        <!-- Remember Me -->
                        <div class="flex items-center justify-between py-2">
                            <Label for="remember" class="flex items-center space-x-3 cursor-pointer">
                                <Checkbox id="remember" name="remember" :tabindex="3" class="border-2 border-gray-300" />
                                <span class="text-sm text-gray-700 font-medium">Remember me</span>
                            </Label>
                        </div>

                        <!-- Login Button -->
                        <Button
                            type="submit"
                            class="w-full h-13 py-3 bg-gradient-to-r from-blue-600

                             text-white font-semibold
                             rounded-lg shadow-lg
                              disabled:opacity-50 disabled:cursor-not-allowed
                              "
                            :tabindex="4"
                            :disabled="processing"
                        >
                            <LoaderCircle v-if="processing" class="w-5 h-5 animate-spin mr-2" />
                            <span v-if="!processing">Sign In to Your Account</span>
                            <span v-else>Signing In...</span>
                        </Button>
                    </div>

                    <!-- Sign Up Link -->
                    <div class="text-center pt-4 border-t border-gray-200">
                        <p class="text-gray-600">
                            Don't have an account?
                            <TextLink :href="route('register')" :tabindex="5" class="text-blue-600 hover:text-blue-700 font-semibold hover:underline transition-colors ml-1">
                                Create your medical profile
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
                <p class="text-xs text-gray-400">© 2025 MediJobs. Connecting medical professionals with opportunities.</p>
            </div>
        </div>
    </div>
</template>
