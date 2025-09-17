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
    <!-- Kenyan ribbon (subtle flag accent) -->
    <div class="fixed top-0 left-0 right-0 z-[60] h-1">
        <div class="h-full grid grid-cols-12">
            <div class="col-span-3 bg-black"></div>
            <div class="col-span-3 bg-white"></div>
            <div class="col-span-3 bg-red-600"></div>
            <div class="col-span-3 bg-green-600"></div>
        </div>
    </div>

    <div class="min-h-screen bg-gradient-to-br from-emerald-50 via-white to-green-50 flex items-center justify-center p-4 pt-8">
        <Head title="Log in" />

        <div class="w-full max-w-md">
            <!-- Header Section -->
            <div class="text-center mb-8">
                <div class="flex justify-center items-center space-x-3 mb-6">
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
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Karibu Tena</h1>
                <p class="text-gray-600">Enter your credentials to access your medical career dashboard</p>
            </div>

            <!-- Login Form Card -->
            <div class="bg-white/90 backdrop-blur-lg rounded-2xl shadow-xl border border-gray-100 p-8 relative overflow-hidden">
                <!-- Decorative circles in Kenyan palette -->
                <div class="absolute top-4 right-4 w-8 h-8 bg-emerald-200/30 rounded-full"></div>
                <div class="absolute bottom-4 left-4 w-6 h-6 bg-red-200/30 rounded-full"></div>

                <div v-if="status" class="mb-6 p-4 bg-emerald-50 border border-emerald-200 rounded-lg text-sm font-medium text-center text-emerald-700">
                    {{ status }}
                </div>

                <Form method="post" :action="route('login')" :reset-on-success="['password']" v-slot="{ errors, processing }" class="space-y-6 relative z-10">
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
                                    class="w-full h-13 px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all duration-200 bg-white/80 backdrop-blur-sm hover:border-emerald-300"
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
                                <TextLink v-if="canResetPassword" :href="route('password.request')" class="text-sm text-emerald-600 hover:text-emerald-700 font-medium hover:underline transition-colors" :tabindex="5">
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
                                    class="w-full h-13 px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all duration-200 bg-white/80 backdrop-blur-sm hover:border-emerald-300"
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
                                <Checkbox id="remember" name="remember" :tabindex="3" class="border-2 border-gray-300 data-[state=checked]:bg-emerald-600 data-[state=checked]:border-emerald-600" />
                                <span class="text-sm text-gray-700 font-medium">Remember me</span>
                            </Label>
                        </div>

                        <!-- Login Button -->
                        <Button
                            type="submit"
                            class="w-full h-13 py-3 bg-gradient-to-r from-emerald-600 to-green-700 hover:from-emerald-700 hover:to-green-800 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none"
                            :tabindex="4"
                            :disabled="processing"
                        >
                            <LoaderCircle v-if="processing" class="w-5 h-5 animate-spin mr-2" />
                            <span v-if="!processing">Access Your Dashboard</span>
                            <span v-else>Signing In...</span>
                        </Button>
                    </div>

                    <!-- Sign Up Link -->
                    <div class="text-center pt-4 border-t border-gray-200">
                        <p class="text-gray-600">
                            Don't have an account?
                            <TextLink :href="route('register')" :tabindex="5"
                                      class="text-red-600 hover:text-emerald-700 font-semibold hover:underline transition-colors ml-1">
                                Join MediCareers Kenya
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
                            <span>License Verified</span>
                        </div>
                        <span class="text-gray-300">•</span>
                        <div class="flex items-center space-x-1">
                            <div class="w-2 h-2 bg-red-500 rounded-full"></div>
                            <span>Trusted by 25K+ Medics</span>
                        </div>
                        <span class="text-gray-300">•</span>
                        <div class="flex items-center space-x-1">
                            <div class="w-2 h-2 bg-black rounded-full"></div>
                            <span>Kenya-Wide Coverage</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Links -->
            <div class="text-center mt-8 space-y-3">
                <div class="flex items-center justify-center space-x-4 text-sm text-gray-500">
                    <a href="#" class="hover:text-emerald-600 transition-colors">Privacy Policy</a>
                    <span>•</span>
                    <a href="#" class="hover:text-emerald-600 transition-colors">Terms of Service</a>
                    <span>•</span>
                    <a href="#" class="hover:text-emerald-600 transition-colors">Help</a>
                </div>
                <p class="text-xs text-gray-400">© 2025 MediCareers Kenya. Connecting medical professionals across 47 counties.</p>
            </div>
        </div>
    </div>
</template>
